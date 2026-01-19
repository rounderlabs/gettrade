<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAadharCard;
use App\Models\UserKyc;
use App\Models\UserWithdrawFrequency;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use function auth;

class UserKycController extends Controller
{
    public function showIdForm()
    {
        return Inertia::render('Kyc/IdentityKycForm');
    }

    public function showBankingForm()
    {
        return Inertia::render('Kyc/BankKycForm');
    }

    public function showKycStatus()
    {
        $kyc = UserKyc::where('user_id', auth()->user()->id)->first();
        return Inertia::render('Kyc/KycStatus', [
            'kyc'=>$kyc
        ]);
    }

    public function submitIdForm(StoreAadharCard  $request)
    {
        $request->validate([
            'aadhar_no'=>['required'],
            'pan_no'=>['required'],
        ]);

        $aadhar_path = '';
        $pan_path = '';
        if ($request->hasFile('aadhar_file')) {
            $aadhar_path = $request->file('aadhar_file')->store('user/kyc/aadhar', 'public');
        }

        if ($request->hasFile('pan_file')) {
            $pan_path = $request->file('pan_file')->store('user/kyc/pan', 'public');
        }
        $kyc = UserKyc::create([
            'user_id'=>auth()->user()->id,
            'aadhar_no'=>$request->aadhar_no,
            'aadhar_img'=>$aadhar_path,
            'pan_no'=>$request->pan_no,
            'pan_img'=>$pan_path,
        ]);

        if (!$kyc) {
            throw ValidationException::withMessages([
                'invoice' => 'Error ! Please Check the details',
            ]);
        }
        return redirect()->route('kyc.final.step')->with('notification', ['Your Identity Verification  Submitted Successfully', 'success']);
    }

    public function submitBankingForm(Request $request)
    {
        $request->validate([
            'bank_name'=>['required'],
            'account_no'=>['required'],
            'ifsc_code'=>['required'],
            'holder_name'=>['required'],
        ]);

        $kyc = UserKyc::where('user_id', auth()->user()->id)->first();
        $kyc->update([
            'bank_name'=>$request->bank_name,
            'account_no'=>$request->account_no,
            'ifsc_code'=>$request->ifsc_code,
            'holder_name'=>$request->holder_name,
            'upi_id'=>$request->upi_id
        ]);

        if (!$kyc) {
            throw ValidationException::withMessages([
                'invoice' => 'Error ! Please Check the details',
            ]);
        }
        return redirect()->route('kyc.set.frequency')->with('notification', ['Your KYC Submitted Successfully', 'success']);
    }

    public function showDurationForm()
    {
        $user = auth()->user();
        $userWithdrawalFrequency = userWithdrawalFrequency($user);
        $frequency = UserWithdrawFrequency::where('user_id', $user->id)->first();
        return Inertia::render('Kyc/WithdrawFrequencyForm',[
            'frequency'=>$frequency
        ]);
    }

    public function submitFrequencyForm(Request $request)
    {
        $request->validate([
            'frequency' =>'required',
        ]);
        $brokerage_percent = 0;
        if ($request->frequency == 'Monthly'){
            $brokerage_percent = 50;
        }elseif ($request->frequency == 'Weekly'){
            $brokerage_percent = 45;
        }elseif ($request->frequency == 'Daily'){
            $brokerage_percent = 40;
        }
        $user = auth()->user();
        $userWithdrawalFrequency = userWithdrawalFrequency($user);
        $userWithdrawalFrequency->update([
            'frequency'=>$request->frequency,
            'brokerage_percent'=>$brokerage_percent
        ]);

        return redirect()->route('dashboard')->with('notification', ['Withdrawal Frequency Updated Successfully', 'success']);
    }
}
