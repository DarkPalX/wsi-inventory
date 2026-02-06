@extends('theme.main')

@section('pagecss')
    <style>
        #cover-banner {
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('{{ asset('theme/images/dfa-bg-2.jpg') }}') center center no-repeat;
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
            background-color: rgba(0, 0, 255, 0.7); /* Blue color with 50% opacity */
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
            background-color: #333;
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

        #login-section {
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
            background: url('{{ asset('theme/images/dfa-bg-2.jpg') }}') center center no-repeat;
            background-size: cover;
            filter: blur(4px);
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
    <div id="cover-banner">

        <img src="{{ Setting::get_company_logo_storage_path() }}" alt="Site Logo" style="max-width: 170px; margin: 20px 0;">
        
        <h1 class="text-uppercase text-light" style="font-size: 70px;">Foreign Service Institute</h1>

        <h2 class="text-white">BOOK INVENTORY SYSTEM</h2>
        <div class="button-container">
            <button class="bg-youtube border border-youtube" onclick="showLoginForm()">Login</button>
            <button class="bg-transparent border border-light" onclick="window.location.href='signup.html'">Sign Up</button>
        </div>
    </div>

    <div class="content-wrap py-0">
        <div class="section p-0 m-0 h-100 position-absolute" id="blurred-bg"></div>
        <div class="section bg-transparent min-vh-100 p-0 m-0">
            <div class="vertical-middle">
                <div class="container-fluid py-5 mx-auto">
                    <div class="text-center">
                        <a href="index.html"><img src="{{ Setting::get_company_logo_storage_path() }}" alt="Site Logo" style="max-width: 170px; margin: 20px 0;"></a>
                    </div>
    
                    <div id="login-section" class="card mx-auto rounded-0 border-0 rounded rounded-5" style="max-width: 400px; background-color: rgba(255,255,255,0.93);">
                        <div class="card-body" style="padding: 40px;">

                            <form method="POST" action="{{ route('login') }}">
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
    
                                @if($message = Session::get('unauthorize-login'))
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <i data-feather="alert-circle" class="mg-r-10"></i> 
                                        <span>Unauthorized login. Please contact your system administrator.</span>
                                    </div>
                                @endif
    
                                @csrf
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label for="email"><i class="tx-danger">*</i> Email</label>
                                    <input required type="email" id="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}">
                                    <small class="text-danger" style="font-size: 12px;">{{ $errors->first('email') }}</small>
                                </div>
    
                                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <label for="password"><i class="tx-danger">*</i> Password</label>
                                    <input required type="password" id="password" name="password" class="form-control" placeholder="********" >
                                    <small class="text-danger" style="font-size: 12px;">{{ $errors->first('password') }}</small>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Log In</button>
                                <a href="{{route('password.request')}}" class="btn btn-info btn-sm">Forgot Password</a>
                            </form>

                            {{-- <div class="line line-sm"></div>
    
                            <div class="w-100 text-center">
                                <h4 class="mb-3">or Login with:</h4>
                                <a href="#" class="button button-rounded bg-facebook">Facebook</a>
                                <span class="d-none d-md-inline-block">or</span>
                                <a href="#" class="button button-rounded bg-google">Google</a>
                            </div> --}}

                        </div>
                    </div>
    
                    <div class="text-center dark mt-3"><small class="bg-dark p-1 rounded rounded-3">Developed by &copy; Webfocus Solutions Inc.</small></div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
@endsection