@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{general()->meta_title}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection 

@push('css')

<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    textarea:focus, input:focus{
    outline: none;
    }
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
    
    .mobileCartItems{
        display:none;
    }

    .cart-page{
        max-width: 100%;
        margin: 30px auto;
        padding: 0;
        box-shadow: none;
    }

    .cart-page h3.cartPageTitle{
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #eee;
    }

    .cartProductBox{
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }

    .cartSummaryBox{
        border-radius: 8px;
        border: 1px solid #eee;
    }

    .cartSummaryBox h4{
        font-weight: 700;
        margin: 0 0 5px;
    }

    .carttable thead th{
        background: #f8f9fa;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: .4px;
        border-top: none;
    }

    .carttable tbody tr:hover{
        background: #fafafa;
    }

    @media only screen and (max-width: 767px) {
        .lx-hidden{
            display:none;
        }
        .mobileCartItems{
            display:block;
        }
        .cart-page p.customer{
            font-size: 15px;
        }
        .cartSummaryBox{
            margin-top: 20px;
        }
    }

</style>

@endpush 

@section('contents')

<div class="main-home-page">
    <div class="container">
    	<div class="cart-page">
    		<h3 class="cartPageTitle">Shopping Cart</h3>

    		 @if (session('info'))
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <strong>Oops! </strong> {{Session::get('info') }}.
            </div>
            @endif
    		
    		<div class="productList-table">

    		     @include(App\Models\General::first()->theme.'.carts.includes.cartItems')

    		</div>
    		
    	</div>
    </div>
</div>

@endsection 

@push('js') 

<script>
    $(document).ready(function(){
        
            $(document).on('change','.cartQtyChange',function(){

                  var url = $(this).data('url');
                  var qty = $(this).val();
                  var Dcharge =parseInt($('.cartDeliveryCharge').text());

                  if (isNaN(Dcharge)){
                    Dcharge =0;
                  }
                
                if(qty==''){
                    qty=1;
                }

                
                $.ajax({
                  url: url,
                  type: 'GET',
                  dataType: 'json',
                  cache: false,
                  data: {'qty':qty},
                })
                .done(function(data) {

                    $(".productList-table").empty().append(data.cartItems);
                    $(".HeaderCartItems").empty().append(data.HeadercartItems);
                    $(".HeaderCartItems2").empty().append(data.HeadercartItems2);
                })
                .fail(function() {
                  // alert("error");
                });


            });
    });
</script>

@endpush