<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ site_setting('site_name', 'Get Trade') }}</title>

    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.43;
            background: linear-gradient(135deg,#0046fd,#181823FF);
            color: #000;
        }
        table { border-collapse: collapse; width: 100%; }
        a { color: #4B72FA; text-decoration: none; }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #181823FF;
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
        }

        .header img { width: 100px; }

        .content {
            padding: 40px 30px;
        }

        .content h1 {
            font-size: 24px;
            color: #ffffff;
        }

        .content p { color: #d0d0d0; }

        .footer {
            padding: 20px;
            text-align: center;
            background: #181823FF;
        }

        .footer p {
            color: #aaa;
            font-size: 12px;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 22px;
            background: #0046fd;
            color: #fff !important;
            border-radius: 4px;
            font-weight: bold;
        }

        @media (max-width:600px){
            .header { flex-direction: column; }
        }
    </style>
</head>

<body>

<div style="text-align:center;color:#fff;font-size:12px;margin-bottom:10px;">
    If you cannot view this email,
    <a href="{{ url()->current() }}" style="color:#fff;text-decoration:underline;">click here</a>
</div>

<div class="email-wrapper">

    <!-- Header -->
    <div class="header">
        <img src="{{ site_setting('logo_desktop', asset('assets/images/logo.png')) }}" alt="Logo">
        <a href="{{ route('login') }}" style="color:#fff;text-decoration:underline;">Sign In</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h1>🎉 Welcome to {{ site_setting('site_name', 'Get Trade') }}!</h1>

        <p style="font-size:16px;">
            Hi <strong style="color:#fff">{{ $user->name }}</strong>,
        </p>

        <p>
            Your account has been successfully created. We’re excited to have you onboard!
        </p>

        <p><strong style="color:#fff">Username:</strong> {{ $user->username }}</p>
        <p><strong style="color:#fff">Referral ID:</strong> {{ $user->ref_code }}</p>

        <p>
            <strong style="color:#fff">Referral Link:</strong><br>
            <a href="{{ site_setting('site_url') }}/register/{{ $user->username }}">
                {{ site_setting('site_url') }}/register/{{ $user->username }}
            </a>
        </p>

        <a href="{{ site_setting('site_url') }}" class="btn">Go to Dashboard</a>

        <p style="margin-top:20px;font-size:12px;">
            Need help? Contact us at
            <a href="mailto:{{ site_setting('support_email','info@getwealth.live') }}">
                {{ site_setting('support_email','info@getwealth.live') }}
            </a>
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            © {{ date('Y') }} {{ site_setting('site_name','Get Wealth') }}.
            All rights reserved.
        </p>
    </div>

</div>
</body>
</html>
