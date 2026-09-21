<!DOCTYPE html>
<html lang="en">
    <head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{csrf_token()}}" />
        @yield('title')
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{asset(general()->favicon())}}" />
        @yield('SEO')
        <!-- Google Font CDN-->
        <link href="https://fonts.googleapis.com/css?family=Source Code Pro" rel="stylesheet" />
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Acme&display=swap" rel="stylesheet" />

        <!-- Bootstrap CS CDN -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

        <!-- Font Awesome CSS CDN-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Slick Slider CSS CDN-->
        <link rel="stylesheet" type="text/css" href="{{asset('nihon/css/slick.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('nihon/css/slick-theme.css')}}" />
        
        <link rel="stylesheet" type="text/css" href="{{asset('nihon/css/jquery.fancybox.css')}}" />

        <!-- Matis Menus CSS -->
        <link rel="stylesheet" href="{{asset('nihon/css/metisMenu.css')}}" />

        <!-- Custom Css for this Design -->
        <link rel="stylesheet" href="{{asset('nihon/css/style-v2.2.css')}}" />
        <link rel="stylesheet" href="{{asset('nihon/css/coustome.css')}}" />
        
        <!-- Jquery Script  CDN-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        
        {!!general()->script_head!!}
        
        
        
        <style>
        
        .sticky-header {
            background: #000 !important;
        }
        .sidebar-title {
            background: #000;
        }
      .cartButton .btn {
            background: #000;
      }
      .topSaleBadg {
    background: #000;
      }
      .mobileHeader {
    background: #000;
}

.singlPrice {
    padding: 20px 0;
}

.proudctVeiwDescription ul {
    margin: 0 !important;
    padding: 0 !important;
}
.stockAvailable {
    width: 250px;
}
        
            .main-container {
                min-height: 700px;
            }
            .whatsapp-fixed {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                background-color: #25D366;
                color: white;
                font-size: 24px;
                padding: 15px;
                border-radius: 50%;
                text-align: center;
                z-index: 1000;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                text-decoration: none;
                transition: background-color 0.3s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
            }
            .whatsapp-fixed i {
                color: #ffff;
                font-size: 30px;
            }
            .footer-widget p {
                font-size: 14px;
            }
            
            .breadcrumb-area {
                background: gainsboro;
                padding: 15px;
            }
            .mobileHeader.stick {
                background: #000;
            }
            
            @media screen and (max-width: 768px) {
    .mobileLogo a img {
        border-radius: 5px;
        margin: 0 auto;
        display: block;
    }
}
            
            
            @media only screen and (max-width: 768px) {
                .breadcrumb-area h1 {
                    font-size: 20px;
                }
                .xzoom-container {
    display: block !important;
}
.xzoom-thumbs {
    display: flex;
    flex-wrap: wrap;
}
            }
        </style>
        @stack('css')
    </head>
    
    <body>
        
        
        <!-- WhatsApp Fixed Button -->
        <a href="https://wa.me/88{{general()->mobile}}" class="whatsapp-fixed" target="_blank">
          <i class="fa-brands fa-whatsapp"></i>
        </a>
                
        
        <!--Header Part Include Start-->
        @include(general()->theme.'.layouts.header')

        <!--Main Contant Section Start-->
        <div class="main-container">
        @yield('contents')
        </div>
        <!--Main Contant Section End-->
        
         <!--Footer Part Include Start-->
        @include(general()->theme.'.layouts.footer')
        
        

        <!-- Bootstrap Script  CDN-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <!-- Metis Menus Script -->
        <script src="{{asset('nihon/js/metisMenu.min.js')}}"></script>

        <!-- Sweet Alert CDN -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <!-- Slick slider CDN -->
        <script type="text/javascript" src="{{asset('nihon/js/slick.min.js')}}"></script>

        <!--iconify Script CDN-->
        <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>

        <!-- Custom Script for this Design -->
        <script src="{{asset('nihon/js/myjquery.js')}}"></script>
        
        
          {!!general()->script_body!!}
        


        <script>
        
        function lazyLoadImages($selector, skeletonClass) {
            const $lazyImages = $($selector);
    
            function loadVisibleImages(){
                $lazyImages.each(function (){
                    const $img = $(this);
    
                    if ($img.attr("src")) return; // Skip if already loaded
    
                    const windowBottom = $(window).scrollTop() + $(window).height();
                    const imgTop = $img.offset().top;
    
                    if (imgTop < windowBottom + 200) {
                        const skeleton = $img.prev(`.${skeletonClass}`);
                        $img.attr("src", $img.data("src")).css("opacity", "0");
    
                        $img.on("load", function () {
                            $(this).css("opacity", "1");
                            if (skeleton.length) {
                                skeleton.fadeOut(300, function () {
                                    $(this).remove();
                                });
                            }
                        });
                    }
                });
            }
    
            // Run on scroll and resize
            $(window).on("scroll resize", loadVisibleImages);
    
            // Run initially
            loadVisibleImages();
        }
        
         $(document).ready(function () {
            lazyLoadImages(".lazy", "skeleton");

            // Extra fallback: remove all skeletons after full page load
            $(window).on("load", function () {
                $(".skeleton").fadeOut(300, function () {
                    // $(this).remove();
                });
            });
        });
        
        $(document).ready(function(){
            
            
                
                $("#district").on("change", function(){
                var id = $(this).val();
                  if(id==''){
                   $('#city').empty().append('<option value="">No City</option>');
                  }
                  var url ='{{url('geo/filter')}}' + '/'+id;
                  $.get(url,function(data){
                    $('#city').empty().append(data.geoData);  
                  });   
            });
            
                $(document).on("click", ".Quantityplus", function () {
                    var that =$(this);
                    var m = that.attr("data-max");
                    var id = that.attr("data-id");
                    var cls ='input.qty_'+id;
                    var q = parseInt($(cls).val());
                    
                    q = isNaN(q) ? 1 : q;
                    
                    var access = m - q;
                    
                    if (access > 0) {
                        q++;
                        $(cls).val(q)
                    }
                    
                });
                
                
                $(document).on("click", ".Quantityminus", function () {
                    var that =$(this);
                    var n = that.attr("data-min");
                    var id = that.attr("data-id");
                    var cls ='input.qty_'+id;
                    var q = parseInt($(cls).val());
                    
                    q = isNaN(q) ? 1 : q;
                    
                    if (q > n) {
                        q--;
                        $(cls).val(q)
                        
                    }
                    
                });
            
            $(document).on('click', function(e) {

        	    var container = $(".search-section");
        	    var containerClose = $(".SearchResultDiv");
        	    
        	    if (!$(e.target).closest(container).length) {
        	        containerClose.hide();
        	    }
        
        	});
            
            $(document).on("keyup", ".AjaxSearchProduct", function () {
                
                var search =$(this).val();
                
                if(search.length > 1){
                    var url = $(this).data('url');

                    $.ajax({
                      url: url,
                      type: 'GET',
                      dataType: 'json',
                      cache: false,
                      data:{'search':search}
                    })
                    .done(function(data) {
                        $(".SearchResultDiv").empty().append(data.searchProducts);
                        $(".SearchResultDiv").show();
                    })
                    .fail(function() {
                      // alert("error");
                    });
                    
                }
                
                
            });
            
            $(document).on('click','.wishlistCompareUpdate',function(){
                  var url = $(this).data('url');

                  var that = $( this );

                  $.ajax({
                      url: url,
                      type: 'GET',
                      dataType: 'json',
                      cache: false,
                    })
                    .done(function(data) {
                        if(data.success){

                            $(".viewItemsLists").empty().append(data.itemsView);

                            if(data.status==true){
                              $(that).html('<i class="fa fa-heart"></i>');
                            }else{
                              $(that).html('<i class="fa fa-heart-o"></i>');
                            }
                            if(data.statusType==0){
                              $(".wlcounter").empty().append(data.count);
                              if(data.alert==true){
                                alert('Wishlist Are Full. Cannot Added Over 48 Items.');
                              }
                            }else{
                              $(".cpcounter").empty().append(data.count);
                              if(data.alert==true){
                                alert('Compare Are Full. Cannot Added Over 20 Items.');
                              }
                            }
                        }
                    })
                    .fail(function() {
                      // alert("error");
                    });


            });
                
            $(document).on("click", ".singleaddCart, .singleBuyCart", function (e) {
                    e.preventDefault(); 
                    var that =$(this);
                    var id = that.attr("data-id");
                    var url = that.attr("data-url");
                    
                    var cls ='.addToCartProduct_'+ id;
                    var clsMS ='.MessageSuccess'+ id;
                    var clsCMS ='.CMessageSuccess'+ id;
                    var clsLoading ='.LoadingaddCartHide_'+ id;
                    var clsAction ='.addCartShow_'+ id;

                    var data = $(cls).serialize();
                    $.ajax({
                        url: url,
                        method: "POST",
                        data: data,
                        beforeSend: function() {
                            // $(clsAction).hide();
                            $(clsLoading).show();
                            that.find('img').show(); 
                            $(clsMS).empty();
                        },
                    })
                    .done(function (data) {
                        that.find('img').hide();
                        $(clsMS).empty().append('<div style="border: 1px solid #28a745;padding: 5px 10px;margin-bottom:5px;">Product Added Success</div>');
                        $(clsCMS).empty().append('<a href="{{route('carts')}}" style="padding: 8px 10px;margin-bottom:5px;display:inline-block; background: red;  color: #fff;     width: 100%;    border-radius: 5px;    text-align: center;">View Cart <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </a>');
                        $(".HeaderCartItems").empty().append(data.HeadercartItems);
                        $(".HeaderCartItems2").empty().append(data.HeadercartItems2);
                        
                        if(data.singleAddToCard){
                            $(".homeFlexButton_"+id).empty().append(data.singleAddToCard);
                        }
                        
                        $(clsLoading).hide();
                        // $(clsAction).show();
                        
                        if(data.add_type){
                            window.location.href = "{{ route('checkout') }}";
                        }
                        
                    })
                    .fail(function () {
                        $(clsLoading).hide();
                        // $(clsAction).show();
                        location.reload(true);
                        that.find('img').hide(); 
                    });
                    
            });
            
            $(document).on('click','.cartUpdate',function(){

                var url = $(this).data('url');
                var Dcharge =parseInt($('.cartDeliveryCharge').text());

                if (isNaN(Dcharge)){
                    Dcharge =0;
                }

                $.ajax({
                  url: url,
                  type: 'GET',
                  dataType: 'json',
                  cache: false,
                })
                .done(function(data) {

                    $(".productList-table").empty().append(data.cartItems);
                    $(".HeaderCartItems").empty().append(data.HeadercartItems);
                    $(".HeaderCartItems2").empty().append(data.HeadercartItems2);
                    
                    if(data.singleAddToCard){
                        $(".homeFlexButton_"+data.pro_id).empty().append(data.singleAddToCard);
                    }
                    
                })
                .fail(function() {
                  // alert("error");
                });

            });
            
            
            
        });
            jQuery(document).ready(($) => {
                // $(".quantity").on("click", ".plus", function (e) {
                //     let $input = $(this).prev("input.qty");
                //     let val = parseInt($input.val());
                //     $input.val(val + 1).change();
                // });
                
                
                
                        
                
                // $(".quantity").on("click", ".minus", function (e) {
                //     let $input = $(this).next("input.qty");
                //     var val = parseInt($input.val());
                //     if (val > 0) {
                //         $input.val(val - 1).change();
                //     }
                // });
            });
        </script>
        <script>
        $(document).ready(function () {
            $(window).on('scroll', function () {
                if ($(this).scrollTop() > 100) {
                    $('.mobileHeader').addClass('stick');
                } else {
                    $('.mobileHeader').removeClass('stick');
                }
            });
        });
        </script>     
        
        
        <script>
            $(".cgt-slider").slick({
                dots: false,
                autoplay: true,
                autoplaySpeed: 2500,
                infinite: true,
                speed: 300,
                slidesToShow: 7,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true,
                        },
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ],
            });
        </script>
        <script>
            $(".products-slider").slick({
                dots: false,
                autoplay: true,
                autoplaySpeed: 2500,
                infinite: true,
                speed: 300,
                slidesToShow: 5,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true,
                        },
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ],
            });
        </script>
        <script>
            $(".sale-slider").slick({
                dots: false,
                autoplay: true,
                autoplaySpeed: 2500,
                infinite: true,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true,
                        },
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ],
            });
        </script>

        <script>
            $(".slider-one").slick({
                dots: false,
                autoplay: true,
                autoplaySpeed: 2500,
                infinite: true,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true,
                        },
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ],
            });
        </script>
        
        @stack('js')
        
        
    </body>
</html>
