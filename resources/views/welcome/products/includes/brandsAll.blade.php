
<div class="Brandlist">
    <ul>
        @foreach($brands->shuffle() as $brand)
        <li>

             
            <a href="{{ route('productBrandView',[$brand,Str::slug($brand->title)]) }}">
            @if($brand->image)
            <img  src="{{ asset($brand->image->file_url) }}" alt="{{$brand->title}}">
            @else
            <span>No Image</span>
            @endif
            </a>
            
        @endforeach
    </ul>
</div>