@extends('admin.layouts.app') @section('title')
<title>Menus List - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')
<style type="text/css"></style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Menus List</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Menus List</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
        		@isset(json_decode(Auth::user()->permission->permission, true)['menus']['add'])
            <a class="btn btn-outline-primary" href="{{route('admin.menusCreate')}}">Add Menu</a>
           	@endisset
            <a class="btn btn-outline-primary reloadPage1" href="{{route('admin.menus')}}">
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
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">Menus List</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 60px;">S:L</th>
                                            <th style="min-width: 300px;">Menu Name</th>
                                            <th style="max-width: 100px;">Location</th>
                                            <th style="max-width: 100px;">Items</th>
                                            <th style="min-width: 160px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($menus as $i=>$menu)
                                        <tr>
                                            <td>
                                                {{$i+1}}
                                            </td>
                                            <td>
                                                <span>{{$menu->name}}</span><br />
                                                @if($menu->status=='active')
                                                <span><i class="fa fa-check" style="color: #1ab394;"></i></span>
                                                @else
                                                <span><i class="fa fa-times" style="color: #ed5565;"></i></span>
                                                @endif @if($menu->fetured==true)
                                                <span><i class="fa fa-star" style="color: #1ab394;"></i></span>
                                                @endif
                                                <span style="font-size: 10px;">
                                                    <i class="fa fa-user" style="color: #1ab394;"></i>
                                                    {{$menu->user?$menu->user->name:'No Author'}}
                                                </span>
                                            </td>
                                            <td style="padding: 5px; text-align: center;">
                                                {{ucfirst($menu->location)}}
                                            </td>
                                            <td style="padding: 5px; text-align: center;">
                                                {{$menu->MenuItems->count()}}
                                            </td>
                                            <td class="center">
                                                <a href="{{route('admin.menusEdit',$menu->id)}}" class="btn btn-sm btn-info">Config</a>

                                                @isset(json_decode(Auth::user()->permission->permission, true)['menus']['delete'])
                                                <a href="#deleteModal{{$menu->id}}" class="btn btn-sm btn-danger" data-toggle="modal">Delete</a>
                                                <div class="modal fade" id="deleteModal{{$menu->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Confermation</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are Your Want To Delete
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a href="{{route('admin.menusDelete',$menu->id)}}" class="btn btn-primary">Yes</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endisset
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{$menus->links('pagination')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js') @endpush
