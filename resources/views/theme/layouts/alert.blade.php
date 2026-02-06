<!-- #alert start -->
<style>
    .floating-alert {
        position: fixed;
        bottom: 35px; /* Adjust distance from the bottom */
        right: 35px; /* Adjust distance from the right */
        z-index: 1000; /* Ensure it overlays other content */
        width: auto; /* Adjust as needed */
        max-width: 700px; /* Adjust as needed */
    }
</style>

@if(Session::get('alert'))

    @php
        $alert = explode(':', Session::get('alert'));
        $type = $alert[0];
        $message = $alert[1];
    @endphp


    <div class="floating-alert alert alert-dismissible alert-{{ $type }}" id="alert-popup">
        {{ $message }}.
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-hidden="true"></button>
    </div>
    <script src="{{ asset('theme/js/jquery-3.6.0.min.js') }}"></script>
    <script>
        setTimeout(function(){
            $('#alert-popup').fadeOut('slow');
        }, 3500);
    </script>

@endif
<!-- #alert end -->