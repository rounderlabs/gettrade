<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Contact;
use App\Models\State;
use App\Models\UserBotAccount;
use App\Models\UserBotAuthenticationStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function product()
    {
        return view('product');
    }
    public function technology()
    {
        return view('technology');
    }

    public function aboutUs()
    {
        return view('about');
    }

    public function subscription()
    {
        return view('subscriptions');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function tnc()
    {
        return view('tnc');
    }

    public function riskDisclosure()
    {
        return view('risk_disclosure');
    }

    public function customerAgreement()
    {
        return view('customer-agreement');
    }

    public function whyWe()
    {
        return view('whywe');
    }

    public function faq()
    {
        return view('help');
    }

    public function returns()
    {
        return view('help');
    }

    public function feature()
    {
        return view('feature');
    }

    public function contact()
    {
        return view('contact');
    }

    public function saveContact(Request $request)
    {

        $request->validate([
            'name' => ['required','max:60'],
            'email' => ['required', 'email'],
            'mobile' => ['required'],
            'company_name' => ['required'],
            'message' => ['max:255'],

        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'company_name' => $request->company_name,
            'message' => $request->message,
        ]);

        return redirect()->route('home')->with('status', 'Thanks You request successfully submitted. One of our executive will get back to you shortly.');
//        route('contact')->with('status', 'Thanks You request successfully submitted. One of our executive will get back to you shortly.');
    }

    public function getCities(Request $request)
    {
        $request->validate([
            'state' => ['required', 'exists:states,id']
        ]);

        $state = State::where('id', $request->state)->first();

        if (!is_null($state)) {
            $cities = City::select('id', 'name')->where('state_id', $state->id)->orderBy('name')->get();
            return response()->json($cities);
        }
        return response()->json([]);

    }

    public function botAuthStat($bot_username, $status=null)
    {
        if (!UserBotAccount::where('bot_username', strtoupper($bot_username))->exists()) {
            return response()->json(['message' => 'Username not exist!'], 403);
        }
        $userBotAccount = UserBotAccount::where('bot_username', $bot_username)->where('is_active', 1)->first();
        UserBotAuthenticationStat::create([
            'user_id' => $userBotAccount->user_id,
            'user_bot_account_id' => $userBotAccount->user_id,
            'authenticated_at' => now(),
            'status' => trim($status)
        ]);

        return response()->json(['message' => 'success'], 200);

    }


}
