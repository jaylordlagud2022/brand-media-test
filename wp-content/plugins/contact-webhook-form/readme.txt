CONTACT WEBHOOK FORM
====================

Installation
1. In WordPress, go to Plugins > Add New Plugin > Upload Plugin.
2. Upload contact-webhook-form.zip and activate it.
3. Open Settings > Contact Webhook Form.
4. Enter your webhook URL and save.
5. Add [contact_webhook_form] to a page, post, or shortcode widget.

Webhook payload
The plugin sends a JSON POST request with:
- name
- email
- phone
- submitted_at
- source

The webhook must respond with an HTTP 2xx status for the form to show success.
SweetAlert2 is loaded from jsDelivr when the shortcode is displayed.
