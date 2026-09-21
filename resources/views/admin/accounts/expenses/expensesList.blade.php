@extends('admin.layouts.app') @section('title')
<title>Expenses List - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Expenses List</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Expenses List</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">

            <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#addExpenses">
                Add Expenses
            </button>
            
            <a class="btn btn-outline-primary" href="{{route('admin.expensesList')}}">
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
                                        <form action="{{route('admin.expensesList')}}">
                                            <div class="row">
                                                <div class="col-md-12 mb-0">
                                                    <div class="input-group">
                                                        <input type="text" name="search" value="{{$r->search?$r->search:''}}" placeholder="Expenses Name" class="form-control {{$errors->has('search')?'error':''}}" />
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
                        <h4 class="card-title">Expenses List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                              <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 50px;">SL</th>
                                            <th style="min-width: 170px;width: 170px;">Date</th>
                                            <th style="min-width: 150px;width: 150px;">Method</th>
                                            <th style="min-width: 150px;width: 150px;">Amount</th>
                                            <th style="min-width: 150px;width: 150px;">Type</th>
                                            <th style="min-width: 200px;">Description</th>
                                            <th style="min-width: 80px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expenses as $i=>$expense)
                                        <tr>
                                            <td>
                                            {{$expenses->currentpage()==1?$i+1:$i+($expenses->perpage()*($expenses->currentpage() - 1))+1}}
                                            </td>
                                            <td>
                                                <span>{{$expense->created_at->format('d-m-Y h:i A')}}</span>
                                            </td>
                                            <td>
                                                <span>{{$expense->method?$expense->method->name:''}}</span>
                                            </td>
                                            <td>
                                                <span>{{priceFormat($expense->amount)}}</span>
                                            </td>
                                            <td>
                                                <span>{{$expense->expenseType?$expense->expenseType->name:'Others'}}</span>
                                            </td>
                                            <td style="padding: 5px; text-align: center;">
                                                {!!$expense->billing_note!!}
                                            </td>
                                            <td class="center">

                                                <button class="btn btn-sm btn-info" type="button" data-toggle="modal" data-target="#updateExpenses{{$expense->id}}">
                                                   Edit
                                                </button>

                                                <!-- Modal -->
                                                 <div class="modal fade text-left" id="updateExpenses{{$expense->id}}" >
                                                   <div class="modal-dialog" role="document">
                                                     <div class="modal-content">
                                                        <form action="{{route('admin.expensesListManage',['id'=>$expense->id,'type'=>'update'])}}" method="post">
                                                            @csrf
                                                           <div class="modal-header">
                                                             <h4 class="modal-title" id="myModalLabel1">Update Expense</h4>
                                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                               <span aria-hidden="true">&times; </span>
                                                             </button>
                                                           </div>
                                                           <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Date*</label>
                                                                    <input type="date" name="date" value="{{$expense->created_at->format('Y-m-d')}}" class="form-control" required="">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Expense Type*</label>
                                                                    <select class="form-control" name="type" required="">
                                                                        <option value="">Select Type</option>
                                                                        @foreach($types as $type)
                                                                        <option value="{{$type->id}}" {{$expense->src_id==$type->id?'selected':''}}>{{$type->name}}</option>
                                                                        @endforeach
                                                                        <option value="0" {{$expense->src_id==0?'selected':''}}>Others</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Payment Method Type*</label>
                                                                    <select class="form-control paymentMethod" name="method" required="">
                                                                        <option value="">Select Method</option>
                                                                        @foreach($methods as $method)
                                                                        <option value="{{$method->id}}" {{$expense->method_id==$method->id?'selected':''}}>{{$method->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Payment Method*</label>
                                                                    <select class="form-control paymentOption" name="methodoption" required="">
                                                                       @if($expense->methodOptions->count() > 1)
                                                                        <option value="">Select Option</option>
                                                                        @endif
                                                                        @foreach($expense->methodOptions as $data)
                                                                        <option value="{{$data->id}}" {{$expense->method_option_id==$data->id?'selected':''}}>{{$data->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Amount*</label>
                                                                    <input type="number" name="amount" value="{{$expense->amount}}" class="form-control" required="">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Description</label>
                                                                    <textarea class="form-control" name="description" placeholder="Write Description">{!!$expense->billing_note!!}</textarea>
                                                                </div>
                                                           </div>
                                                           <div class="modal-footer">
                                                             <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
                                                             <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Update Expense</button>
                                                           </div>
                                                       </form>
                                                     </div>
                                                   </div>
                                                 </div>

                                                <a href="{{route('admin.expensesListManage',['id'=>$expense->id,'type'=>'delete'])}}" class="btn btn-sm btn-danger" onclick="return confirm('Are You Want To Delete?')">Delete</a>
                                               
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{$expenses->links('pagination')}}
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
 <div class="modal fade text-left" id="addExpenses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
   <div class="modal-dialog" role="document">
     <div class="modal-content">
        <form action="{{route('admin.expensesList')}}" method="post">
            @csrf
           <div class="modal-header">
             <h4 class="modal-title" id="myModalLabel1">Add Expense</h4>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times; </span>
             </button>
           </div>
           <div class="modal-body">
                <div class="form-group">
                    <label>Date*</label>
                    <input type="date" name="date" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" class="form-control" required="">
                </div>
                <div class="form-group">
                    <label>Expense Type*</label>
                    <select class="form-control" name="type" required="">
                        <option value="">Select Type</option>
                        @foreach($types as $type)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                        <option value="0">Others</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment Method*</label>
                    <select class="form-control paymentMethod" name="method" required="">
                        <option value="">Select Method</option>
                        @foreach($methods as $method)
                        <option value="{{$method->id}}">{{$method->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment Method*</label>
                    <select class="form-control paymentOption" name="methodoption" required="">
                       
                    </select>
                </div>
                <div class="form-group">
                    <label>Amount*</label>
                    <input type="number" name="amount" class="form-control" required="">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" placeholder="Write Description"></textarea>
                </div>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
             <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Expense</button>
           </div>
       </form>
     </div>
   </div>
 </div>


@endsection @push('js') @endpush
