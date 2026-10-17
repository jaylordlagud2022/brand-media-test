jQuery(function($){
  $(document).on('submit','.cwf-form',function(e){
    e.preventDefault();
    const form=this, $form=$(form), $button=$form.find('.cwf-submit');
    const name=$.trim($form.find('[name="name"]').val());
    const email=$.trim($form.find('[name="email"]').val());
    const phone=$.trim($form.find('[name="phone"]').val());
    const emailPattern=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phonePattern=/^[0-9+\s().-]{6,40}$/;
    const alertBox=(icon,title,text)=>Swal.fire({icon,title,text,confirmButtonText:'OK',confirmButtonColor:'#5969df',borderRadius:'16px'});
    if(!name || name.length>120){alertBox('error','Check your name','Please enter your name (up to 120 characters).');return;}
    if(!emailPattern.test(email) || email.length>190){alertBox('error','Check your email','Please enter a valid email address.');return;}
    if(!phonePattern.test(phone)){alertBox('error','Check your phone','Please enter a valid phone number.');return;}
    $button.prop('disabled',true).find('span').text('Sending…');
    $.ajax({
      url:CWF_DATA.ajaxUrl,method:'POST',dataType:'json',
      data:{action:'cwf_submit_form',nonce:CWF_DATA.nonce,name:name,email:email,phone:phone}
    }).done(function(response){
      if(response && response.success){form.reset();alertBox('success','Message sent',response.data.message||'Your details were sent successfully.');}
      else{alertBox('error','Unable to send',response?.data?.message||'Please try again later.');}
    }).fail(function(xhr){
      const message=xhr.responseJSON?.data?.message||'Something went wrong. Please try again.';
      alertBox('error','Submission failed',message);
    }).always(function(){
      $button.prop('disabled',false).find('span').text('Send message');
    });
  });
});