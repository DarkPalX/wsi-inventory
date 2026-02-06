<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

</head>
<title>Untitled Document</title>

<body>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f0f0;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p {
        margin: 10px 0;
        padding: 0;
        font-weight: normal;
    }

    p {
        font-size: 13px;
    }
</style>

<!-- BODY-->
<div style="max-width: 700px; width: 100%; background: #fff;margin: 30px auto;">

    <div style="padding:30px 60px;">
        <div style="text-align: center;padding: 20px 0;">
            <img src="{{ asset('theme/images/taikisha.png') }}" alt="company logo" width="175" />
        </div>

        <p style="margin-top: 30px;"><strong>Dear {{ $recipient->name }},</strong></p>

        <p>
            Please click the button below to set up your password and complete the activation process of your account
        </p>

        <br />

        <div style="text-align: center;">
           <a href="{{ URL::to('/create-password') }}?token=n8uien28j9mh2673ge23e238ye7283ed2iojdfdfigbfdgsdfm83&email={{ urlencode($recipient->email) }}" target="_blank" style="padding: 10px 20px; background: #0349fc; color: #fff; text-decoration: none; font-size: 14px; border-radius: 3px;">Activate my account</a>
        </div>

        <br />

        <p>If you did not request a password reset, please ignore this email or communicate with us if you have questions.</p>

        <br />

        <p>For any inquiry or comments, please contact us at <a href="javascript:void(0)">{{ $setting->email }}</a>. Thank you.</p>

        <br />

        <br />

        <p>
            Regards, <br />
            <strong>
                {{ $setting->company_name }}
            </strong>
        </p>
    </div>

    <div style="padding: 30px;background: #fff;margin-top: 20px;border-top: solid 1px #eee;text-align: center;color: #aaa;">
        <p style="font-size: 12px;">
            <strong>{{ $setting->company_name }}</strong> <br /> {{ $setting->company_address }} <br /> {{ $setting->tel_no }} | {{ $setting->mobile_no }}

            <br /><br /> <a href="{{ url('/') }}">View Website</a>
        </p>
    </div>
</div>

</body>

</html>
