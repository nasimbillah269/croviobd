$(document).ready(function(){
    
    $.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
  
  /** Sticky Header js **/

  // header stays in place via CSS position:sticky; this only toggles the
  // "scrolled" look (shadow, smaller logo) and is throttled to one update per frame
  var header = $('.sticky-header, .mobileHeader');
  var win = $(window);
  var ticking = false;

  function updateHeader() {
      header.toggleClass('stick', win.scrollTop() > 40);
      ticking = false;
  }

  win.on('scroll', function() {
      if (!ticking) {
          ticking = true;
          window.requestAnimationFrame(updateHeader);
      }
  });
  updateHeader();

    /** Sticky Header js **/


    /** Slick Slider js **/
    $('.slider-five').slick();
    /** Slick Slider js **/


    /** Subsribe Sweat Alert js **/
    $('.subscribe-button').click(function(e){
          e.preventDefault();
          Swal.fire('Thank You for your Subscribe!!');
      });
    /** Subsribe Sweat Alert js **/

    /** Mobile sidebar Menus  js **/
    $(document).on('click','.moble-menus-models',function(){
      $('.mobile-menu-side-modals').addClass('open-side');
      $("body").css('overflow-y','hidden');
    });

    $(document).on('click','.side-modals-close',function(){
      $('.side-modalsBar').removeClass('open-side');
      $("body").css('overflow-y','');
    })
    /** Mobile sidebar Menus  js **/
    
    /** Mobile sidebar Menus  js **/
    $(document).on('click','.mobile-menus-models',function(){
      $('.mobile-menu-side-modals2').addClass('open-side');
      $("body").css('overflow-y','hidden');
    });

    $(document).on('click','.side-modals-close2',function(){
      $('.side-modalsBar2').removeClass('open-side');
      $("body").css('overflow-y','');
    })
    /** Mobile sidebar Menus  js **/
    
    


    /** Matis Menus  js **/
      $('#menu').metisMenu({

      // enabled/disable the auto collapse.
      toggle: true,

      // prevent default event
      preventDefault: true,

      // default classes
      activeClass: 'active',
      collapseClass: 'collapse',
      collapseInClass: 'in',
      collapsingClass: 'collapsing',

      // .nav-link for Bootstrap 4
      triggerElement: 'span',

      // .nav-item for Bootstrap 4
      parentTrigger: 'li',

      // .nav.flex-column for Bootstrap 4
      subMenu: 'ul'

    });

    /** Matis Menus  js **/




});
