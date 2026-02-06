Dear {{ $recipient->name }},

We have received a request to reset your password for your account in {{ $setting->company_name }} ({{ url('/') }}).

To reset your password, please click on the following link:
{{ URL::to('/create-password') }}?token=n8uien28j9mh2673ge23e238ye7283ed2iojdfdfigbfdgsdfm83&email={{ urlencode($recipient->email) }}

If you did not request a password reset, please ignore this email or communicate with us if you have questions.

For any inquiry or comments, please contact us at {{ $setting->email }}. Thank you.

Regards,
{{ $setting->company_name }}

---
{{ $setting->company_name }}
{{ $setting->company_address }}
{{ $setting->tel_no }} | {{ $setting->mobile_no }}
{{ url('/') }}
