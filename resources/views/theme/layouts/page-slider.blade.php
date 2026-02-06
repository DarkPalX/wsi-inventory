<section id="slider" class="slick-wrapper clearfix">{{-- .include-header --}}
    <div class="banner-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12" style="padding:0;">
                    <div class="sub-banner-caption">
                        <div class="container" style="position: relative;">
                            <h2 class="text-center excerpt-1 text-light">{{$page->name}}</h2>
                            <div class="sub-banner-flex">
                                {{-- <ol class="breadcrumb nobottommargin flex-nowrap justify-content-center">
                                    <li class="breadcrumb-item text-nowrap"><a href="{{ route('home') }}" class="text-light"><i class="icon-home"></i></a></li>
                                    <li class="breadcrumb-item active excerpt-1 text-light" aria-current="page">{{$page->name}}</li>
                                </ol> --}}
                                <div class="custom-breadcrumb nobottommargin flex-nowrap justify-content-center">
                                    <span class="breadcrumb-item"><a href="{{ route('home') }}" class="text-light"><i class="icon-home"></i></a></span>
                                    <span class="breadcrumb-separator">/</span>
                                    <span class="breadcrumb-item active excerpt-1 text-light" aria-current="page">{{$page->name}}</span>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div id="banner" class="slick-slider">
                        @foreach ($page->album->banners as $banner)
                        <div class="hero-slide dark">
                            <img src="{{ $banner->image_path }}" />
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
