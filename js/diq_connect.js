(function($) {
	$(document).ready(function(){
		$("#save_api_form").submit(function(event){
			var api = jQuery('#diq_api_key').val();
			
			// submit btn
			$("#diq_save_api_info").prop("disabled", true);

			// input box
			$("#diq_api_key").prop("disabled", true);

			// loader
			$(".cn_api_loader").show();

			if(api){
				$.ajax({
					type : "POST",
					url : diq_con_api.ajax_url,
					data : {
						action: "diq_process_api_info",
						security: diq_con_api.check_nonce,
						key: api
					},
					success:function(response){
						// status span
						$("#cn_api_stts").css({"color": response.span});
						$("#cn_api_admin_status").html(response.html);
						$("#diq_edit_api_stts").html(response.notice);
						$("#cn_api_stts").text(response.text);
						// $("#diq_api_key").prop("disabled", true);
						if(response.status == 0 || response.status == 3 || response.status == 5){
							// input box
							$("#diq_api_key").prop("disabled", true);
							// submit btn
							$("#diq_save_api_info").prop("disabled", false);
							// loader
							$(".cn_api_loader").hide();
							$("#diq_edit_api_info").click(function(event){
								var api = jQuery('#diq_api_key').val();
								jQuery("#diq_api_key").prop("disabled", false);
								$(this).prop("disabled", true);
							});

						}else if(response.status == 1 || response.status == 2){
							$("#diq_api_key").prop("disabled", true);
							// reload
							setTimeout(function(){
								location.reload();
							}, 3000);
							
						}else if(response.status == 6){
							$("#diq_api_key").prop("disabled", true);
							$("#diq_save_api_info").prop("disabled", false);
							$(".cn_api_loader").hide();
							
						}

					}
				});
			}
			event.preventDefault();
		});
		$("#diq_edit_api_info").click(function(){
			var api = jQuery('#diq_api_key').val();
			jQuery("#diq_api_key").prop("disabled", false);
			$(this).prop("disabled", true);
		});
	});
})(jQuery);
