<div class="destopSlider">
  
  <div  class="singlefeturedImage">
    <div class="full">
    <img class="block__pic" src="{{ asset($product->fi()) }}" alt="{{$product->title}}">
    </div>
  </div>

  <div class="singleproductgallery">
    <a  class="selected" data-full="{{ asset($product->fi()) }}"><img src="{{ asset($product->fi()) }}" alt="{{$product->title}}" /></a>
    <!-- @foreach($product->images as  $img)
    <a  data-full="{{ asset($img->img_name) }}"><img src="{{ asset($img->img_name) }}" alt="{{$product->title}}" /></a>
    @endforeach -->
   
  </div>
</div>

