  <div class="inportant-note">
    <p>
        @if(session()->get('locale')=='bn')
        সরবরাহের সুযোগ
        @else
        Delivery Options 
        @endif
    </p>
    <table style="width: 100%;">
      <tr>
        <td width="10%"><i class="fa fa-truck"></i></td>
        <td width="90%" >
        <!--<strong>Home Delivery</strong><br>-->
        <span style="font-size: 14px;"><b>
            @if(session()->get('locale')=='bn')
            ঢাকার ভিতরে ডেলিভারি
            @else
            Delivery Inside Dhaka
            @endif
        </b> 
        BDT  
        @if($product->shipping_cost > 0)
        {{number_format($product->shipping_cost,0)}}
        @else

        Dhaka City

        @endif
        </span><br>

        <span><b>Delivery Time:</b> 2 - 4 Day</span><br>

        <span style="font-size: 14px;"><b>
         @if(session()->get('locale')=='bn')
            ঢাকার বাহিরে ডেলিভারি
            @else
           Delivery Out Side Dhaka
            @endif

        </b> 
        BDT 
        @if($product->shipping_cost2 > 0)
        {{number_format($product->shipping_cost2,0)}}
        @else

        Ou Of Dhaka
        @endif

        </span><br>

        <span><b>Delivery Time:</b> 5 - 7 Day</span>

        </td>
        <!--<td width="35%">-->
        <!--    <h6> Inside Dhaka BDT 60 </h6>-->
        <!--    <h6> Out Side Dhaka BDT 100 </h6>-->
        <!--</td>-->
      </tr>
    </table>
     @if(session()->get('locale')=='bn')
    <p>নগদ মূল্যে ডেলিভারি</p>
    @else
    <p>Cash On Delivery </p>
    @endif
    <table style="width: 100%;">
      <tr>
        <table>
          <td width="10%">

            <i class="fas fa-money-bill"></i>
          </td>
          <td width="90%"><strong>
               @if(session()->get('locale')=='bn')
              ক্যাশ অন ডেলিভারি সহজলভ্য
              @else
              Cash on Delivery Available
              @endif
              </strong></td>
          <td width="1%"></td>
        </tr>
      </table>
      @if(session()->get('locale')=='bn')
      <p>রিটার্ন এবং ওয়ারেন্টি</p>
      @else
      <p>Return & Warranty</p>
      @endif
      <table style="width: 100%;">
        <tr>
          <td width="10%" style="vertical-align: top;"><i class="fa fa-undo"></i></td>
          <td>
            @if(session()->get('locale')=='bn')
            <strong>৭ দিনের রিটার্ন</strong><br><span>মতের পরিবর্তন প্রযোজ্য নয়</span>
            @else
              <strong>7 Day Return</strong><br><span>Change of mind Not available</span>
            @endif
          </td>
          <td width="1%"></td>
        </tr>
        <tr>
          <td width="10%"></td>
	  <td>
	        @if($product->warranty!=null)
	        
            @if(session()->get('locale')=='bn')
             <strong>ওয়্যারেন্টি উপলভ্য নয়</strong>
             @else
              <strong>Warranty available</strong>
            @endif
            <br><strong>{{$product->warranty}}</strong>

            @else
             @if(session()->get('locale')=='bn')
             <strong>ওয়্যারেন্টি উপলভ্য নয়</strong>
             @else
              <strong>Warranty not available</strong>
            @endif

            @endif
            </td>
          <td width="1%"></td>
        </tr>
      </table>              
    </div>


    
    
    