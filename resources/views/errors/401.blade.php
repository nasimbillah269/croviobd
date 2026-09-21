@extends('layouts.app')
@section('title')
<title>Unauthorized - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">
    .showPassword {
    right: 0 !important;
    cursor: pointer;
    }

</style>
@endpush
@section('contents')

<!-- BEGIN: Content-->
<div class="app-content content">
   <div class="content-overlay"></div>
   <div class="content-wrapper">
     <div class="content-header row">
     </div>
     <div class="content-body">
        <section class="row flexbox-container">
            <div class="col-12 d-flex align-items-center justify-content-center">
             <div class="col-lg-4 col-md-8 col-10  p-0">
                 <div class="card border-grey border-lighten-3 m-0">
                 	<div class="ErrorsPageLayout">
                 		<br>
                 		<h2>Oppos!</h2>
	                 	<h5>Unauthorized</h5><br><br>
	                 	<a href="{{route('index')}}" class="btn btn-success"><i class="fa fa-home"></i> Return Home</a>
	               
                        @if(Auth::check())
                        @if(Auth::user()->admin)
                        <a href="{{route('admin.dashboard')}}" class="btn btn-info"><i class="fas fa-th-large"></i> Back 
                        Dashboard</a>
                        @endif
                        @endif
                 	</div>
                 </div>
             </div>
            </div>
        </section>
    </div>
</div>
</div>

@endsection
@push('js')

@endpush

