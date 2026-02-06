<section id="slider" class="slick-wrapper clearfix">
    <div class="banner-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12" style="padding:0;">
                    <div class="sub-banner-caption">
                        <div class="container" style="position: relative;">
                            <h2 class="text-center excerpt-1 text-light">{{$page->name}}</h2>
                            <div class="sub-banner-flex">
                                <ol class="breadcrumb nobottommargin flex-nowrap justify-content-center">
                                    <li class="breadcrumb-item text-nowrap"><a href="index.htm" class="text-light"><i class="icon-home"></i></a></li>
                                    <li class="breadcrumb-item active excerpt-1 text-light" aria-current="page">{{$page->name}}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div id="banner" class="slick-slider">
                        <div class="hero-slide dark">
                            <img src="{{asset('theme/no-banner.jpg')}}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>