<!DOCTYPE html>
<html>

<body
    style="padding: 20px; font-size: 14px; line-height: 1.43; font-family: Helvetica, Arial, sans-serif;
background: linear-gradient(135deg,#0046fd,#00fafe);
">
<div style="max-width: 600px; margin: 10px auto 20px; font-size: 12px; color: #000; text-align: center;">If you are
    unable to see this message, <a href="#" style="color: #000; text-decoration: underline;">click here to view in
        browser</a></div>
<div style="max-width: 600px; margin: 0px auto; background-color: #fff; box-shadow: 0px 20px 50px rgba(0,0,0,0.05);border-radius: 8px;">
    <table style="width: 100%;">
        <tr class="background-color: #222633;">
            <td>
                <img alt="" src="{{asset('assets/images/logo.png')}}" style="width: 100px; padding: 10px">
            </td>
            <td style="padding-left: 50px; text-align: right; padding-right: 20px;">
                <a href="{{route('login')}}" style="color: #000; text-decoration: underline; font-size: 14px; letter-spacing: 1px;">Sign In</a>
            </td>
        </tr>
    </table>
    <div style="padding: 60px 70px; border-top: 1px solid rgba(0,0,0,0.05);"><h1 style="margin-top: 0px;">
            Hi {{$user->ref_code}},<br> {{$user->name}}</h1>
        <div style="color: #636363; font-size: 14px;"><p>Thank you for creating account on our website, there is one
                more step before you can use it, you need to activate your account by clicking the link below. Once you
                click the button, just login to your account and you are set to go.</p></div>
        <a href="{{$url}}"
           style="padding: 8px 20px; background-color: #4B72FA; color: #fff; font-weight: bolder; font-size: 16px; display: inline-block; margin: 20px 0px; margin-right: 20px; text-decoration: none;">Activate
            my account</a>
        <h4 style="margin-bottom: 10px;">Need Help?</h4>
        <div style="color: #A5A5A5; font-size: 12px;"><p>If you have any questions you can simply reply to this email or
                find our contact information below. Also contact us at <a href="{{'mailto:'.env('SUPPORT_EMAIL')}}"
                                                                          style="text-decoration: underline; color: #4B72FA;">{{env('SUPPORT_EMAIL')}}</a>
            </p></div>
    </div>
    <div style="background-color: #F5F5F5; padding: 40px; text-align: center; border-radius: 8px;">
        <div style="color: #A5A5A5; font-size: 12px; margin-bottom: 20px; padding: 0px 50px;">You are receiving this
            email because you signed up for {{env('APP_NAME')}}
        </div>
        <div style="margin-bottom: 20px;"><a href="#" style="display: inline-block; margin: 0px 10px;">
                <img alt="" src="img/market-google-play.png" style="height: 33px;"></a>
            <a href="#" style="display: inline-block; margin: 0px 10px;"><img alt="" src="img/market-ios.png"
                                                                              style="height: 33px;"></a>
        </div>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.05);">
            <div style="color: #A5A5A5; font-size: 10px;">Copyright 2022 {{env('APP_NAME')}}. All rights reserved.
            </div>
        </div>
    </div>
</div>
</body>
</html>
