@extends('admin.layouts.app') 

@section('title')
<title>Point users - {{general()->title}} | {{general()->subtitle}}</title>
@endsection 

@push('css')


@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Point users</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Point users</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.coinUsers')}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        @include('admin.alerts')

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-sm-1 mb-xl-0">
                                     <span class="card-icon primary d-flex justify-content-center mr-2">
                                       <i class="fas fa-coins customize-icon font-large-2 p-1"></i>
                                     </span>
                                     <div class="stats-amount" style="margin-right: 0 !important;">
                                       <h3 class="heading-text text-bold-600">{{priceFormat($totalCoin+$totalUsed)}}</h3>
                                       <p class="sub-heading">Total Point ({{priceFullFormat($totalCoinPrice+$totalUsedPrice)}})</p>
                                     </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-sm-1 mb-xl-0">
                                     <span class="card-icon primary d-flex justify-content-center mr-2">
                                       <i class="fas fa-coins customize-icon font-large-2 p-1"></i>
                                     </span>
                                     <div class="stats-amount" style="margin-right: 0 !important;">
                                       <h3 class="heading-text text-bold-600">{{priceFormat($totalUsed)}}</h3>
                                       <p class="sub-heading">Used Point ({{priceFullFormat($totalUsedPrice)}})</p>
                                     </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-sm-1 mb-xl-0">
                                     <span class="card-icon primary d-flex justify-content-center mr-2">
                                       <i class="fas fa-coins customize-icon font-large-2 p-1"></i>
                                     </span>
                                     <div class="stats-amount" style="margin-right: 0 !important;">
                                       <h3 class="heading-text text-bold-600">{{priceFormat($totalCoin)}}</h3>
                                       <p class="sub-heading">Available Point ({{priceFullFormat($totalCoinPrice)}})</p>
                                     </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Point users</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                    
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 60px;min-width: 60px;">SL</th>
                                            <th style="min-width: 150px;">Name</th>
                                            <th style="width: 150px;min-width: 150px;" >Order Total</th>
                                            <th style="width: 150px;min-width: 150px;" >Point Balance</th>
                                            <th style="width: 150px;min-width: 150px;">Used Point</th>
                                            <th style="width: 200px;min-width: 200px;">Total Point</th>
                                        </tr>
                                        @foreach($users as $i=>$user)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{priceFormat($user->orders()->count())}}</td>
                                            <td>{{priceFormat($user->balance)}}</td>
                                            <td>{{priceFormat($user->orders()->sum('used_coin'))}}</td>
                                            <td>{{priceFormat($user->orders()->sum('used_coin')+$user->balance)}}</td>
                                        </tr>
                                        @endforeach
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>


    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js')

<script>

          

</script>

@endpush
