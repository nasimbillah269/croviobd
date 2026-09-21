<div class="card-header" style="padding: 5px; border: 1px solid gray; margin: 2px 0;" data-toggle="collapse" href="#accordion2" aria-expanded="false" aria-controls="accordion2">
    <a class="card-title lead collapsed" href="#">Pages</a>
</div>
<div id="accordion2" style="border: 1px solid gray;" role="tabpanel" data-parent="#accordionWrapa1" class="collapse" aria-expanded="false">
    <div class="card-content">
        <div class="card-body">
            <form action="{{route('admin.menusItemsPost',$menu->id)}}" method="post">
                @csrf
                <input type="hidden" name="parent" value="{{$parent->id}}" />
                <div class="form-group">
                    <label for="pages">Select Pages</label>
                    <select data-placeholder="Select Pages..." name="pages[]" class="select2 form-control" multiple="multiple" required="">
                        @foreach($pages as $i=>$page)
                        <option value="{{$page->id}}">{{$page->name}}

                            @if($page->id==1)
                            (Privacy Policy)
                            @elseif($page->id==9)
                            (Front Page)
                            @elseif($page->id==10)
                            (Latest Blog)
                            @elseif($page->id==11)
                            (Latest Service)
                            @elseif($page->id==12)
                            (About Us)
                            @elseif($page->id==13)
                            (Contact Us)
                            @elseif($page->id==14)
                            (Galleries)
                            @elseif($page->id==15)
                            (All Brands)
                            @elseif($page->id==16)
                            (All Clients)
                            @endif

                        </option>
                        @endforeach
                    </select>
                    @if ($errors->has('pages*'))
                    <p style="color: red; margin: 0; font-size: 10px;">The Page Must Be a Number</p>
                    @endif
                </div>
                @isset(json_decode(Auth::user()->permission->permission, true)['menus']['add'])
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add</button>
                @endisset
            </form>
        </div>
    </div>
</div>
