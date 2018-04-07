

 /* ==============================================
      BACK TOP
    =============================================== */

        jQuery(window).scroll(function(){
          if (jQuery(this).scrollTop() > 1) {
            jQuery('.dmtop').css({bottom:"20px"});
          } else {
            jQuery('.dmtop').css({bottom:"-100px"});
          }
        });
        jQuery('.dmtop').click(function(){
          jQuery('html, body').animate({scrollTop: '0px'}, 800);
          return false;
        });
	
	
//$('tr').click( function() {
//    window.location = $(this).find('a').attr('href');
//}).hover( function() {
//    $(this).toggleClass('hover');
//});

$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});