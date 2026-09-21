@extends('admin.layouts.app')
@section('title')
<title>Dashbaord - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">

</style>
@endpush
@section('contents')
<div class="content-header row">
   </div>
   <div class="content-body">
    <!-- Grouped multiple cards for statistics starts here -->
  <div class="row grouped-multiple-statistics-card">
     <div class="col-12">
       <div class="card">
         <div class="card-body">
           dsfsdf <span class="ajax-main-search">Click</span>
         </div>
       </div>
     </div>
   </div>
   <!-- Grouped multiple cards for statistics ends here -->

</div>
@endsection
@push('js')
<script>
    $(document).ready(function(){

        setTimeout(function(){
               
                var url ="{{route('admin.dashboardData')}}";
                
                window.location.replace(url);
                
                
                },5000);
            
          });
        
</script>
@endpush