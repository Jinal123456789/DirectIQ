// jQuery(document).ready(function(){
//     jQuery(document).on("click", "#save_msg", function() {
//         var directiq_formMsg = jQuery('#save_message').serializeArray();
//          $.each(directiq_formMsg, function(i, field) {
//                     $("#area").append("<br>" + field.name + ":" + field.value );
//                 });
//         jQuery.ajax({
//             type : "post",
//             dataType : "json",
//             url : save_message.ajax_url,
//             data : 
//             {
//                 action: "get_directiqMessageSave", 
//                 directiq_formMsg   : directiq_formMsg ,
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