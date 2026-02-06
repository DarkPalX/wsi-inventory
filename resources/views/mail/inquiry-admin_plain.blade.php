Dear {{ $adminInfo->firstname }},

{{ $clientInfo['firstname']. ' ' .$clientInfo['lastname']}} has submitted an inquiry requiring your prompt attention.
Please see details of the inquiry below.

Subject: {{ $clientInfo['subject'] }}
Name: {{ $clientInfo['firstname']. ' ' .$clientInfo['lastname']}}
Email: {{ $clientInfo['email'] }}
Contact Number: {{ $clientInfo['contact'] }}
Message: {{ $clientInfo['message'] }}


Regards,
{{ $setting->company_name }}



{{ $setting->company_name }}
{{ $setting->company_address }}
{{ $setting->tel_no }} | {{ $setting->mobile_no }}

{{ url('/') }}
