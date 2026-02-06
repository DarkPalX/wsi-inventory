@extends('theme.main')

@section('pagecss')
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">
        
            <div class="col-md-6">
                <strong class="text-uppercase">{{ $page->name }}</strong>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item">User</li>
                        <li class="breadcrumb-item"><a href="{{ route('accounts.users.index') }}">{{ $page->name }}</a></li>
                    </ol>
                </nav>
                
            </div>
        </div>
        
        <div class="row mt-5">

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Personal Profile Information</div>

                        <div class="card-body">

                                

                            
                            <form id="personal_form" method="post" action="{{ route('accounts.users.update-profile', $user->id) }}" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="d-flex">

                                    <div class="col-sm-12 col-lg-4 d-flex align-items-center justify-content-center flex-column">
                                        <div class="rounded-circle overflow-hidden border d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; position: relative;">
                                            <img src="{{ asset($user->avatar) }}" 
                                                 alt="Profile Image" 
                                                 class="img-fluid" 
                                                 style="width: 100%; height: 100%; object-fit: cover;" 
                                                 onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';">
                                            <i class="fa-solid fa-user text-secondary" style="font-size:120px; display: none; position: absolute;"></i>
                                        </div>
                                    
                                        <div class="mt-3 text-center">
                                            <small class="text-uppercase">{{ User::userRole($user->role_id) }}</small> | <strong>{{ $user->name }}</strong><br>
                                            <small>{{ $user->email }}</small>
                                        </div>
                                    
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-sm btn-transparent border" onclick="document.getElementById('avatar').click();"><i class="bi-image-alt mr-2"></i> Update Avatar</button>
                                            <input type="file" id="avatar" name="avatar" style="display: none;" accept=".png, .jpg" onchange="change_avatar()">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12 col-lg-8">
                                        <div class="form-group">
                                            <strong class="col-form-label">First Name</strong>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}" id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" required>
                                                @error('firstname')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
        
                                        <div class="form-group">
                                            <strong for="middlename" class="col-form-label">Middle Name</strong>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control {{ $errors->has('middlename') ? 'is-invalid' : '' }}" id="middlename" name="middlename" value="{{ old('middlename', $user->middlename) }}" required>
                                                @error('middlename')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
        
                                        <div class="form-group">
                                            <strong for="lastname" class="col-form-label">Last Name</strong>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}" id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" required>
                                                @error('lastname')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-sm-12 mt-5">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                <a href="{{ env('APP_URL') }}" class="btn btn-light">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mt-4">
                                </div>

                                {{-- hidden input --}}
                                <input type="text" value="{{ $user->role_id }}" name="role_id" hidden>

                            </form>
                            
                            <div class="fancy-title title-center title-border">
                                <i class="bi-key text-danger"></i>
                            </div>

                            <div class="col-lg-12">
                                <button class="btn btn-transparent border mb-4" type="button" data-bs-toggle="collapse" data-bs-target="#password_box" aria-expanded="false" aria-controls="password_box">
                                    <i class="fa-solid fa-info-circle mr-2"></i> Change Password
                                </button>

                                <div class="collapse" id="password_box">
                
                                    <form method="post" action="{{ route('accounts.users.update-password', $user->id) }}" enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">Current Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                                @error('current_password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" id="password" name="password" required>
                                                @error('password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
        
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-3 col-form-label">Confirm Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                                @error('confirm_password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-2">Confirm</button>

                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('pagejs')
    <script>
        function change_avatar() {
            var form = document.getElementById('personal_form');
            form.action = '{{ route("accounts.users.update-avatar") }}';
            form.submit();
        }
    </script>
@endsection