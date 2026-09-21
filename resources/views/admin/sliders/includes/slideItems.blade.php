<form action="{{route('admin.slideDrug',$slider->id)}}" method="post">
    @csrf
    @isset(json_decode(Auth::user()->permission->permission, true)['sliders']['add'])
    <button type="submit" class="btn btn-success">Serialist Update</button>
    @endisset
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" >
            <thead>
                <tr>
                    <th>Image</th>
                    <th width="50%">Info</th>
                    <th width="25%">Action</th>
                </tr>
            </thead>
            <tbody id="sortable">
            @foreach($slides as $i=>$slide)
                <tr>
                    <td style="cursor: move;">
                    <input type="hidden" name="slideid[]" value="{{$slide->id}}">
                   <img src="{{asset($slide->image())}}" style="max-width: 100px;">
                    </td>
                    <td style="cursor: move;">
                    <span><b>Name: </b>{{$slide->name}}</span>
                    <br>
                    @if($slide->status=='active')
                    <span><i class="fa fa-check" style="color: #1ab394;"></i></span>
                    @else
                    <span><i class="fa fa-time" style="color: #1ab394;"></i></span>
                    @endif
                    @if($slide->icon)
                    <span>Color: <span style="    width: 150px;height: 10px;display: inline-block;background:{{$slide->icon}}"></span></span>
                    @endif
                    </td>
                    <td class="center">

                    <a href="{{route('admin.slideEdit',$slide->id)}}" class="btn btn-sm btn-info">Edit</a>


                    @isset(json_decode(Auth::user()->permission->permission, true)['sliders']['delete'])
                    <a href="#deleteModal{{$slide->id}}" class="btn btn-sm btn-danger" data-toggle="modal">Delete</a>
                    <div class="modal fade" id="deleteModal{{$slide->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                              <a href="{{route('admin.slideDelete',$slide->id)}}" class="btn btn-info">Yes Delete</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      @endisset
                    </td>
                </tr>
        @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Image</th>
                    <th width="50%">Info</th>
                    <th width="25%">Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
</form>

    <script type="text/javascript">
      $( function() {
              $( "#sortable" ).sortable();
              $( "#sortable" ).disableSelection();
            } );
    </script>