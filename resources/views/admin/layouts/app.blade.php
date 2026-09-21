<!DOCTYPE html>
 <html class="loading" lang="en" >
   <!-- BEGIN: Head-->
   <head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
     <meta name="description" content="" />
     <meta name="keywords" content="" />
     <meta name="author" content="NIT" />
     @yield('title')
     <link rel="apple-touch-icon" href="{{asset(general()->favicon())}}" />
     <link rel="shortcut icon" type="image/x-icon" href="{{asset(general()->favicon())}}" />

     <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet" />

     <!-- BEGIN: Vendor CSS-->
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/vendors.min.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/selects/select2.min.css')}}" />
     <!-- END: Vendor CSS-->

     <!-- BEGIN: Theme CSS-->
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap.min.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/bootstrap-extended.min.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/colors.min.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/components.min.css')}}" />
     <!-- END: Theme CSS-->

     <!-- BEGIN: Page CSS-->
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/vertical-menu-modern.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/core/colors/palette-gradient.min.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/fonts/simple-line-icons/style.min.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/card-statistics.min.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/vertical-timeline.min.css')}}" />
     <!-- END: Page CSS-->

     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css"/>
     <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet" />
     <!-- BEGIN: Custom CSS-->
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/style.css')}}" />
     <!-- END: Custom CSS-->

     <!-- BEGIN: Theme JS-->
     <script src="{{asset('app-assets/js/JsBarcode.all.js')}}"></script>

    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>

    <style type="text/css">
        .note-editor.note-frame.fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%!important;
            z-index: 1050;
            background: white;
        }
      ul.statuslist li::before {
          content: '';
          width: 1px;
          height: 16px;
          background: #dddee1;
          position: absolute;
          left: 0px;
          right: auto;
          top: 5px;
      }
      ul.statuslist li:first-child::before {
        width: 0;
      }
      ul.statuslist li {
          display: inline-block;
          position: relative;
          padding: 0 5px;
      }
      ul.statuslist {
          margin: 0;
          padding: 0;
          list-style: none;
          text-align: right;
      }
      .table td, .table th {
        padding: 0.5rem 1rem;
      }

      .ibox-tools {
          display: block;
          float: none;
          margin-top: 0;
          position: absolute;
          top: 7px;
          right: 15px;
          padding: 0;
          text-align: right;
      }

      .btn.btn-md {
            padding: 5px 15px;
            margin: 5px;
        }

    </style>

     @stack('css')
   </head>
   <!-- END: Head-->

   <!-- BEGIN: Body-->
   <body class="vertical-layout vertical-menu-modern 2-columns fixed-navbar hell {{Request::is('admin/pos-orders/create')? 'menu-collapsed' : ''}} " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    @include('admin.layouts.header')
    @include('admin.layouts.sidebar')

    

     <!-- BEGIN: Content-->
     <div class="app-content content">
       <div class="content-overlay"></div>
       <div class="content-wrapper">
         @yield('contents')
       </div>
     </div>
     <!-- END: Content-->


     @include('admin.layouts.footer')


     <!-- BEGIN: Vendor JS-->
     <script src="{{asset('app-assets/vendors/js/vendors.min.js')}}"></script>
     <!-- BEGIN Vendor JS-->

     <!-- BEGIN: Page Vendor JS-->
     <script src="{{asset('app-assets/vendors/js/charts/apexcharts/apexcharts.min.js')}}"></script>
     <script src="{{asset('app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
     <!-- END: Page Vendor JS-->

     <!-- BEGIN: Theme JS-->
     <script src="{{asset('app-assets/js/core/app-menu.min.js')}}"></script>
     <script src="{{asset('app-assets/js/core/app.min.js')}}"></script>
     <script src="{{asset('app-assets/js/scripts/customizer.min.js')}}"></script>
     <!-- END: Theme JS-->
     
     <!-- Drag dropable data  -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{asset('app-assets/js/printThis.js')}}"></script>
    <!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
     <!-- BEGIN: Page JS-->
     <script src="{{asset('app-assets/js/scripts/cards/card-statistics.min.js')}}"></script>
     <script src="{{asset('app-assets/js/scripts/forms/select/form-select2.min.js')}}"></script>
     <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
     <!-- END: Page JS-->
     @stack('js')
     <script type="text/javascript">
      $( function() {
              $( "#sortable" ).sortable();
              $( "#sortable" ).disableSelection();
              $( ".sortable" ).sortable();
              $( ".sortable" ).disableSelection();
          } );

    </script>
     <script>
      $(document).ready(function(){

        $('#PrintAction').on("click", function () {
            $('.PrintAreaContact').printThis();
          });

        $('#PrintAction2').on("click", function () {
            $('.PrintAreaContact2').printThis();
          });

         $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $(document).on('click','.reloadPage',function(){

                location.reload();
                return true;

            });
          
          $(document).on('click','.showPassword',function(){
                $(this).toggleClass('active-show');
                if ($(this).hasClass('active-show')) {
                    $('input.password').prop('type','text');
                    $(this).empty().append('<i class="fa fa-eye"></i>');
                } else {
                    $('input.password').prop('type','password');
                    $(this).empty().append('<i class="fa fa-eye-slash"></i>');
                }
            });

          $(document).on('change','.paymentMethod',function(){
                var id = $(this).val();
                  if(id==''){
                   $('.paymentOption').empty().append();
                  }
                  var url ='{{url('payments/filter')}}' + '/'+id;
                  $.get(url,function(data){
                    $('.paymentOption').empty().append(data.paymentData);
                  });   
            });


          $("#division").on("change", function(){
                var id = $(this).val();
                  if(id==''){
                   $('#district').empty().append('<option value="">No District</option>');
                   $('#city').empty().append('<option value="">No City</option>');
                  }
                  var url ='{{url('geo/filter')}}' + '/'+id;
                  $.get(url,function(data){
                    $('#district').empty().append(data.geoData);
                    $('#city').empty().append('<option value="">No City</option>');
                  });   
            });

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


            $('.mediaDelete').click(function(e){
                e.preventDefault();

              var url =$(this).attr('href');

              if(confirm("Are you sure you want to delete this?")){
                
                $.ajax({
                  url : url,
                  type:'GET',
                  cache: false,
                  contentType: false,
                  dataType: 'json',
                  beforeSend: function()
                  {
                    
                  },
                  complete: function()
                  {
                      
                  },
                  }).done(function (data) {
                     
                     location.reload(true);
                    
                  }).fail(function () {
                      alert('fail');
                  });
                  
              }else{
                  return false;
              }

            });
          
      });
    </script>

    <script type="text/javascript">
      ///Check Box Select With Count show

          $(function() {
            $('.checkCounter').text('0');
            var generallen = $("input[name='checkid[]']:checked").length;
            if (generallen > 0) {
              $(".checkCounter").text('(' + generallen + ')');
            } else {
              $(".checkCounter").text(' ');
            }
            
          })
          
          function updateCounter() {
            var len = $("input[name='checkid[]']:checked").length;
            if (len > 0) {
              $(".checkCounter").text('(' + len + ')');
            } else {
              $(".checkCounter").text(' ');
            }
          }
          
          $("input:checkbox").on("change", function() {
            updateCounter();
          });

       
        $(document).ready(function(){
          $('#checkall').click(function() {
              var checked = $(this).prop('checked');
              $('input:checkbox').prop('checked', checked);
              updateCounter();
            });
        });
        
        ///Check Box Select With Count show
      </script>

    
   </body>
   <!-- END: Body-->
 </html>