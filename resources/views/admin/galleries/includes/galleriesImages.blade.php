<form action="{{route('admin.galleriesImagesUpdate',$gallery->id)}}" method="post">
    @csrf
    
    <p style="margin: 0;color: #ff5722;">Note:Select Delete Must Be Image Select</p>
    <div class="row">
        <div class="col-md-6">
            <select class="form-control rounded-0" name="action" required="">
                <option value="">Select Action</option>
                @isset(json_decode(Auth::user()->permission->permission, true)['galleries']['add'])
                <option value="1">Update</option>
                @endisset
                @isset(json_decode(Auth::user()->permission->permission, true)['galleries']['delete'])
                <option value="2">Delete</option>
                @endisset
            </select>
        </div>
        <div class="col-md-6">
            <button type="submit" class="float-md-right btn-block btn btn-success rounded-0" onclick="return confirm('Are You Want To Action?')">Action</button>
        </div>
    </div>
   
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" >
            <thead>
                <tr>
                    <th width="30%">
                        <label style="cursor: pointer;margin-bottom: 0;">
                        @isset(json_decode(Auth::user()->permission->permission, true)['galleries']['delete'])
                        <input class="checkbox" type="checkbox" class="form-control" id="checkall">  All 
                        @endisset
                        <span class="checkCounter"></span>
                      </label>
                    Image</th>
                    <th width="70%">Content</th>
                </tr>
            </thead>
            <tbody id="sortable">
            @foreach($gallery->galleryImages as $i=>$image)
                <tr>
                    <td style="cursor: move;">
                    @isset(json_decode(Auth::user()->permission->permission, true)['galleries']['delete'])
                    <input type="checkbox" name="checkid[]" value="{{$image->id}}">
                    @endisset
                    @isset(json_decode(Auth::user()->permission->permission, true)['galleries']['add'])
                    <input type="hidden" name="imageid[]" value="{{$image->id}}">
                    @endisset
                    <img src="{{asset($image->file_url)}}" style="max-width: 100px;">
                    </td>
                    <td style="cursor: move;">
                    
                    @if(isset(json_decode(Auth::user()->permission->permission, true)['galleries']['add']))
                    <input type="text" class="form-control"  name="name[]" placeholder="Name Image" value="{{$image->alt_text}}">
                    <textarea class="form-control"  name="description[]">{{$image->description}}</textarea>
                    @else
                    <input type="text" class="form-control" disabled="" name="name[]" placeholder="Name Image" value="{{$image->alt_text}}">
                    <textarea class="form-control" disabled="" name="description[]">{{$image->description}}</textarea>
                    @endif
                    </td>
                </tr>
        @endforeach
            </tbody>
        </table>
    </div>
</form>

    <script type="text/javascript">
      $( function() {
              $( "#sortable" ).sortable();
              $( "#sortable" ).disableSelection();
            } );
    </script>