@extends('theme.main')

@section('pagecss')
    <style>
        {{ str_replace(array("'", "&#039;"), "", $page->styles ) }}
    </style>

    {{-- custom readcrumb --}}
    <style>
        .custom-breadcrumb {
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .breadcrumb-item {
            margin-right: 5px;
        }

        .breadcrumb-separator {
            margin: 0 5px;
            color: #ccc;
        }

        .text-light {
            color: #fff; /* Customize the text color as needed */
        }

        .icon-home {
            /* Add styles for the home icon */
        }
    </style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row py-5">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    @if($message = Session::get('error'))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                        </div>
                    @endif
        
                    @if($message = Session::get('msg'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i data-feather="alert-circle" class="mg-r-10"></i> {{ $message }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($parentPage)
            <span onclick="closeNav()" class="dark-curtain"></span>
            <div class="col-lg-12 col-md-5 col-sm-12">
                <span onclick="openNav()" class="button button-small button-circle border-bottom ms-0 text-initial nols fw-normal noleftmargin d-lg-none mb-4"><span class="icon-chevron-left me-2 color-2"></span> Quicklinks</span>
            </div>
            <div class="col-lg-3 pe-lg-4">
                <div class="tablet-view">
                    <a href="javascript:void(0)" class="closebtn d-block d-lg-none" onclick="closeNav()">&times;</a>

                    <div class="card border-0 bg-transparent">
                        <h3>Quicklinks</h3>
                        <div class="side-menu">
                            <ul class="mb-0 pb-0">
                                <li @if($parentPage->id == $page->id) class="active" @endif>
                                    <a href="{{ $parentPage->get_url() }}"><div>{{ $parentPage->name }}</div></a>
                                </li>
                                @foreach($parentPage->sub_pages as $subPage)
                                    <li @if($subPage->id == $page->id) class="active" @endif>
                                        <a href="{{ $subPage->get_url() }}"><div>{{ $subPage->name }}</div></a>
                                        @if ($subPage->has_sub_pages())
                                            <ul>
                                                @foreach ($subPage->sub_pages as $subSubPage)
                                                <li @if ($subSubPage->id == $page->id) class="active" @endif>
                                                    <a href="{{ $subSubPage->get_url() }}"><div>{{ $subSubPage->name }}</div></a>
                                                    @if ($subSubPage->has_sub_pages())
                                                    <ul>
                                                        @foreach ($subSubPage->sub_pages as $subSubSubPage)
                                                            <li @if ($subSubSubPage->id == $page->id) class="active" @endif>
                                                                <a href="{{ $subSubSubPage->get_url() }}"><div>{{ $subSubSubPage->name }}</div></a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                @if (Str::contains(url()->current(), '/create-password'))
                    <form id="create_password_form" method="POST" action="{{ url('/create-password') }}">
                        @csrf
                        {!! $page->contents !!}
                    </form>
                @elseif (Str::contains(url()->current(), '/reset-password'))
                    <form id="reset_password_form" method="POST" action="{{ url('/reset-password') }}">
                        @csrf
                        {!! $page->contents !!}
                    </form>
                @else
                    {!! $page->contents !!}
                @endif
            </div>
        @else
            <div class="col-lg-12">
                @if (Str::contains(url()->current(), '/create-password'))
                    <form id="create_password_form" method="POST" action="{{ url('/create-password') }}">
                        @csrf
                        {!! $page->contents !!}
                    </form>
                @elseif (Str::contains(url()->current(), '/reset-password'))
                    <form id="reset_password_form" method="POST" action="{{ url('/reset-password') }}">
                        @csrf
                        {!! $page->contents !!}
                    </form>
                @else
                    {!! $page->contents !!}
                @endif
            </div>
        @endif

    </div>
</div>
@endsection

@section('pagejs')

<!-- ... Your existing HTML code ... -->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var url_string = window.location.href; 
        var url = new URL(url_string);
        var token = url.searchParams.get("token");
        var email = url.searchParams.get("email");
        
        document.getElementById("token").value = token;
        document.getElementById("email").value = email;
    });
</script>  

<script>
    document.getElementById('create_password_form').action = "{{ route('members.create-password') }}";
    document.getElementById('reset_password_form').action = "{{ route('members.reset-password') }}";
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the element by ID
        const homeButton = document.getElementById('home_button');
        
        // Set the href attribute
        homeButton.href = "{{ env('APP_URL') }}";
    });
</script>

<script>
    function logout() {
        // Perform any additional actions before logging out, if needed

        // Create a dynamic form
        var form = document.createElement("form");
        form.action = "{{ route('members.logout') }}";
        form.method = "post";

        // Add CSRF token input
        var csrfInput = document.createElement("input");
        csrfInput.type = "hidden";
        csrfInput.name = "_token";
        csrfInput.value = "{{ csrf_token() }}";
        form.appendChild(csrfInput);

        // Append the form to the body and submit
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection
