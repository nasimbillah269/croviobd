@extends('admin.layouts.app') @section('title')
<title>Account Transfer - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Account Transfer</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Account Transfer</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
        
            <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#addTransfer">
                New Transfer
            </button>
            
            <a class="btn btn-outline-primary" href="{{route('admin.accountsTransfer')}}">
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
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div id="accordion">
                                <div
                                    class="card-header collapsed"
                                    data-toggle="collapse"
                                    data-target="#collapseTwo"
                                    aria-expanded="false"
                                    aria-controls="collapseTwo"
                                    id="headingTwo"
                                    style="background: #f5f7fa; padding: 10px; cursor: pointer; border: 1px solid #00b5b8;"
                                >
                                    Search click Here..
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" style="border: 1px solid #00b5b8; border-top: 0;">
                                    <div class="card-body">
                                        <form action="{{route('admin.accountsTransfer')}}">
                                            <div class="row">
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group">
                                                        <input type="date" name="startDate" value="{{$r->startDate?Carbon\Carbon::parse($r->startDate)->format('Y-m-d') :''}}" class="form-control {{$errors->has('startDate')?'error':''}}" />
                                                        <input type="date" value="{{$r->endDate?Carbon\Carbon::parse($r->endDate)->format('Y-m-d') :''}}" name="endDate" class="form-control {{$errors->has('endDate')?'error':''}}" />
                                                    </div>
                                                </div>
        

                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group">
                                                        <select class="form-control" name="method">
                                                            <option value="">Select Method</option>
                                                            @foreach($methods as $method)
                                                            <option value="{{$method->id}}" {{$r->method?$r->method==$method->id?'selected':'':''}}>{{$method->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-success rounded-0">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">Transfer Balance</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 50px;">SL</th>
                                            <th style="min-width: 150px;width: 150px;">From</th>
                                            <th style="min-width: 150px;width: 150px;">To</th>
                                            <th style="min-width: 120px;width: 120px;">Amount</th>
                                            <th style="min-width: 170px;width: 170px;">Date</th>
                                            <th style="min-width: 100px;">Note</th>
                                            <th style="min-width: 120px;width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transfers as $i=>$transfer)
                                        <tr>
                                            <td>
                                            {{$transfers->currentpage()==1?$i+1:$i+($transfers->perpage()*($transfers->currentpage() - 1))+1}}
                                            </td>
                                            <td>
                                                {{$transfer->formmethod?$transfer->formmethod->name:''}}
                                            </td>
                                            <td>
                                                {{$transfer->method?$transfer->method->name:''}}
                                            </td>
                                            <td>
                                                <span>{{priceFormat($transfer->amount)}}</span>
                                            </td>
                                            <td>
                                                <span>{{$transfer->created_at->format('d-m-Y h:i A')}}</span>
                                            </td>
                                            <td style="padding: 5px; text-align: center;">
                                                {!!$transfer->description!!}
                                            </td>
                                            <td class="center">

                                                {{-- <button class="btn btn-sm btn-info" type="button" data-toggle="modal" data-target="#updateType{{$transfer->id}}">
                                                   Edit
                                                </button>  --}}

                                                <!-- Modal -->
                                                 <div class="modal fade text-left" id="updateType{{$transfer->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                                                   <div class="modal-dialog" role="document">
                                                     <div class="modal-content">
                                                        <form action="{{route('admin.accountsTransferUpdate',['update',$transfer->id])}}" method="post">
                                                            @csrf
                                                           <div class="modal-header">
                                                             <h4 class="modal-title" id="myModalLabel1">Update Type</h4>
                                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                               <span aria-hidden="true">&times; </span>
                                                             </button>
                                                           </div>
                                                           <div class="modal-body">
                                                            <div class="form-group">
                                                                    <label>Date*</label>
                                                                    <input type="date" name="date" value="{{$transfer->created_at->format('Y-m-d')}}" class="form-control"required="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Amount*</label>
                                                                <input type="number" value="{{$transfer->amount}}" name="amount" class="form-control" placeholder="0" required="">
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-6">
                                                                    <label>From Method Type*</label>
                                                                    <select class="form-control paymentMethod" name="frommethod" required="">
                                                                        <option value="">Select Method Type</option>
                                                                        @foreach($methods as $method)
                                                                        <option value="{{$method->id}}" {{$method->id==$transfer->src_id?'selected':''}}>{{$method->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-6">
                                                                    <label>Method *</label>
                                                                    <select class="form-control paymentOption" name="frompaymentOption" required="">
                                                                     @foreach($transfer->formmethodOptions as $formetOption)
                                                                     <option value="{{$formetOption->id}}" {{$formetOption->id==$transfer->transection_id?'selected':''}} >{{$formetOption->name}}</option>
                                                                     @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                            
                                                            <div class="row">
                                                                <div class="form-group col-6">
                                                                    <label>To Method Type*</label>
                                                                    <select class="form-control paymentMethod2" name="tomethod" required="">
                                                                        <option value="">Select Method Type</option>
                                                                        @foreach($methods as $method)
                                                                        <option value="{{$method->id}}" {{$method->id==$transfer->method_id?'selected':''}}>{{$method->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-6">
                                                                    <label>Method *</label>
                                                                    <select class="form-control paymentOption2" name="topaymentOption" required="">
                                                                     @foreach($transfer->methodOptions as $formetOption)
                                                                     <option value="{{$formetOption->id}}" {{$formetOption->id==$transfer->method_option_id?'selected':''}}>{{$formetOption->name}}</option>
                                                                     @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                    <label>Description</label>
                                                                    <textarea class="form-control" name="description" placeholder="Write Description">{!!$method->billing_note!!}</textarea>
                                                            </div>
                                                       </div>
                                                           <div class="modal-footer">
                                                             <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
                                                             <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Update</button>
                                                           </div>
                                                       </form>
                                                     </div>
                                                   </div>
                                                 </div>

                                                <a href="{{route('admin.accountsTransferUpdate',['delete',$transfer->id])}}" class="btn btn-sm btn-danger" onclick="return confirm('Are You Want To Delete?')"><i class="fa fa-trash"></i></a>
                                               
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{$transfers->links('pagination')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>


 <!-- Modal -->
 <div class="modal fade text-left" id="addTransfer" >
   <div class="modal-dialog" role="document">
     <div class="modal-content">
        <form action="{{route('admin.accountsTransfer')}}" method="post">
            @csrf
           <div class="modal-header">
             <h4 class="modal-title" id="myModalLabel1">New Transfer</h4>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times; </span>
             </button>
           </div>
           <div class="modal-body">
                <div class="form-group">
                        <label>Date*</label>
                        <input type="date" name="date" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" class="form-control"required="">
                </div>
                <div class="form-group">
                    <label>Amount*</label>
                    <input type="number" name="amount" class="form-control" placeholder="0" required="">
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>From Method Type*</label>
                        <select class="form-control paymentMethod" name="frommethod" required="">
                            <option value="">Select Method Type</option>
                            @foreach($methods as $method)
                            <option value="{{$method->id}}" >{{$method->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label>Method *</label>
                        <select class="form-control paymentOption" name="frompaymentOption" required=""></select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-6">
                        <label>To Method Type*</label>
                        <select class="form-control paymentMethod2" name="tomethod" required="">
                            <option value="">Select Method Type</option>
                            @foreach($methods as $method)
                            <option value="{{$method->id}}" >{{$method->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label>Method *</label>
                        <select class="form-control paymentOption2" name="topaymentOption" required="">
                         
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" placeholder="Write Description"></textarea>
                </div>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
             <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Transfer</button>
           </div>
       </form>
     </div>
   </div>
 </div>


@endsection 

@push('js') 
<script type="text/javascript">
    
    $(document).ready(function(){

        $(".paymentMethod2").on("change", function(){
            
            var id = $(this).val();
              if(id==''){
               $('.paymentOption2').empty().append();
              }
              var url ='{{url('payments/filter')}}' + '/'+id;
              $.get(url,function(data){
                $('.paymentOption2').empty().append(data.paymentData);
              });

        });


    });

</script>
@endpush
