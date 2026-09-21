@extends('admin.layouts.app') @section('title')
<title>Slider Edit - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')

<style type="text/css"></style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Slider Edit</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Slider Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.sliders')}}">BACK</a>
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
            <div class="col-md-4">
                <form action="{{route('admin.slidersUpdate',$slider->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Slider Edit</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Slider Name(*) </label>
                                    <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Slider Name" value="{{$slider->name?:old('name')}}" required="" />
                                    @if ($errors->has('name'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="description">Description </label>
                                    <textarea name="description" class="form-control {{$errors->has('description')?'error':''}}" placeholder="Enter Description">{!!$slider->description!!}</textarea>
                                    @if ($errors->has('description'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('description') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="image">Slider Image</label>
                                    <input type="file" name="image" class="form-control {{$errors->has('image')?'error':''}}" />
                                    @if ($errors->has('image'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('image') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <img src="{{asset($slider->image())}}" style="max-width: 100px;" />
                                    
                                    @isset(json_decode(Auth::user()->permission->permission, true)['sliders']['add'])
                                    @if($slider->imageFile)
                                    <a href="{{route('admin.mediesDelete',$slider->imageFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                                    @endif
                                    @endisset
                                </div>
                                <div class="form-group">
                                    <label for="banner">Slider Banner</label>
                                    <input type="file" name="banner" class="form-control {{$errors->has('banner')?'error':''}}" />
                                    @if ($errors->has('banner'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('banner') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <img src="{{asset($slider->banner())}}" style="max-width: 200px;" />
                                    @isset(json_decode(Auth::user()->permission->permission, true)['sliders']['add'])
                                        @if($slider->bannerFile)
                                        <a href="{{route('admin.mediesDelete',$slider->bannerFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>
                                        @endif
                                    @endisset
                                </div>

                                <div class="form-group">
                                    <label for="fetured">Slider Location</label>
                                    <select class="form-control" name="location">
                                        <option value="">Select Location</option>
                                        <option value="Front Page Slider" {{$slider->location=='Front Page Slider'?'selected':''}}>Front Page Slider</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="status">Slider Status</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="status" name="status" {{$slider->status=='active'?'checked':''}}/>
                                            <label class="custom-control-label" for="status">Active</label>
                                        </div>
                                    </div>
                                </div>
                                @isset(json_decode(Auth::user()->permission->permission, true)['sliders']['add'])
                                <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save changes</button>
                                @endisset
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">Slide Items</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @isset(json_decode(Auth::user()->permission->permission, true)['sliders']['add'])
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{route('admin.slideCreate',$slider->id)}}" class="btn btn-primary">Add Slide</a>
                                </div>
                                <div class="col-md-6">
                                    
                                </div>
                            </div>
                            <hr />
                            @endisset
                            <div class="sliderImagesList"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js')
<script type="text/javascript">
    $(document).ready(function(){
        var url ='{{route('admin.slideAjax',$slider->id)}}';
        $.ajax({
          url:url,
          dataType: 'json',
           success : function(data){
             $('.sliderImagesList').empty().append(data.view);

           },error: function () {
              alert('error');
            }
        });

    });
</script>

@endpush
