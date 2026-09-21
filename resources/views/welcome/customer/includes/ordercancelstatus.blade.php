<style>
     .statustimeline:before {
    background: linear-gradient(to right, #04a150 0%,#04a150 62%,#ef1b22 62%,#ef1b22 100%);
    }
</style>
                
                <ul>
                    <li>
                        <span class="statuscheckok">
                            <span style="display: inline-block;background: white;">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            </span>
                        </span>
                        <span>Placed Order</span>
                        <span class="time">{{$order->created_at->format('Y-m-d h:i A')}}</span>
                    </li>
                   @if($order->confirmed_at!=null)
                    <li>
                        <span class="statuscheckok">
                            <span style="display: inline-block;background: white;">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            </span>
                        </span>
                        <span>Confimed</span>
                        <span class="time">{{Carbon\Carbon::parse($order->confirmed_at)->format('Y-m-d h:i A')}}</span>
                    </li>
                    @endif
                    @if($order->shipped_at!=null)
                    <li>
                        
                        <span class="statuscheckok">
                            <span style="display: inline-block;background: white;">
                            <i class="fa fa-ellipsis-h" aria-hidden="true" style="background: #f44336;"></i>
                            </span>
                        </span>
                        <span>Shipped</span>
                        <span class="time">{{Carbon\Carbon::parse($order->shipped_at)->format('Y-m-d h:i A')}}</span>
                    </li>
                    @endif
                    @if($order->delivered_at!=null)
                    <li>
                        <span class="statuscheckok">
                            <span style="display: inline-block;background: white;">
                            <i class="fa fa-ellipsis-h" aria-hidden="true" style="background: #f44336;"></i>
                            </span>
                        </span>
                        <span>Delivered</span>
                        <span class="time">{{Carbon\Carbon::parse($order->delivered_at)->format('Y-m-d h:i A')}}</span>
                    </li>
                    @endif
                    <li>
                        <span class="statuscheckok">
                            <span style="display: inline-block;background: white;">
                            <i class="fa fa-times" aria-hidden="true" style="background: #f44336;"></i>
                            </span>
                        </span>
                        <span>Canceled</span>
                        <span class="time">{{Carbon\Carbon::parse($order->cancelled_at)->format('Y-m-d h:i A')}}</span>
                    </li>
                </ul>