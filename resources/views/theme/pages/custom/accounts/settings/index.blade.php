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
                        <li class="breadcrumb-item">{{ $page->name }}</li>
                        <li class="breadcrumb-item">Manage</li>
                    </ol>
                </nav>
            </div>
            
        </div>

        <form method="post" action="{{ route('accounts.settings.update-settings') }}">
            @csrf

            <div class="row mt-4 mb-3">
                <div class="col-md-12 d-flex align-items-center justify-content-end">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-5 offset-1">

                    <h6>Company Details</h6>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Company Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_name" value="{{ $setting->company_name ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Company Address</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="company_address" value="{{ $setting->company_address ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Mobile #</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="mobile_no" value="{{ $setting->mobile_no ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Tel #</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tel_no" value="{{ $setting->tel_no ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">TIN #</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tin_no" value="{{ $setting->tin_no ?? '' }}">
                        </div>
                    </div>

                    <h6>Purchase Order Settings</h6>

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Requested By</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="purchase_order_requested_by" value="{{ $setting->purchase_order_requested_by ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Verifier 1</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="purchase_order_verifier1" value="{{ $setting->purchase_order_verifier1 ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Prepared By</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="purchase_order_prepared_by" value="{{ $setting->purchase_order_prepared_by ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Checker</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="purchase_order_checker" value="{{ $setting->purchase_order_checker ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Verifier 2</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="purchase_order_verifier2" value="{{ $setting->purchase_order_verifier2 ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Approved By</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="purchase_order_approved_by" value="{{ $setting->purchase_order_approved_by ?? '' }}">
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>


@endsection

@section('pagejs')

@endsection