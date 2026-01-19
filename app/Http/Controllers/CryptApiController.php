<?php

namespace App\Http\Controllers;

use App\Blockchains\Api\CryptApi\CryptApi;
use App\Jobs\UserDepositFundJob;
use App\Models\CryptApiTransaction;
use App\Models\CryptApiWallet;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CryptApiController extends Controller
{

//    public function processRequest(Request $request)
//    {
//        $cryptApiTransaction = CryptApi::processCallback($request);
//
//        if (is_array($cryptApiTransaction)) {
//            if (CryptApiTransaction::where('txn_in', $cryptApiTransaction['txid_in'])->exists()) {
//                return;
//            }
//
//            $cryptApiWallet = CryptApiWallet::where('address_in', $cryptApiTransaction['address_in'])->where('address_out', $cryptApiTransaction['address_out'])->first();
//            if (is_null($cryptApiWallet)) {
//                return;
//            }
//            $cryptoPrice = $cryptApiTransaction['coin'] == 'trx' ? tronPrice() : castDecimalString('1', 8);
//            $cryptoApiTxn = CryptApiTransaction::create([
//                'user_id' => $cryptApiWallet->user_id,
//                'invoice_id' => $cryptApiWallet->invoice_id,
//                'address_in' => $cryptApiTransaction['address_in'],
//                'address_out' => $cryptApiTransaction['address_out'],
//                'crypto' => $cryptApiTransaction['coin'],
//                'txn_in' => $cryptApiTransaction['txid_in'],
//                'txn_out' => $cryptApiTransaction['txid_out'],
//                'amount_in' => castDecimalString($cryptApiTransaction['value_coin'], '8'),
//                'amount_out' => castDecimalString($cryptApiTransaction['value_forwarded_coin'], '8'),
//                'crypto_price' => $cryptoPrice
//            ]);
//            if ($cryptoApiTxn) {
//
//                $depositTransaction = $cryptoApiTxn->depositTransaction()->create([
//                    'user_id' => $cryptoApiTxn->user_id,
//                    'address' => $cryptoApiTxn->address_in,
//                    'crypto' => $cryptoApiTxn->crypto,
//                    'crypto_price' => $cryptoPrice,
//                    'amount' => $cryptoApiTxn->amount_in,
//                    'amount_in_usd' => multipleDecimalStrings($cryptoPrice, $cryptoApiTxn->amount_in, 2),
//                    'txn_time' => now()
//                ]);
//
//                if ($depositTransaction) {
//                    $invoice = Invoice::find($cryptoApiTxn->invoice_id);
//                    if ($invoice && $invoice->status == 'pending') {
//                        $invoice->update([
//                            'deposit_transaction_id' => $depositTransaction->id,
//                            'status' => 'success'
//                        ]);
//                        dispatch(new PurchaseJob($depositTransaction));
//                    }
//
//                }
//
////                $usdValue = $depositTransaction['coin'] == 'trx' ? multipleDecimalStrings($depositTransaction['value_coin'], tronPrice(), 2) : $depositTransaction['value_coin'];
//////                $depositUsdWallet = DepositUsdWallet::firstOrCreate(
//////                    ['user_id' => $cryptApiWallet->user_id],
//////                    ['balance' => castDecimalString('0', 2), 'balance_on_hold' => castDecimalString('0', 2)]
//////                );
//////                $depositUsdWallet->increment('balance', $usdValue);
//            }
//        } else {
//            Log::info("Not Array");
//        }
//    }

    public function processRequest(Request $request)
    {
        $cryptApiTransaction = CryptApi::processCallback($request);

        if (is_array($cryptApiTransaction)) {
            if (CryptApiTransaction::where('txn_in', $cryptApiTransaction['txid_in'])->exists()) {
                return;
            }

            $cryptApiWallet = CryptApiWallet::where('address_in', $cryptApiTransaction['address_in'])->where('address_out', $cryptApiTransaction['address_out'])->first();
            if (is_null($cryptApiWallet)) {
                return;
            }
            $cryptoPrice = getDepositCoinPrice($cryptApiTransaction['coin']);
            $cryptoApiTxn = CryptApiTransaction::create([
                'user_id' => $cryptApiWallet->user_id,
                'invoice_id' => $cryptApiWallet->invoice_id,
                'address_in' => $cryptApiTransaction['address_in'],
                'address_out' => $cryptApiTransaction['address_out'],
                'crypto' => $cryptApiTransaction['coin'],
                'txn_in' => $cryptApiTransaction['txid_in'],
                'txn_out' => $cryptApiTransaction['txid_out'],
                'amount_in' => castDecimalString($cryptApiTransaction['value_coin'], '8'),
                'amount_out' => castDecimalString($cryptApiTransaction['value_forwarded_coin'], '8'),
                'crypto_price' => $cryptoPrice
            ]);
            if ($cryptoApiTxn) {

                $depositTransaction = $cryptoApiTxn->depositTransaction()->create([
                    'user_id' => $cryptoApiTxn->user_id,
                    'address' => $cryptoApiTxn->address_in,
                    'crypto' => $cryptoApiTxn->crypto,
                    'crypto_price' => $cryptoPrice,
                    'amount' => $cryptoApiTxn->amount_in,
                    'amount_in_usd' => multipleDecimalStrings($cryptoPrice, $cryptoApiTxn->amount_in, 2),
                    'txn_time' => now()
                ]);

                if ($depositTransaction) {
                    $invoice = Invoice::find($cryptoApiTxn->invoice_id);
                    if ($invoice) {
                        $invoice->update([
                            'deposit_transaction_id' => $depositTransaction->id,
                            'status' => 'success'
                        ]);
                        dispatch(new UserDepositFundJob($depositTransaction));
                    }
                }
            }
        } else {
            Log::info("Not Array");
        }
    }
}
