<!DOCTYPE html>
<html>

<body
    style="padding: 20px; font-size: 14px; line-height: 1.43; font-family: Helvetica, Arial, sans-serif;
    background: linear-gradient(135deg,#0046fd,#00fafe);
">
<div style="max-width: 600px; margin: 10px auto 20px; font-size: 12px; color: #000; text-align: center;">
    If you are unable to see this message, <a href="#" style="color: #000; text-decoration: underline;">click here to
        view in browser</a></div>
<div
    style="max-width: 600px; margin: 0px auto; background-color: #fff; box-shadow: 0px 20px 50px rgba(0,0,0,0.05);border-radius: 8px;">
    <table style="width: 100%;">
        <tr class="background-color: #222633;">
            <td>
                <img alt="" src="{{asset('assets/images/logo.png')}}" style="width: 100px; padding: 10px">
            </td>
            <td style="padding-left: 50px; text-align: right; padding-right: 20px;">

            </td>
        </tr>
    </table>
    <div style="padding: 60px 70px; border-top: 1px solid rgba(0,0,0,0.05);">
        <div style="color: #636363; font-size: 18px; padding: 10px">
            <h2>New Contact Us Message!</h2>
            <h3>Subject : {{$subject}}</h3>
            <h4>Name : {{$name}}</h4>
            <h5>Email : {{$email}}</h5>
            <p>Message : {{ $comment}}</p>
        </div>
    </div>
    <div style="background-color: #F5F5F5; padding: 40px; text-align: center;border-radius: 8px;">
        <div style="color: #A5A5A5; font-size: 12px; margin-bottom: 20px; padding: 0px 50px;">You are receiving this
            email because users are filling the contact- us form
        </div>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.05);">
            <div style="color: #A5A5A5; font-size: 10px;">Copyright {{ date('Y') }} {{config('app.name')}}. All rights
                reserved.
            </div>
        </div>
    </div>
</div>
</body>
</html>
