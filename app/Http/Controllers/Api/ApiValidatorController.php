<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ValidatorActiveStat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiValidatorController extends Controller
{
    public function linkValidator(Request $request)
    {
       // Log::info($request);
       // return $request->all();
        $validate = Validator::make($request->all(), [
            'linking_id' => ['required', 'numeric'],
            'mac_address1' => ['required'],
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => 'Something Went Wrong, Please Check Linking ID','status' => 'Failed', 'message' => $validate->messages()], 200);
        }

        $linkingId   = $request->linking_id;
        $macAddress1 = $request->mac_address1;
        $macAddress2 = $request->mac_address2;

        $validator = \App\Models\Validator::where('linking_id', $linkingId)->first();
        if (is_null($validator)) {
            return response()->json(['error' => 'Input Error!','status' => 'Failed', 'message' => 'Linking Id is Invalid'], 200);
        }
        if ($validator->is_active === 1){
            return response()->json(['error' => 'Input Error!','status' => 'Failed', 'message' => 'Linking Id is Already Linked With a Validator'], 200);
        }
        $validator_mac = \App\Models\Validator::where('mac_address1', $macAddress1)->first();

        if ($validator_mac) {
            if ($validator_mac->mac_address1 == $macAddress1 || $validator_mac->mac_address1 == $macAddress2 || $validator_mac->mac_address2 == $macAddress1 || $validator_mac->mac_address2 == $macAddress2 || $validator_mac->is_active === 1) {
                return response()->json(['error' => 'Input Error!', 'status' => 'Failed', 'message' => 'Validator already linked with another Linking ID'], 433);
            }
        }
        $maxDelegator = 0;
        if($validator->type == \App\Models\Validator::TYPE['VALIDATOR'] ){
            $maxDelegator = 20 ;
        }
        if($validator->type == \App\Models\Validator::TYPE['VALIDATOR_PRO'] ){
            $maxDelegator = 40 ;
        }
        if($validator->type == \App\Models\Validator::TYPE['VALIDATOR_PRO_MAX'] ){
            $maxDelegator = 80 ;
        }

        $validator->update([
            'mac_address1' => $macAddress1,
            'mac_address2' => $macAddress2,
            'max_delegator' => $maxDelegator,
            'delegator_connected' => 0,
            'linking_date' => now()->format('Y-m-d'),
            'is_active' => 1
        ]);

        return response()->json([
            'error' => '0',
            'status' => 'success',
            'type' => $validator->type,
            'message' => 'Validator linked successfully!'
        ]);
    }

    public function validatorPing(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'linking_id' => ['required', 'numeric', 'exists:validators,linking_id'],
            'mac_address1' => ['required'],
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => 'Invalid Input', 'status' => 'Failed','message' => $validate->messages()], 200);
        }

        $linkingId = $request->linking_id;
        $macAddress1 = $request->mac_address1;
        $macAddress2 = $request->mac_address2;

        $validator = \App\Models\Validator::where('linking_id', $linkingId)->first();
        if (is_null($validator)) {
            return response()->json(['error' => 'Input Error!','status' => 'Failed', 'message' => 'Linking Id is Invalid'], 200);
        }
        $max_hit_count = 0 ;
        if($validator->type == \App\Models\Validator::TYPE['VALIDATOR'] ){
            $max_hit_count = 8 ;
        }
        if($validator->type == \App\Models\Validator::TYPE['VALIDATOR_PRO'] ){
            $max_hit_count = 16 ;
        }
        if($validator->type == \App\Models\Validator::TYPE['VALIDATOR_PRO_MAX'] ){
            $max_hit_count = 24 ;
        }

        if ($validator->mac_address1 == $macAddress1 || $validator->mac_address1 == $macAddress2 || $validator->mac_address2 == $macAddress1 || $validator->mac_address2 == $macAddress2) {

            $validatorLinkStat = ValidatorActiveStat::firstOrCreate([
                'user_id'=>$validator->user_id,
                'validator_id' => $validator->id,
                'stats_date' => now()->format('Y-m-d')
            ],[
                'validator_hit_count' => 0,
                'max_hit_count' => $max_hit_count,
            ]);

            $updatedTime = Carbon::parse($validatorLinkStat->updated_at);
            $next = Carbon::parse(now());
            $frequency =$next->diffInMinutes($updatedTime);
            if ($frequency >= 1 ){
                $validatorLinkStat = ValidatorActiveStat::where('validator_id', $validator->id)->where('stats_date',now()->format('Y-m-d'))->first();
                $total_hit = $validatorLinkStat->validator_hit_count;
                if ($total_hit < $max_hit_count) {
                    $validatorLinkStat->increment('validator_hit_count', 1);
                }
            }

//            if (Carbon::parse($validatorLinkStat->updated_at)->unix() >= now()->subMinute(15)->unix()) {
//                $validatorLinkStat->increment('validator_hit_count',0);
//            }else{
//                $validatorLinkStat = ValidatorActiveStat::where('validator_id', $validator->id)->where('stats_date',now()->format('Y-m-d'))->first();
//                $total_hit = $validatorLinkStat->validator_hit_count;
//                if ($total_hit < $max_hit_count) {
//                    $validatorLinkStat->increment('validator_hit_count', 1);
//                }
//            }
            $validatorLinkStat = ValidatorActiveStat::where('validator_id', $validator->id)->where('stats_date',now()->format('Y-m-d'))->first();
            $total_hit = $validatorLinkStat->validator_hit_count;

            if ($total_hit >= $max_hit_count){
                return response()->json([
                    'status' => 'success',
                    'time_laps' => $frequency.'Minutes',
                    'total_hit' => $total_hit,
                    'max_hit' => $max_hit_count,
                    "message"=> "Today's Validation Has Been Successfully Completed.",
                    "action_required"=> "Stop the validation"
                ]);
            }
            return response()->json([
                'status' => 'success',
                'time_laps' => $frequency.' Minute',
                'total_hit' => $total_hit,
                'max_hit' => $max_hit_count,
                "message"=> "Pinging!",
                "action_required"=> "continue the validation"
            ]);
        }

    }
}
