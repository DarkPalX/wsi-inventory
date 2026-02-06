@php
    $is_video = 0;
    if($page->album->banner_type == 'video'){
        $is_video = 1;
    }
@endphp
<section id="slider" class="slick-wrapper clearfix">{{-- .include-header --}}
    <div class="banner-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12" style="padding:0;">
                    <div id="banner" class="home-slider slick-slider">
                        
                        @foreach ($page->album->banners as $banner)
                            @if($is_video > 0)
                                <div class="hero-slide dark">
                                    <video autoplay="" muted="" loop="" id="myVideo" style="object-fit:none">
                                        <source src="{{ url($banner->image_path) }}" type="video/mp4">
                                        {{-- <source src="{{ url('storage/' . str_after($banner->image_path, 'storage/')) }}" type="video/mp4"> --}}
                                    </video>
                                    <div class="banner-caption">
                                        <div class="container">
                                            <div class="row align-items-center">
                                                <div class="col-lg-12">
                                                    <h2 class="text-center" data-animate="fadeInUp">{{ $banner->title }}</h2>
                                                    <p class="d-none d-sm-block text-center mx-wd-750-f mx-auto" data-animate="fadeInUp" data-delay="200">{{ $banner->description }}</p>

                                                    @if($banner->url && $banner->button_text) 
                                                        <div class="d-flex justify-content-center mt-5" data-animate="fadeInDown" data-delay="400">
                                                            <a href="{{ $banner->url }}" class="button button-large button-border">{{ $banner->button_text }}</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="hero-slide dark">
                                    <img src="{{ url($banner->image_path) }}" alt="{{ $banner->title }}">
                                    {{-- <img src="{{ url('storage/' . str_after($banner->image_path, 'storage/')) }}" alt="{{ $banner->title }}"> --}}
                                    <div class="banner-caption">
                                        <div class="container">
                                            <div class="row align-items-center">
                                                <div class="col-lg-12">
                                                    <h2 class="slide-content" data-animate="fadeInUp">{{ $banner->title }}</h2>
                                                    <p class="d-none d-sm-block mx-wd-750-f slide-content2" data-animate="fadeInUp" data-delay="200">{{ $banner->description }}</p>

                                                    @if($banner->url && $banner->button_text)
                                                        <div class="d-flex mt-5" data-animate="fadeInDown" data-delay="400">
                                                            <a href="{{ $banner->url }}" class="button button-border">{{ $banner->button_text }}</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>