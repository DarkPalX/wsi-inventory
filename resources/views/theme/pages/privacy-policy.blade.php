@extends('theme.main')

@section('content')
<div class="content-wrap">
    <div class="container">
        <div class="row">
            <div class="col-12">
                {!! Setting::info()->data_privacy_content !!}
            </div>
        </div>
    </div>
</div>
@endsection

