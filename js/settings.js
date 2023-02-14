// jQuery(document).ready(function(){
//     jQuery(document).on("click", "#save_sttg", function() {
//         var directiq_formsetting = jQuery('#save_settings').serializeArray();
//          $.each(directiq_formsetting, function(i, field) {
//                     $("#area").append("<br>" + field.name + ":" + field.value );
//                 });
//         jQuery.ajax({
//             type : "post",
//             dataType : "json",
//             url : save_settings.ajax_url,
//             data : 
//             {
//                 action: "get_directiqSettingSave", 
//                 directiq_formsetting   : directiq_formsetting ,
//                 // post_id : post_id
//             },
//             success: function(response) {
//                 if(response.type == "success") {
//                     jQuery('.success_status').show(); 
//                     jQuery('.failed_status').hide();                
//                 } else {
//                     alert("Field is Empty");
//                     jQuery('.failed_status').show();
//                     jQuery('.success_status').hide();
//                 }
              
//                 location.reload();
//             }
//         });
//     });
// });