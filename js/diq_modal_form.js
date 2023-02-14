(function($){
    $(document).ready(function(){
        $(".diq_add_form_tab_btn").click(function(event){
            $('.diq-is-active-tab').removeClass('diq-is-active-tab');
            $(this).addClass('diq-is-active-tab');
            $('.diq-is-show-tab-content').removeClass('diq-is-show-tab-content');
            const index = $(this).index();
            $('.diq_add_form_tab_content').eq(index).addClass('diq-is-show-tab-content');
        });
        
        function loadCSS() {            
            var head = $("#preview").contents().find("head");          
            head.html("<style>" + 'input{display:block;width:100%;padding:.7rem 1rem;font-size:1rem;line-height:1.25;color:#464a4c;background-image:none;background-clip:padding-box;border:1px solid rgba(0,0,0,.15);border-radius:.2rem;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;font-family:inherit} input[type=checkbox]{display:inline-block; width:15px;}' + editor2.getValue() + "</style>");
            var theme_color = $('#diq_sc_form_appearance').find(":selected").val();
            $('#preview').contents().find('input').css("border-color",theme_color);
            $('#preview').contents().find('input[type=submit]').css("background-color",theme_color);

        }; 
        var delay;
        
        var editor2 = CodeMirror.fromTextArea(document.getElementById('code'), {
            mode:"html/text",
            autofocus: true,
            matchBrackets: true,
            styleActiveLine: false,
            lineWrapping: true,
            autoRefresh: true,
            viewportMargin:100,
            airMode: true,
            LineNumbers:true,
            enterMode: "keep",
            tabMode: "shift",
        })
        editor2.focus();
       editor2.setCursor({line: 1, ch: 5})
       $("#tabs").tabs();
        editor2.on("change", function() {
            clearTimeout(delay);
    
            delay = setTimeout(updatePreview, 300);
        });
        $("#tabs").tabs({
        activate: function(event, ui) {
            editor2.refresh();
        }
        });

        function updatePreview() {
            loadCSS();
        }
        setTimeout(updatePreview, 300);

        var delay2;
        const editor = CodeMirror.fromTextArea(document.getElementById("forms"), {
            mode:"xml",
            theme:"default",
            LineNumbers: true,
            autoCloseTags: true,

        })
        editor.on("change", function() {
            clearTimeout(delay2);    
            delay2 = setTimeout(updatePreview2, 100);
        });
        function updatePreview2() {
            var previewFrame2 = document.getElementById('preview');
            var preview2 =  previewFrame2.contentDocument ||  previewFrame2.contentWindow.document;
            preview2.open();
            preview2.write(editor.getValue());
            preview2.close();
            loadCSS();
        }
        setTimeout(updatePreview2, 300);
        editor.setSize('370','250');
        const directiq_preview = document.getElementById('preview');
    
        CodeMirror.on(editor, 'change', function () {
            directiq_preview.innerHTML = editor.getValue();
        });
        CodeMirror.on(editor2, 'change', function () {
            directiq_preview.innerHTML = editor2.getValue();
        });
        var content = $('.column .CodeMirror')[0].CodeMirror;
    

        $(".directiq_popup_modal").submit(function(event){
            event.preventDefault();
            var form_id = $(this).attr('id');
            var alias = $(this).data('alias');
            var modal_id = $(this).data('modal_id');
            var input_label = ($("#"+alias+"_label").val()) ? $("#"+alias+"_label").val() : alias;
            var input_id = ($("#"+alias+"_id").val()) ? $("#"+alias+"_id").val() : alias;
            var input_class = ($("#"+alias+"_class").val()) ? $("#"+alias+"_class").val() : "sample-"+ alias +"-class";
            var input_placeholder = ($("#"+alias+"_placeholder").val()) ? $("#"+alias+"_placeholder").val() : alias;
            var input_type = ($("#"+alias+"_type").val()) ? $("#"+alias+"_type").val() : "text";

            var editor = $('.column .CodeMirror')[0].CodeMirror;
            var editor2 = $('.custom .CodeMirror')[0].CodeMirror;
            var input_name_attr = input_label.toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '_');

            if (alias == "submit") {
                var input_value = ($("#"+alias+"_value").val()) ? $("#"+alias+"_value").val() : "submit";
                var input_name_attr = input_value.toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '_');
                text = '<p>\n<input type="'+ input_type +'" value="' + input_name_attr + '" name="submit" class="' + input_class + '" id="' + input_id + '"/>\n</p>';
            }else if(alias == "agree"){
                var input_url = ($("#"+alias+"_url").val()) ? $("#"+alias+"_url").val() : "#";
                var input_text = ($("#"+alias+"_text").val()) ? $("#"+alias+"_text").val() : "Agree to terms.";

                text = '<p> \n <input name="agree" id="agree" type="' + input_type + '" value="1"/> <a href="'+ input_url +'" target="_blank">'+ input_text +'</a>\n</p>';
                console.log(text,"text");
            }else{
                text = '<p>\n<label>' + input_label + '</label>\n<input type="'+ input_type +'" name="' + alias + '" class="' + input_class + '" id="' + input_id + '" placeholder="' + input_placeholder + '"/>\n</p>';
            }
            insertText(text);
            $('#'+modal_id).modal('toggle');
        });

        function insertText(data) {
            var editor = $(".column .CodeMirror")[0].CodeMirror;
            var doc = editor.getDoc();
            var cursor = doc.getCursor(); 
            var line = doc.getLine(cursor.line);
            var pos = {
                line: cursor.line
            };
            if (line.length === 0) {
                doc.replaceRange(data, pos);
            } else {
                doc.replaceRange("\n" + data, pos);
            }
        }
        $("#diq_sc_form_appearance").change(function() {
              var theme_color = $('#diq_sc_form_appearance').find(":selected").val();
              console.log(theme_color,"theme_color");
              $('#preview').contents().find('input').css("border-color",theme_color);
              $('#preview').contents().find('input[type=submit]').css("background-color",theme_color);
              $("#preview").css("background-color", 'white');

            });
    });
})(jQuery);