<?php
/**
 * Plugin Name: Contact Webhook Form
 * Description: Responsive contact form with Name, Email, and Phone fields, SweetAlert feedback, and an admin-configurable webhook URL.
 * Version: 1.0.0
 * Author: OpenAI
 * License: GPL-2.0-or-later
 */
if (!defined('ABSPATH')) exit;

final class CWF_Contact_Webhook_Form {
    const OPTION = 'cwf_webhook_url';
    public function __construct() {
        add_shortcode('contact_webhook_form', [$this, 'render_form']);
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
        add_action('wp_ajax_cwf_submit_form', [$this, 'submit_form']);
        add_action('wp_ajax_nopriv_cwf_submit_form', [$this, 'submit_form']);
    }

    public function register_assets() {
        wp_register_style('cwf-style', plugin_dir_url(__FILE__) . 'assets/form.css', [], '1.0.0');
        wp_register_script('cwf-sweetalert', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', [], '11.0.0', true);
        wp_register_script('cwf-script', plugin_dir_url(__FILE__) . 'assets/form.js', ['jquery', 'cwf-sweetalert'], '1.0.0', true);
    }

    public function render_form() {
        wp_enqueue_style('cwf-style');
        wp_enqueue_script('cwf-sweetalert');
        wp_enqueue_script('cwf-script');
        wp_localize_script('cwf-script', 'CWF_DATA', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cwf_submit_form'),
        ]);
        ob_start(); ?>
        <div class="cwf-wrap">
          <div class="cwf-card">
            <div class="cwf-heading">
              <span class="cwf-eyebrow">LET'S CONNECT</span>
              <h2>Get in touch</h2>
              <p>Leave your details and we’ll get back to you soon.</p>
            </div>
            <form class="cwf-form" novalidate>
              <label for="cwf-name">Full name</label>
              <div class="cwf-field">
                <input id="cwf-name" name="name" type="text" placeholder="Your full name" autocomplete="name" required maxlength="120">
              </div>
              <label for="cwf-email">Email address</label>
              <div class="cwf-field">
                <input id="cwf-email" name="email" type="email" placeholder="you@example.com" autocomplete="email" required maxlength="190">
              </div>
              <label for="cwf-phone">Phone number</label>
              <div class="cwf-field">
                <input id="cwf-phone" name="phone" type="tel" placeholder="+63 912 345 6789" autocomplete="tel" required maxlength="40">
              </div>
              <button class="cwf-submit" type="submit"><span>Send message</span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
              <p class="cwf-privacy">Your details will only be used to respond to your inquiry.</p>
            </form>
          </div>
        </div>
        <?php return ob_get_clean();
    }

    public function admin_menu() {
        add_options_page('Contact Webhook Form', 'Contact Webhook Form', 'manage_options', 'cwf-settings', [$this, 'settings_page']);
    }

    public function register_settings() {
        register_setting('cwf_settings_group', self::OPTION, [
            'type' => 'string',
            'sanitize_callback' => [$this, 'sanitize_webhook'],
            'default' => '',
        ]);
    }

    public function sanitize_webhook($value) {
        $value = esc_url_raw(trim((string) $value));
        if ($value && !in_array(wp_parse_url($value, PHP_URL_SCHEME), ['https', 'http'], true)) return '';
        return $value;
    }

    public function settings_page() {
        if (!current_user_can('manage_options')) return; ?>
        <div class="wrap">
          <h1>Contact Webhook Form</h1>
          <p>Set the endpoint that receives submissions. The plugin sends a JSON POST request containing <code>name</code>, <code>email</code>, and <code>phone</code>.</p>
          <form method="post" action="options.php">
            <?php settings_fields('cwf_settings_group'); ?>
            <table class="form-table" role="presentation">
              <tr><th scope="row"><label for="<?php echo esc_attr(self::OPTION); ?>">Webhook URL</label></th>
              <td><input class="regular-text" type="url" id="<?php echo esc_attr(self::OPTION); ?>" name="<?php echo esc_attr(self::OPTION); ?>" value="<?php echo esc_attr(get_option(self::OPTION, '')); ?>" placeholder="https://example.com/webhook" />
              <p class="description">Use an HTTPS URL when possible. Leave blank to disable external delivery.</p></td></tr>
            </table>
            <?php submit_button('Save Webhook Settings'); ?>
          </form>
          <hr><p>Place the shortcode <code>[contact_webhook_form]</code> in any page or post.</p>
        </div>
        <?php
    }

    public function submit_form() {
        check_ajax_referer('cwf_submit_form', 'nonce');
        $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';

        if ($name === '' || strlen($name) > 120 || !is_email($email) || strlen($email) > 190 || $phone === '' || strlen($phone) > 40) {
            wp_send_json_error(['message' => 'Please enter a valid name, email address, and phone number.'], 400);
        }
        if (!preg_match('/^[0-9+\s().-]{6,40}$/', $phone)) {
            wp_send_json_error(['message' => 'Please enter a valid phone number.'], 400);
        }

        $webhook = get_option(self::OPTION, '');
        if (!$webhook) {
            wp_send_json_error(['message' => 'The form is not configured yet. Please try again later.'], 500);
        }

        $response = wp_remote_post($webhook, [
            'timeout' => 15,
            'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
            'body' => wp_json_encode([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'submitted_at' => current_time('c'),
                'source' => home_url(),
            ]),
        ]);
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'We could not send your message right now. Please try again.'], 502);
        }
        $status = (int) wp_remote_retrieve_response_code($response);
        if ($status < 200 || $status >= 300) {
            wp_send_json_error(['message' => 'The receiving service could not accept your message. Please try again later.'], 502);
        }
        wp_send_json_success(['message' => 'Thanks! Your details have been sent successfully.']);
    }
}
new CWF_Contact_Webhook_Form();
