@extends('admin.layouts.app') @section('title')
<title>Accounts - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Accounts List</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Accounts List</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.accountsList',['type'=>'add-method'])}}">
                <i class="fa-solid fa-money"></i> Add Method
            </a>
            <a class="btn btn-outline-primary" href="{{route('admin.accountsList')}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-12">
                @include('admin.alerts')

                @foreach($methods as $method)
                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">{{$method->name}}
                            @if($method->status=='active')
                            <span class="badge badge-success">{{ucfirst($method->status)}}</span>
                            @else
                            <span class="badge badge-danger">{{ucfirst($method->status)}}</span>
                            @endif
                            <a href="{{route('admin.accountsEdit',['edit',$method->id])}}" class="btn btn-sm btn-info">Edit</a>
                        </h4>
                    </div>
                    <div class="card-content">

                        <div class="card-body">
                            
                              <div class="table-responsive">
                                  <table class="table table-bordered">
                                      <tr>
                                          <th>SL</th>
                                          <th>Method Type</th>
                                          <th>Amount</th>
                                          <th>Status</th>
                                          <th>Note</th>
                                      </tr>
                                      @foreach($method->methodOptions as $i=>$option)
                                      <tr>
                                          <td>{{$i+1}}</td>
                                          <td>{{$option->name}}</td>
                                          <td>{{priceFullFormat($option->amounts)}}</td>
                                          <td>
                                            @if($method->status=='active')
                                            <span class="badge badge-success">{{ucfirst($method->status)}}</span>
                                            @else
                                            <span class="badge badge-danger">{{ucfirst($method->status)}}</span>
                                            @endif
                                        </td>
                                          <td>
                                              {!!$option->description!!}
                                          </td>
                                      </tr>
                                      @endforeach
                                  </table>
                              </div>

                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>





@endsection 

@push('js') 

@endpush
