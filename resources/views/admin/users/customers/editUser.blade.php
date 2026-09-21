@extends('admin.layouts.app')
@section('title')
<title>User Profile - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">
    .showPassword {
    right: 0 !important;
    cursor: pointer;
    }
    .ProfileImage{
        max-width: 64px;
        max-height: 64px;
    }
</style>
@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">User Profile</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">User Profile</li>
         </ol>
       </div>
     </div>
    </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       
       <a class="btn btn-outline-primary reloadPage" href="{{route('admin.usersCustomer')}}">
       		Back
       	</a>
       	<a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>
 
	

 <div class="content-body"><!-- Basic Elements start -->
	 
@include('admin.alerts')
    <!-- account setting page start -->
 <section id="page-account-settings">
     <div class="row">
         <!-- left menu section -->
         <div class="col-md-3 mb-2 mb-md-0">
             <ul class="nav nav-pills flex-column mt-md-0 mt-1">
                  <li class="nav-item">
                     <a class="nav-link d-flex {{Request::is('admin/users/customer/edit/'.$user->id.'/profile') || Request::is('admin/users/customer/profile/'.$user->id)?'active':''}}" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
                         <i class="fa fa-user"></i>
                        Profile
                     </a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link d-flex {{Request::is('admin/users/customer/edit/'.$user->id.'/change-password')?'active':''}}" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false">
                         <i class="fa fa-lock"></i>
                        Change Password
                     </a>
                 </li>
             </ul>
         </div>
         <!-- right content section -->
         <div class="col-md-9">
             <div class="card">
                 <div class="card-content">
                     <div class="card-body">
                         <div class="tab-content">
                             <div role="tabpanel" class="tab-pane {{Request::is('admin/users/customer/edit/'.$user->id.'/profile') || Request::is('admin/users/customer/edit/'.$user->id)?'active':''}}" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">
                                 @include('admin.users.includes.userProfile')
                             </div>
                             <div class="tab-pane fade {{Request::is('admin/users/customer/edit/'.$user->id.'/change-password')?'active show':''}}" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                 @include('admin.users.includes.changePassword')
                             </div>

                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>
 <!-- account setting page end -->


</div>



@endsection
@push('js')



@endpush