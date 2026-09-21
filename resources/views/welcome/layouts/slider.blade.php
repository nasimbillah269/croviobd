@if(slider('Front Page Slider'))
<div class="sliderMain">
    <div class="row">
        <div class="slider-section">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach(slider('Front Page Slider')->subSliders as $i => $slider)
                        <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" class="{{$i==0?'active':''}}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach(slider('Front Page Slider')->subSliders as $i => $slider)
                    <div class="carousel-item {{$i==0?'active':''}}">
                        <!-- Image Wrapper for Skeleton -->
                        <div class="slider-image-wrapper">
                            <!-- Skeleton Loader -->
                            <div class="skeleton skeleton-slider"></div>

                            <!-- Lazy Loading Image -->
                            <img class="d-block w-100 lazy lazy-slider" 
                                 data-src="{{ asset($slider->image()) }}" 
                                 alt="SM Power Image">
                        </div>
                    </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endif
