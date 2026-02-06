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
                        <li class="breadcrumb-item"><a href="{{ route('accounts.roles.index') }}">{{ $page->name }}</a></li>
                        <li class="breadcrumb-item">Edit</li>
                    </ol>
                </nav>
                
            </div>
        </div>
        
        <div class="row mt-5">

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Role Information</div>

                        <div class="card-body">
                            
							<form method="post" action="{{ route('accounts.roles.update', $role->id) }}">
                                @csrf
                                @method('PUT')
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Name</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Desription</label>
									<div class="col-sm-10">
                                        <textarea class="form-control" id="description" name="description">{{ $role->description }}</textarea>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-10">
										<button type="submit" class="btn btn-primary">Save</button>
										<a href="{{ route('accounts.roles.index') }}" class="btn btn-light">Back</a>
									</div>
								</div>
							</form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        

    </div>

@endsection

@section('pagejs')
@endsection