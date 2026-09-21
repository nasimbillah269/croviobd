<!DOCTYPE html>
 <html class="loading" lang="en">
   <!-- BEGIN: Head-->
   <head>

     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
     <meta name="description" content="" />
     <meta name="keywords" content="" />
     <meta name="author" content="company Nmae" />
     @yield('title')
     <link rel="apple-touch-icon" href="{{asset('app-assets/images/ico/apple-icon-120.png')}}" />
     <link rel="shortcut icon" type="image/x-icon" href="{{asset('app-assets/images/ico/favicon.ico')}}" />

     <!-- BEGIN: Vendor CSS-->
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/vendors.min.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/icheck/icheck.css')}}" />
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/forms/icheck/custom.css')}}" />
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
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/login-register.min.css')}}" />
     <!-- END: Page CSS-->

     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.1/css/all.min.css"/>

     <!-- BEGIN: Custom CSS-->
     <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/style.css')}}" />
     <!-- END: Custom CSS-->
     <style type="text/css">
       .ErrorsPageLayout {
            text-align: center;
            padding: 25px;
        }
        .ErrorsPageLayout h2 {
            color: red;
        }
     </style>
     @stack('css')
   </head>
   <!-- END: Head-->

   <!-- BEGIN: Body-->
   <body class="vertical-layout vertical-menu-modern 1-column   blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
        
        @yield('contents')


     <!-- BEGIN: Vendor JS-->
     <script src="{{asset('app-assets/vendors/js/vendors.min.js')}}"></script>
     <!-- BEGIN Vendor JS-->

     <!-- BEGIN: Page Vendor JS-->
     <script src="{{asset('app-assets/vendors/js/forms/icheck/icheck.min.js')}}"></script>
     <script src="{{asset('app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
     <!-- END: Page Vendor JS-->

     <!-- BEGIN: Theme JS-->
     <script src="{{asset('app-assets/js/core/app-menu.min.js')}}"></script>
     <script src="{{asset('app-assets/js/core/app.min.js')}}"></script>
     <!-- END: Theme JS-->

     <!-- BEGIN: Page JS-->
     <script src="{{asset('app-assets/js/scripts/forms/form-login-register.min.js')}}"></script>
     <!-- END: Page JS-->

     <!-- BEGIN: Icons JS-->
     <script src="https://code.iconify.design/2/2.1.0/iconify.min.js"></script>
     <!-- BEGIN: Icons JS-->
     @stack('js')
   </body>
   <!-- END: Body-->
 </html>