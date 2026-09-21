@isset($carts) @if($carts->count() > 0)
<ul style="max-height: 380px; overflow: auto; list-style: none; padding: 0; margin: 0;">
    @foreach($carts as $cart)
    <li>
        <div class="row carted-item itemlist_{{ $cart->id }}" style="margin: 10px; border: 1px solid #e4e0e1; padding: 2px;">
            <div class="col-2" style="padding: 0; text-align: center;">
                <span style="display: block; padding: 0;" class="plusitem cartUpdate" data-url="{{ route('changeToCart', [$cart, 'increment']) }}">+</span>
                <span style="display: block; padding: 0;" class="qtyitem qtyitem_{{ $cart->id }}">{{ $cart->quantity }}</span>
                <span style="display: block; padding: 0;" class="minusitem cartUpdate" data-url="{{ route('changeToCart', [$cart, 'decrement']) }}">-</span>
            </div>
            <div class="col-2" style="padding: 5px; text-align: center; display: flex; -ms-flex-align: center; -webkit-align-items: center; -webkit-box-align: center; align-items: center;">
                <img src="{{asset($cart->product?$cart->product->fi():'')}}" style="width: 100%;" />
            </div>
            <div class="col-5" style="padding: 2px 0;">
                <p style="margin: 0; font-size: 12px;">{{strlimit($cart->product?$cart->product->title:'no-title',25)}}</p>
                <p style="margin: 0; font-size: 10px;">
                    BDT <span style="padding: 0; font-size: 12px;">{{ number_format($cart->itemprice(),0) }}</span>
                    @if($cart->color)
                    <strong>C:</strong> {{$cart->color }} @endif &nbsp; @if($cart->size) <strong>S:</strong> {{$cart->size }} @endif
                </p>
            </div>
            <div class="col-2" style="padding: 0; text-align: center; display: flex; color: #df0100; -ms-flex-align: center; -webkit-align-items: center; -webkit-box-align: center; align-items: center;">
                <span class="itemTotalPrice_{{ $cart->id }}" style="display: block; padding: 0; font-size: 12px;">{{ number_format($cart->subtotal(),0) }}</span>
            </div>
            <div class="col-1" style="padding: 0; text-align: center; display: flex; -ms-flex-align: center; -webkit-align-items: center; -webkit-box-align: center; align-items: center;">
                <span class="removeitem cartUpdate float-right" data-url="{{ route('changeToCart',[$cart, 'delete']) }}" style="cursor: pointer;"><i class="fa fa-times" aria-hidden="true"></i></span>
            </div>
        </div>
    </li>
    @endforeach
</ul>

<div>
    <p style="padding: 5px 15px; margin: 0;">
        @if(session()->get('locale')=='bn') মোট মূল্য @else Sub Total @endif
        <span style="float: right;"> <span>BDT </span><span class="cartTotalPrice"> @isset ($cartTotalPrice) {{number_format($cartTotalPrice)}} @endisset</span> </span>
    </p>
</div>
<div class="row" style="margin: 0; padding: 15px 0;">
    <div class="col-12" style="text-align: center;">
        <a href="{{ route('carts') }}" style="display: inline-block; padding: 10px; background: #116953; border-radius: 3px; color: white; width: 100%;">
            @if(session()->get('locale')=='bn') কার্টে যান @else View Cart @endif
        </a>
    </div>
    <div class="col-12" style="text-align: center;">
        <a href="{{ url('checkout') }}" style="margin: 10px 0; cursor: pointer; display: inline-block; padding: 10px; background: #bf203d; border-radius: 3px; text-decoration: none; color: white; width: 100%;">
            @if(session()->get('locale')=='bn') চেক আউট করুন @else Proccess To CheckOut @endif
        </a>
    </div>
</div>
@else
<div class="emptycarts">
    <center>
        <p>Empty Cart</p>
    </center>
</div>
@endif @endisset
