// // jQuery(document).on("click", "#style_submit", function() {
//   // var formMsg = jQuery('#msgsuccess').val();
//  $('#save_style').on('change',function(){
//     var directiq_style = jQuery('#save_style').serializeArray();
//     jQuery.ajax({
//         type : "post",
//         dataType : "json",
//         url : save_style.ajax_url,
//         data : 
//         {
//             action: "get_directiq_styleSave", 
//             directiq_formMsg : directiq_style,
//             // post_id : post_id
//         },
//         success: function(response) {
//             if(response.type == "success") {
//                 jQuery('.success_status').show(); 
//                 jQuery('.failed_status').hide();                
//             } else {
//                 alert("Field is Empty");
//                 jQuery('.failed_status').show();
//                 jQuery('.success_status').hide();
//             }
          
//             location.reload();
//         }
//     });
// });