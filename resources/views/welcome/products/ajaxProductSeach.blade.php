<div class="row" style="background: #e7e7e7;margin: 0;">
    <div class="col-md-12" style="background: white;border-right: 1px solid #c6c6c6;border-bottom: 1px solid #c6c6c6;">
        <p style="margin: 0;cursor: pointer;padding: 5px;">
           <a style="text-decoration: none;color: gray;font-size: 14px;" href="javascript:void(0)">Product Search Result</a> 
        </p>
    </div>
</div>

<div style="border-top: 1px solid #c6c6c6;padding-top: 10px;">

    <div class="postsAuto">
        <div class="dataLastPage" data-lastpage="{{$products->lastPage()}}" data-nowpage="1" data-key="{{$search?:''}}" data-url="{{$products->path()}}">
    
        	@include(App\Models\General::first()->theme.'.products.includes.productsAll6')
    
        </div>
    
     
             
    <!--<div class="text-center mt-2" style="display:block;">-->
    <!--    <div class="loader"><i class="fa fa-spin fa-spinner"></i></div>-->
    <!--</div>-->

    </div>

</div>