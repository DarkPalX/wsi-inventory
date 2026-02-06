@php
    $page = new App\Models\Page();
    $page->name = 'Login';
@endphp

@extends('theme.main')

@section('pagecss')
    <style>
        #cover-banner {
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('{{ asset('images/company-bg.png') }}') center center no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            color: #fff;
            transition: transform 0.5s ease-in-out;
            z-index: 10;
        }

        #cover-banner::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background-color: rgba(253, 140, 3, 0.35); */
            background-color: rgba(77, 1, 77, 0.77); 

            z-index: -1; /* Ensure the overlay is behind the content */
        }

        #cover-banner h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #fff; /* Ensure the text color is white or another color */
            text-shadow: 
                2px 2px 5px #000,  
                -2px -2px 5px #000,  
                2px -2px 5px #000,  
                -2px  2px 5px #000;
        }


        #cover-banner img {
            max-width: 150px;
            margin: 20px 0;
        }

        #cover-banner p {
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        #cover-banner .button-container {
            display: flex;
            justify-content: center;
        }

        #cover-banner button {
            margin: 10px;
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            background-color: #ffffff;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        #cover-banner button:hover {
            background-color: #555;
        }

        #cover-banner.slide-up {
            transform: translateY(-100%);
        }

        .hidden-login {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            z-index: 1;
        }

        #login-section.show {
            opacity: 1;
        }

        #blurred-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset('images/company-bg.png') }}') center center no-repeat;
            background-size: cover;
            /* filter: blur(1px); */
            z-index: -1; /* Ensure the blurred background is behind the content */
        }

        /* Additional styles to ensure content is above the blurred background */
        .content-wrap {
            position: relative;
            z-index: 1;
        }

    </style>
@endsection

@section('content')

    <!-- Cover Banner -->
    <div id="cover-banner" @if($errors->first('email')) hidden @endif>

        <img src="{{ asset('images/company-logo.png') }}" alt="Site Logo" style="max-width: 490px; margin: 20px 0;">
        
        <h2 class="text-uppercase text-light" style="font-size: 35px;">Inventory System</h2>

        {{-- <h2 class="text-white">INVENTORY SYSTEM</h2> --}}
        <div class="button-container">
            <button class="btn" style="background-color:#fe6400;" onclick="showLoginForm()">Login</button>
            <a href="{{ route('signup') }}"><button class="btn bg-transparent border border-light">Create Account</button></a>
        </div>
    </div>

    <div class="content-wrap py-0">

        {{-- <div class="section p-0 m-0 h-100 position-absolute" id="blurred-bg"></div> --}}

        <div class="section bg-transparent min-vh-100 p-0 m-0">
            <div class="vertical-middle">
                <div class="container-fluid py-5 mx-auto">
    
                    <div id="login-section" class="card mx-auto rounded-0 border-0 rounded rounded-5 mt-5 {{ !$errors->first('email') ? 'hidden-login' : '' }}" style="max-width: 500px; background-color: rgba(255,255,255,0.75);">
                        
                        <div class="card-body" style="padding: 40px;">
                        
                            <div class="text-center">
                                <a href="#"><img src="{{ Setting::get_company_logo_storage_path() }}" alt="Site Logo" style="max-width: 400px; margin: 20px 0;"></a>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label for="email"><i class="tx-danger">*</i> Email</label>
                                    <input required type="text" id="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}">
                                    <small class="text-danger" style="font-size: 12px;">{{ $errors->first('email') }}</small>
                                </div>
    
                                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <label for="password"><i class="tx-danger">*</i> Password</label>
                                    <input required type="password" id="password" name="password" class="form-control" placeholder="********" >
                                    <small class="text-danger" style="font-size: 12px;">{{ $errors->first('password') }}</small>
                                </div>
                                <button type="submit" class="btn btn-md text-white" style="background-color:#fe6400;">Log In</button>
                                <a href="{{route('password.request')}}" class="btn btn-outline-dark btn-md">Forgot Password</a>
                            </form>

                            <div class="text-center dark mt-5"><small class="text-dark">No account yet?</small> <a href="{{ route('signup') }}">Sign up</a></div>
                            <div class="text-center"><small class="text-dark">Developed by &copy; Webfocus Solutions Inc.</small></div>

                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
@endsection