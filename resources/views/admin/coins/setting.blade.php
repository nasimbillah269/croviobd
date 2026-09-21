@extends('admin.layouts.app') 

@section('title')
<title>Point Setting - {{general()->title}} | {{general()->subtitle}}</title>
@endsection 

@push('css')


@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Point Setting</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Point Setting</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
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
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Point Setting</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                    <form action="{{route('admin.coinSetting')}}" method="post">
                   				        @csrf
                                        <table class="table table-borderless">
                                         <tr>
                                             <th>Order Point Bonus (%)</th>
                                             <td style="padding:3px;">
                                                <input type="number" class="form-control form-control-sm" name="coin_bonus" step="any" value="{{general()->coin_bonus}}" placeholder="Point bonus">
                                             </td>
                                         </tr>
                                         <tr>
                                             <th>Point Convert {{general()->currency}} Rate (%)</th>
                                             <td style="padding:3px;">
                                                <input type="number" class="form-control form-control-sm" name="coin_convert_rate" step="any" value="{{general()->coin_convert_rate}}" placeholder="Point convert rate">
                                             </td>
                                         </tr>
                                         <tr>
                                             <th>Minimum Order Amount</th>
                                             <td style="padding:3px;">
                                                <input type="number" class="form-control form-control-sm" name="coin_minimum_order" value="{{general()->coin_minimum_order}}" placeholder="Point minimum order">
                                             </td>
                                         </tr>
                                         <tr>
                                             <th>Minimum Point Balance</th>
                                             <td style="padding:3px;">
                                                <input type="number" class="form-control form-control-sm" name="coin_minimum_blance" value="{{general()->coin_minimum_blance}}" placeholder="Point minimum balance">
                                             </td>
                                         </tr>
                                         <tr>
                                             <th>Status</th>
                                             <td style="padding:3px;">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="status" name="coin_status" {{general()->coin_status?'checked':''}}/>
                                                    <label class="custom-control-label" for="status">Active</label>
                                                </div>
                                             </td>
                                         </tr>
                                        
                                         <tr>
                                             <th>Action</th>
                                             <td style="padding:3px;">
                                                <button type="submit" class="btn btn-info" style="padding:5px 10px;">Update</button>
                                             </td>
                                         </tr>
                                     </table>
                                    </form>
                                    </div>
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
