(function($) {
  $(document).ready(function(){
    $(".diq-short-copy").click(function(e) {
      let area = document.createElement('textarea');
      document.body.appendChild( area );
      area.style.display = "none";
      let content = document.querySelectorAll('.diq-short-code');
      let copy    = document.querySelectorAll('.diq-short-copy');
        for( let i = 0; i < copy.length; i++ ){
          copy[i].addEventListener('click', function(){
            area.style.display = "block";
            area.value = content[i].innerText;
            area.select();
            document.execCommand('copy');   
            area.style.display = "none";
            this.innerHTML = '<button style="color: red; border:none; background:transparent;">Copied</button>';
            /* arrow function doesn't modify 'this', here it's still the clicked button */
            setTimeout( () => this.innerHTML = "Copy code", 2000 );
          });
        }
    });
  });
})(jQuery);