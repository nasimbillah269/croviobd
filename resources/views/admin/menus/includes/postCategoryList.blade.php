<div class="card-header" style="padding: 5px; border: 1px solid gray; margin: 2px 0;" data-toggle="collapse" href="#accordion3" aria-expanded="false" aria-controls="accordion3">
    <a class="card-title lead collapsed" href="#">Post Categories</a>
</div>
<div id="accordion3" style="border: 1px solid gray;" role="tabpanel" data-parent="#accordionWrapa1" class="collapse" aria-expanded="false">
    <div class="card-content">
        <div class="card-body">
            <form action="{{route('admin.menusItemsPost',$menu->id)}}" method="post">
                @csrf
                <input type="hidden" name="parent" value="{{$parent->id}}" />
                <div class="form-group">
                    <label for="name">Select Categories</label>
                    <select data-placeholder="Select Categories..." name="blogCategories[]" class="select2 form-control" multiple="multiple" required="">
                        @foreach($blogCategories as $i=>$bctg)
                        <option value="{{$bctg->id}}">{{$bctg->name}}</option>
                        @endforeach
                    </select>
                </div>
                @isset(json_decode(Auth::user()->permission->permission, true)['menus']['add'])
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add</button>
                @endisset
            </form>
        </div>
    </div>
</div>