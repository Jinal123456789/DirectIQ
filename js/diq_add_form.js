(function($) {
	$(document).ready(function(){
		$(".diq_remove_sc_entry").click(function(e) {
		    if (confirm('Are you sure?')) {
		        var row = $(this).data('id');
		        $.ajax({
		            type: 'POST',
		            url: diq_sc_form.ajax_url, 
		            data: {
		                action: 'diq_handle_short_code_remove',
		                rec_id: row
		            },
		            success: function (response) {
		                if (response) {
		                    location.reload();
		                }
		            },
		            error: function (jqXHR, textStatus, errorThrown) {
		                console.log(jqXHR, textStatus, errorThrown);
		            }
		        });
		    }
		});

		// selected all record will be delete
		
		$('.action').on('click',function(e) {
		 		var value =  $('#bulk-action-selector-top').val();
		 		if(value == 'diq_delete'){
		 			let checkID = [];
		 			$.each($('.diq_checkbox'), function(index,elem){
		 				if($(elem).prop('checked')){
		 					var currentID = $(this).attr("data-id");
		 					checkID.push(currentID)
		 				}
		 			})
	 					$.ajax({
				 		    type: 'POST',
				 		    url: diq_sc_form.ajax_url, 
				 		    data: {
				 		        action: 'diq_multiple_short_code_remove',
				 		        rec_checked_id: checkID
				 		    },
				 		    success: function (response) {
				 		        if (response) {
				 		            location.reload();
				 		        }
				 		    },
				 		    error: function (jqXHR, textStatus, errorThrown) {
				 		        console.log(jqXHR, textStatus, errorThrown);
				 		    }
				 		});	    
				}
		});


		$("#diq_add_shortcode_form").submit(function(event){
			var edit_id = ($(this).data("diq_edit_form_id")) ? $(this).data("diq_edit_form_id") : "add";

			var diq_html_editor = $('.column .CodeMirror')[0].CodeMirror;
			diq_html_editor.save();

			// html content
			var form_html = diq_html_editor.getValue();
			var form_email_type = form_html.includes('input type="email"'); 
			// var CodeMirror = ($('.CodeMirror').val()) ? $('.CodeMirror').val() : "";
			// console.log(form_html," column form_html");
			// form label
			var form_name = ($('#diq_form_label').val()) ? $('#diq_form_label').val() : "";

			// success message input
			var form_success_msg = ($("#diq_scfrm_text_success").val()) ? $("#diq_scfrm_text_success").val() : "";

			// email message input
			var form_email_msg = ($("#diq_scfrm_text_email").val()) ? $("#diq_scfrm_text_email").val() : "";

			// required field message input
			var form_required_field_msg = ($("#diq_scfrm_text_required_field").val()) ? $("#diq_scfrm_text_required_field").val() : "";

			// general error message input
			var form_general_error_msg = ($("#diq_scfrm_general_error").val()) ? $("#diq_scfrm_general_error").val() : "";

			// console.log(form_general_error_msg,"form_general_error_msg");
			var form_subscribe_list_checkbox = [];
	        $('.diq_subs_list_checkbox:checked').each(function(i){
	          form_subscribe_list_checkbox[i] = $(this).val() ? $(this).val() : "";
	        });
	        // var form_list_label = $(".diq_subs_list_checkbox:checked").id; 
	        var form_subscribe_list_checkbox_label = [];
	        $('.diq_subs_list_checkbox:checked').each(function(i){
	          form_subscribe_list_checkbox_label[i] = $(this).attr("id") ? $(this).attr("id") : "";
	          // form_list_label[i] = document.querySelector(this).id;
	        });
	        // console.log(form_subscribe_list_checkbox_label,"form_subscribe_list_label");
	        
			var form_hide_form_btn = $('input[name="diq_hide_form_radio_btn"]:checked').val();

			var form_redirect_url_front = ($("#diq_redirect_url_front").val()) ? $("#diq_redirect_url_front").val() : "";

			var form_sc_form_appearance = $('#diq_sc_form_appearance').find(":selected").val();

			// var diq_sc_custom_css_appearance = ($('.CodeMirror').val()) ? $('.CodeMirror').val() : "";
			var diq_sc_custom_css_appearance = $('.custom .CodeMirror')[0].CodeMirror;
			diq_sc_custom_css_appearance.save();

			// html content
			var form_style_html = diq_sc_custom_css_appearance.getValue();
			console.log(form_style_html,"custom css");


			var date = new Date();
			var dd = String(date.getDate()).padStart(2, '0');
			var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
			var yyyy = date.getFullYear();
			var form_sc_date = dd+"-"+mm+"-"+yyyy;
		
			if (form_name && (form_subscribe_list_checkbox > '0') && (form_email_type == true)) {
				$.ajax({
					type : "POST",
					dataType : "json",
					url : diq_sc_form.ajax_url,
					data : 	{
						action: "diq_sc_form_submit", 
						f_edit : edit_id,
						f_html : form_html,
						f_label : form_name,
						f_success_msg : form_success_msg,
						f_email_msg : form_email_msg,
						f_required_field_msg : form_required_field_msg,
						f_general_error_msg : form_general_error_msg,
						f_subscribe_list_checkbox : form_subscribe_list_checkbox,
						f_subscribe_list_label_checkbox : form_subscribe_list_checkbox_label,
						f_subscribe_list_size : form_subscribe_list_checkbox.length,
						f_hide_form_btn : form_hide_form_btn,
						f_redirect_url_front : form_redirect_url_front,
						f_sc_form_appearance : form_sc_form_appearance,
						f_diq_sc_custom_css_appearance : form_style_html,
						f_sc_date : form_sc_date
					},
					success: function(response) {
						if (response.status == 1) {
							if (edit_id == "add" ) {
								window.location.href = diq_sc_form.diq_flist;
							}else{
								location.reload();
							}
						}else{
							alert("Error in processing. \n Cannot Save form");						
							location.reload();
						}
						event.preventDefault();
					}
				});
			}else{
				
				if (!(form_name)){
					alert("Please enter form name/label");
				} else {
					if(form_email_type == false)
					{
						alert("Email is required");
					}else{
						alert("Please check checkbox of 'Lists Connected To This Form' option");
					}
				}
			}
			event.preventDefault();
		});
	});
})(jQuery);
  		
jQuery(document).ready( function($) {   
  	jQuery('.target_blank').parent().attr('target','_blank');  
    });