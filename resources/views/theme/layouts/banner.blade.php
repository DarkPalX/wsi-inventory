@if(request()->url() == env('APP_URL'))

    <section class="page-title dark page-title-parallax scroll-detect parallax include-header py-6">
        <!-- <img data-src="images/dfa-bg-2.jpg" class="parallax-bg lazy dark"> -->
        <img data-src="{{ asset('theme/images/dfa-bg-title.png') }}" class="parallax-bg lazy dark" style="width: 100%; height: auto; filter: blur(8px); position: absolute; top: 0; left: 0; z-index: -1;">
        <div class="container">
            <div class="page-title-row pb-5 align-items-lg-end">

                <div class="page-title-content">
                    <h1 class="text-uppercase">Welcome, {{ Auth::user()->name }}</h1>
                    <span>What would you like to do?</span>

                    <div class="page-title-buttons mt-5">
                        <button class="button button-border button-light rounded px-5 mb-0" data-scrollto="#maintenance-div" data-offset="60" data-delay="2s">Maintenance <i class="uil uil-wrench ms-1"></i></button>
                        <a href="#" class="button button-border button-light rounded px-5 mb-0" data-scrollto="#explore-more-elements">Manage Account <i class="uil uil-user ms-1"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </section>

@else

    <section class="page-title dark page-title-parallax scroll-detect parallax include-header py-6">
        <!-- <img data-src="images/dfa-bg-2.jpg" class="parallax-bg lazy dark"> -->
        <img data-src="{{ asset('theme/images/dfa-bg-title.png') }}" class="parallax-bg lazy dark" style="width: 100%; height: auto; filter: blur(8px); position: absolute; top: 0; left: 0; z-index: -1;">
        <div class="container">
            <div class="page-title-row pb-5 align-items-lg-end">

                <div class="page-title-content">
                    <h1 class="text-uppercase">{{ $page->name }}</h1>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item">{{ $page->name }}</li>
                            <li class="breadcrumb-item">Manage</li>
                        </ol>
                    </nav>
                    
                </div>

            </div>
        </div>
    </section>

@endif

{{-- @if(isset($page) && $page->album && count($page->album->banners) > 0 && $page->album->is_main_banner())
    @include('theme.layouts.home-slider')
@elseif(isset($page) && $page->album && count($page->album->banners) > 1 && !$page->album->is_main_banner())
    @include('theme.layouts.page-slider')
@elseif(isset($page) && (isset($page->album->banners) && (count($page->album->banners) == 1 && !$page->album->is_main_banner()) || !empty($page->image_url)))
    @include('theme.layouts.page-banner')
@else
    @include('theme.layouts.no-banner')
@endif --}}
