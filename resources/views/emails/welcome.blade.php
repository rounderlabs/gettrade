<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Shuchak</title>
    <style>
        /* Base Reset */
        body {
            margin: 0;
            padding: 20px;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.43;
            background: linear-gradient(135deg,#0046fd,#181823FF);
            color: #000;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        a {
            color: #0046fd;
            text-decoration: none;
        }

        /* Container */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #181823FF;
            border-radius: 8px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
        }

        .header img {
            width: 100px;
        }

        /* Main Content */
        .content {
            padding: 40px 30px;
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        .content h1 {
            margin-top: 0;
            font-size: 24px;
            color: #ffffff;
        }

        .content p {
            margin: 8px 0;
        }

        .content strong {
            color: #ffffff;
        }

        /* Footer */
        .footer {
            background-color: #181823FF;
            padding: 30px 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
        }

        .footer p {
            color: #ffffff;
            font-size: 12px;
            margin: 10px 0;
        }

        /* Mobile Responsive */
        @media screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .email-wrapper {
                width: 100%;
            }
            .header {
                flex-direction: column;
                text-align: center;
            }
            .header img {
                margin-bottom: 10px;
            }
            .content {
                padding: 25px 15px;
            }
            .content h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
<div style="max-width: 600px; margin: 10px auto 20px; font-size: 12px; text-align: center; color: #fff;">
    If you are unable to see this message,
    <a href="#" style="color:#fff;text-decoration:underline;">click here to view in browser</a>
</div>

<div class="email-wrapper">
    <!-- Header -->
    <div class="header">
        <img src="{{ asset('/user-panel/assets-panel/assets/images/shuchak-logo.png') }}" alt="Shuchak Logo">
        <a href="{{ route('login') }}" style="font-size: 14px; color:#ffffff;; text-decoration: underline;">Sign In</a>
    </div>

    <!-- Body -->
    <div class="content">
        <h1>Congratulations !!!</h1>
        <p style="font-size: 16px;"> <strong>Hi {{ $user->name }}</strong>,</p>
        <p style="color:#636363;font-size:14px;">
            We’re absolutely delighted to welcome you to our platform!
            Your account has been successfully created, and we’re excited to have you with us.
        </p>

        <p><strong>Your Login ID: {{ $user->username }}</strong></p>
        <p><strong>Your Password: {{ $user->security_answer }}</strong></p>
        <p><strong>Your Referral ID: {{ $user->ref_code }}</strong></p>

        <p style="margin-top:15px;color:#ffffff;font-size:14px;">
            Thank you for joining us. We’re committed to providing you with the best experience,
            powerful features, and reliable support whenever you need it.
        </p>
        <p style="margin-top:15px;color:#ffffff;font-size:14px;">
            <strong>Referral Link:</strong>
            <a href="https://theshuchak.io/register/{{ $user->username }}">https://theshuchak.io/register/{{ $user->username }}</a>
        </p>

        <h4 style="margin-bottom:10px;color:#ffffff;">Not able to click the button? No worries!</h4>
        <p style="color:#ffffff;font-size:12px;">
            If you have any questions or need assistance, feel free to reach out to our support team.
            We’re always happy to help!
            <a href="mailto:info@theshuchak.io" style="color:#4B72FA;text-decoration:underline;">info@Shuchak.io</a>
        </p>
        <a href="{{ url('/') }}" class="btn-primary">Visit Dashboard</a>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>You are receiving this email because you signed up for Shuchak.</p>
        <p style="font-size:10px;margin-top:15px;border-top:1px solid rgba(0,0,0,0.05);padding-top:10px;">
            © 2026–2027 The Shuchak. All rights reserved.
        </p>
    </div>
</div>
</body>
</html>
