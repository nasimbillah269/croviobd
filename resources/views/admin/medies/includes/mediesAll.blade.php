@foreach($medies as $media)
<li>
    <div class="mediaImagediv">
        <img src="{{asset($media->image())}}" />
    </div>
    @isset(json_decode(Auth::user()->permission->permission, true)['medies']['delete'])
    <div style="top: 0; position: absolute;">
        <input type="checkbox" name="mediaid[]" value="{{$media->id}}" />
    </div>
    @endisset
    <div style="top: 0; right: 0; position: absolute; background: #ffffff; padding: 2px 5px;">
        <a href="{{route('admin.mediesEdit',$media->id)}}" target="_blank"> <i class="fa fa-edit"></i></a>
    </div>
</li>
@endforeach
