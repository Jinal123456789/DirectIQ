(function($){
    $(document).ready(function(){
        $(".diq-duplicate-button").click(function(){
			var  current_id = this.id;
			var date = new Date();
			var dd = String(date.getDate()).padStart(2, '0');
			var mm = String(date.getMonth() + 1).padStart(2, '0');
			var yyyy = date.getFullYear();
			var form_sc_date = dd+"-"+mm+"-"+yyyy;
			
		     $.ajax({
		                type: 'POST',
		                url: diq_duplicate_form.ajax_url,
		                data: {
		                    action: 'diq_duplicate_form_record',
		                    d_current_id : current_id,
		                    d_sc_date : form_sc_date
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
		});
    });
})(jQuery);