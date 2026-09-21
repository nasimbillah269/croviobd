$(document).ready(function(){
    
    $.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
  
  /** Sticky Header js **/

  var header = $('.sticky-header');
  var logo = $('.top-header');
  var middle = $('.middle-header');
    var win = $(window);
    win.on('scroll', function() {
        var scroll = win.scrollTop();
        if (scroll < 250){
            header.removeClass('stick');
            logo.removeClass('hidesection');
            middle.removeClass('hidesection');
        } else {
            header.addClass('stick');
            logo.addClass('hidesection');
            middle.addClass('hidesection');
        }
    });

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
