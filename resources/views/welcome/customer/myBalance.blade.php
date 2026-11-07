@extends(general()->theme.'.layouts.app')
@section('title')
<title>My Balance | {{general()->title}} | {{general()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!general()->meta_dsc!!}">
<meta name="keywords" content="{{general()->meta_key}}">
<meta property="og:title" content="{{general()->name}}">
<meta property="og:description" content="{!!general()->meta_dsc!!}">
<meta property="og:image" content="{{asset(general()->logo())}}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')
@endpush
@section('contents')

<div class="userdashboard">
    <div class="container">
    <div class="row">
        <div class="col-lg-3 usersidebardiv">
            @include(general()->theme.'.customer.includes.sidebar')
        </div>
        <div class="col-lg-9 usermainbody">

            @include(general()->theme.'.alerts')

            <div class="row" style="margin:0 -12px;">
                <div class="col-md-6" style="padding:0 12px;margin-bottom:24px;">
                    <div class="mybalanetotal cd-balance-card">
                        <p>Money Bag Balance ({{ general()->currency ?: 'BDT' }})</p>
                        <span class="cd-balance">{{ number_format(Auth::user()->balance ?: 0, 2) }}</span>
                    </div>
                </div>
                <div class="col-md-6" style="padding:0 12px;margin-bottom:24px;">
                    <div class="mybalanetotal">
                        <p style="margin-bottom:12px !important;">Add Balance</p>
                        <form method="post" action="{{ route('customer.addBalanceToWallet') }}">
                            @csrf
                            <div class="input-group">
                                <input type="number" required class="form-control" name="amount" placeholder="Amount, e.g. 5000">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" style="border-radius:0 8px 8px 0;">Add Balance</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="cd-card">
                <div class="cd-card-head">
                    <div>
                        <h4>All Transactions</h4>
                    </div>
                </div>
                <div class="cd-table-wrap">
                    <table class="table cd-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Previous Balance</th>
                                <th>Transferred</th>
                                <th>New Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="6" style="text-align:center;color:#9ca3af;padding:32px !important;">No transactions yet</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
@endpush
