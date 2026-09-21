<div class="card-header" style="padding: 5px; border: 1px solid gray; margin: 2px 0;" data-toggle="collapse" href="#accordion4" aria-expanded="false" aria-controls="accordion4">
    <a class="card-title lead collapsed" href="#">Product Categories</a>
</div>
<div id="accordion4" style="border: 1px solid gray;" role="tabpanel" data-parent="#accordionWrapa1" class="collapse" aria-expanded="false">
    <div class="card-content">
        <div class="card-body">
            <form action="{{route('admin.menusItemsPost',$menu->id)}}" method="post">
                @csrf
                <input type="hidden" name="parent" value="{{$parent->id}}" />
                <div class="form-group">
                    <label for="name">Select Categories</label>
                    <select data-placeholder="Select Categories..." name="productCategories[]" class="select2 form-control" multiple="multiple" required="">
                        @foreach($productCategories as $i=>$productCtg)
                        <option value="{{$productCtg->id}}">{{$productCtg->name}}</option>
                        
                        @if($productCtg->subctgs->count() >0) @include('admin.menus.includes.productCategorySubList',['subcategories' => $productCtg->subctgs,'i'=>1]) @endif
                        
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