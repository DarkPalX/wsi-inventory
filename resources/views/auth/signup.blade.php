@extends('theme.main')
@section('content')

    <div class="content-wrap py-0">
        <div class="section bg-transparent min-vh-100 p-0 m-0">
            <div class="vertical-middle">
                <div class="container-fluid mx-auto">
    
                    <div id="login-section" class="card mx-auto rounded-0 border-0 rounded rounded-5" style="max-width: 500px; background-color: rgba(255,255,255,0.77);">
                        
                        <div class="card-body" style="padding: 40px;">
                        
                            <div class="text-center">
                                <a href="#"><img src="{{ Setting::get_company_logo_storage_path() }}" alt="Site Logo" style="max-width: 400px; margin: 20px 0;"></a>
                            </div>

                            <h5 style="color:#fe6400;">Create guest account</h5>

                            <form method="post" action="{{ route('submit-registration') }}">
                                @csrf
                                <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
                                    <label for="firstname"><i class="tx-danger">*</i> First Name</label>
                                    <input required type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter First Name" value="{{ old('firstname') }}">
                                    <small class="text-danger" style="font-size: 12px;">{{ $errors->first('firstname') }}</small>
                                </div>
    
                                <div class="form-group {{ $errors->has('middlename') ? 'has-error' : '' }}">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" id="middlename" name="middlename" class="form-control" placeholder="Enter Middle Name" value="{{ old('middlename') }}">
                                    <small class="text-danger" style="font-size: 12px;">{{ $errors->first('middlename') }}</small>
                                </div>
                                
                                <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
                                    <label for="lastname"><i class="tx-danger">*</i> Last Name</label>
                                    <input required type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter Last Name" value="{{ old('lastname') }}">
                                    <small class="text-danger" style="font-size: 12px;">{{ $errors->first('lastname') }}</small>
                                </div>
    
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
    
                                <div class="form-group {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                                    <label for="confirm_password"><i class="tx-danger">*</i> Confirm Password</label>
                                    <input required type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="********" >
                                    <small class="text-danger" style="font-size: 12px;">{{ $errors->first('confirm_password') }}</small>
                                </div>

                                {{-- hidden input --}}

                                <input type="hidden" name="role_id" value="4">

                               
                                
                                <button type="submit" class="btn btn-md text-white" style="background-color:#fe6400;">Submit</button>
                                <a href="/login" class="btn btn-outline-secondary btn-md">Cancel</a>
                                
							</form>

                            <div class="text-center"><small class="text-dark">Developed by &copy; Webfocus Solutions Inc.</small></div>

                        </div>
                    </div>
    

                </div>
            </div>
        </div>
    </div>
@endsection