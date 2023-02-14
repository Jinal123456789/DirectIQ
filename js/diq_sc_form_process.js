(function($) {
    $(document).ready(function(){ 
        // handle front-end form submission
        // forms geneated through directiq shortcode 
        $(".diq_subscriber_form_action").submit(function(e){

             var current_form_id = $(this).data("form_id");
            
            var user_email = $(`form[name= diq_subscriber_form_${current_form_id}]`).find('input[name="email"]').val();
            var user_firstname = $(`form[name= diq_subscriber_form_${current_form_id}]`).find('input[name="firstname"]').val();
            var user_lastname = $(`form[name= diq_subscriber_form_${current_form_id}]`).find('input[name="lastname"]').val();
            var form_listid = $(`form[name= diq_subscriber_form_${current_form_id}]`).find('input[name="listid"]').val();
            // console.log(user_email,"user_email");
            var directiq_Data = jQuery('#diq_form_subs_'+current_form_id).serializeArray();        
            var directiq_form =[];
            directiq_Data.map(function(item){
            if(item.name != 'email_address' &&  item.name != 'listid' && item.name != 'first_name' && item.name != 'last_name' ) {
                directiq_form.push(item);
            }
            })
            var directiq_formData = JSON.stringify(directiq_form);

            var current_form_id = $(this).data("form_id");
            var hide_form = $('#hide_form_'+current_form_id).html();
            var redirect_url_form = $('#redirect_url_form_'+current_form_id).html();
            // if(user_firstname == '' || user_lastname == '' || user_email == ''){
            
            if(user_email == ''){
                $('#required_field_msg_'+current_form_id).show().fadeOut(5000);
                e.preventDefault();
                return false;
            }
            
            if(IsEmail(user_email)==false){
                $('#email_msg_'+current_form_id).show().fadeOut(5000);
                e.preventDefault();
                return false;
            }
            if($(`form[name= diq_subscriber_form_${current_form_id}]`).find('input[name="agree"]').length){
                if($(`form[name= diq_subscriber_form_${current_form_id}]`).find('input[name="agree"]').is(":checked") === false){
                    alert("You must agree to the terms first.");
                    return false;
                }
            }else{
                
            }
                     
            
            $.ajax({
                type: 'POST',
                url: diq_front_sc_form.ajax_url,
                data: {
                    action: 'diq_handle_front_form_submission',
                    email: user_email,
                    firstname: user_firstname,
                    lastname: user_lastname,
                    directiq_formData: directiq_formData,
                    listid: form_listid
                },
                success: function (response) {
                    if (response.status == 1) {

                       var success_msg_done = $('#success_msg_'+current_form_id).show().fadeOut(10000);

                        (hide_form == '1') ? $("#diq_form_subs_"+current_form_id).css({"display": "none"}) : "";
                        
                        if(hide_form == '1' ){
                            $('#hide_succ_msg'+current_form_id).show().fadeOut(10000).delay(20000);
                        }
                        
                        if(redirect_url_form){
                            var re_href = redirect_url_form;
                            document.location.href = re_href;
                            var success_msg_done = $('#success_msg_'+current_form_id).show().fadeOut(10000);
                        }
                        
                        if(hide_form == '1' && redirect_url_form){
                            $('#hide_succ_msg'+current_form_id).show().fadeOut(1000).delay(20000);
                        }

                        $('#diq_form_subs_'+current_form_id)[0].reset();
                       
                    }
                   
                    if (response.status == 2) {
                        $('#email_msg').text(response.message);
                        $('#email_msg').show().fadeOut(5000);
                    }
                    if (response.status == 0) {
                        $('#email_msg').text("Cannot process form");
                        $('#email_msg').show().fadeOut(5000);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                }
            });
            e.preventDefault();
            return false;
        });
    });
})(jQuery);

function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(!regex.test(email)) {
        return false;
    }else{
        return true;
    }
}