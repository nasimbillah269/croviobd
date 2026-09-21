<!--@isset($cartsCount)-->
<!-- Side Cart Area  Start------>
<!--<div class="shoppingcart cartboxdiv">-->
<!--    <div style="text-align: center;background: #fff;padding: 5px;color:#ed2128;">-->
<!--            <i class="fas fa-shopping-cart" aria-hidden="true"></i><br>-->
<!--            <span class="badge badge-success cartresults cart-totalitems" style="background-color: #28a745; color: #fff;">-->
<!--                <span class="totalitemsqty">-->
<!--                  @isset($cartsCount)   {{ $cartsCount }} @endisset-->
<!--                </span>-->
           
<!--            @if(session()->get('locale')=='bn')-->
<!--            পণ্য-->
<!--            @else-->
<!--            Items-->
<!--            @endif-->
<!--            </span>-->

<!--    </div>-->
<!--    <div style="text-align: center;padding: 3px 1px;font-size: 12px;">-->
<!--        <span>৳ </span>-->
<!--        <span class="cartTotalPrice">@isset ($cartTotalPrice) {{number_format($cartTotalPrice)}}  @endisset</span>-->

<!--    </div>-->
<!--</div>-->

<!--<div class="shoppingcardiv" id="cartboxwidget">-->
<!--    <div class="row" style="margin: 0;background: #116953;padding: 10px;color:white;">-->
<!--        <div class="col-8" style="padding: 0;">-->
<!--            <i class="fas fa-shopping-cart"></i>-->
<!--            <span class="totalitemsqty">-->
<!--            @isset($cartsCount) {{ $cartsCount }} @endisset-->
<!--            </span> -->
<!--             @if(session()->get('locale')=='bn')-->
<!--           পণ্য-->
<!--            @else-->
<!--            Items-->
<!--            @endif-->
<!--        </div>-->
<!--        <div class="col-4" style="padding: 0;text-align: right;">-->
<!--           <span id="cartboxClose">Close</span> -->
<!--        </div>-->
<!--    </div>-->
<!--    <div>-->

<!--        <div class="allcartsdiv">-->
<!--            @include(App\Models\General::first()->theme.'.carts.includes.cartItemsForSidebarCart')-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<!--@endisset-->