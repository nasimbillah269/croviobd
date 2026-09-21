@extends('admin.layouts.app') @section('title')
<title>Expenses Type - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Expenses Type</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Expenses Type</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
        
            <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#addType">
                Add Type
            </button>
            
            <a class="btn btn-outline-primary" href="{{route('admin.expensesTypes')}}">
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
                                        <form action="{{route('admin.expensesTypes')}}">
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
                        <h4 class="card-title">Expenses Type</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">SL</th>
                                            <th style="min-width: 200px;width: 200px;">Brand Name</th>
                                            <th style="min-width: 300px;">Description</th>
                                            <th style="min-width: 160px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($types as $i=>$type)
                                        <tr>
                                            <td>
                                            {{$types->currentpage()==1?$i+1:$i+($types->perpage()*($types->currentpage() - 1))+1}}
                                            </td>
                                            <td>
                                                <span>{{$type->name}}</span><br />
                                            </td>
                                            <td style="padding: 5px; text-align: center;">
                                                {!!$type->description!!}
                                            </td>
                                            <td class="center">

                                                <button class="btn btn-sm btn-info" type="button" data-toggle="modal" data-target="#updateType{{$type->id}}">
                                                   Edit
                                                </button>

                                                <!-- Modal -->
                                                 <div class="modal fade text-left" id="updateType{{$type->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                                                   <div class="modal-dialog" role="document">
                                                     <div class="modal-content">
                                                        <form action="{{route('admin.expensesTypesManage',['id'=>$type->id,'type'=>'update'])}}" method="post">
                                                            @csrf
                                                           <div class="modal-header">
                                                             <h4 class="modal-title" id="myModalLabel1">Update Type</h4>
                                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                               <span aria-hidden="true">&times; </span>
                                                             </button>
                                                           </div>
                                                           <div class="modal-body">
                                                                <div class="form-group">
                                                                        <label>Type Name*</label>
                                                                        <input type="text" name="name" value="{{$type->name}}" class="form-control" placeholder="Enter Type Name" required="">
                                                                </div>
                                                                <div class="form-group">
                                                                        <label>Type Description</label>
                                                                        <textarea class="form-control" name="description" placeholder="Write Description">{!!$type->description!!}</textarea>
                                                                </div>
                                                           </div>
                                                           <div class="modal-footer">
                                                             <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
                                                             <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Update Type</button>
                                                           </div>
                                                       </form>
                                                     </div>
                                                   </div>
                                                 </div>

                                                <a href="{{route('admin.expensesTypesManage',['id'=>$type->id,'type'=>'delete'])}}" class="btn btn-sm btn-danger" onclick="return confirm('Are You Want To Delete?')">Delete</a>
                                               
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{$types->links('pagination')}}
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
 <div class="modal fade text-left" id="addType" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
   <div class="modal-dialog" role="document">
     <div class="modal-content">
        <form action="{{route('admin.expensesTypes')}}" method="post">
            @csrf
           <div class="modal-header">
             <h4 class="modal-title" id="myModalLabel1">Add Type</h4>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times; </span>
             </button>
           </div>
           <div class="modal-body">
                <div class="form-group">
                        <label>Type Name*</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Type Name" required="">
                </div>
                <div class="form-group">
                        <label>Type Description</label>
                        <textarea class="form-control" name="description" placeholder="Write Description"></textarea>
                </div>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
             <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Type</button>
           </div>
       </form>
     </div>
   </div>
 </div>


@endsection 

@push('js') 

@endpush
