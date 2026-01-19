<?php

namespace App\Http\Controllers;

use App\Jobs\UserDepositFundJob;
use App\Models\CryptApiTransaction;
use App\Models\CryptApiWallet;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GatewayApiController extends Controller
{

    public function processRequest(Request $request)
    {

        $quoteCurrency = config('quote_currency.' . config('quote_currency.default'));
        $cryptApiTransaction = $this->processCallback($request);
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
                    'currency_symbol' => $cryptoApiTxn->crypto,
                    'currency_price' => $cryptoPrice,
                    'amount' => $cryptoApiTxn->amount_out,
                    'quote_currency' => $quoteCurrency['symbol'],
                    'quote_amount' => divDecimalStrings(multipleDecimalStrings($cryptoApiTxn->amount_out, $cryptoPrice, 8), 1, 8),
//                    'quote_amount' => divDecimalStrings(multipleDecimalStrings($cryptoApiTxn->amount_in, $cryptoPrice, 8), 1, 8),
                    'txn_time' => now()
                ]);

                if ($depositTransaction) {
                    $invoice = Invoice::find($cryptoApiTxn->invoice_id);
                    if ($invoice) {
                        $invoice->update([
                            'deposit_transaction_id' => $depositTransaction->id,
                            'status' => 'success'
                        ]);
                       // dispatch(new ProcessTokenPurchaseJob($depositTransaction, $invoice));
                        dispatch(new UserDepositFundJob($depositTransaction));
                    }
                }
            }
        } else {
            Log::info("Not Array");
        }
    }

    protected function processCallback(Request $request)
    {

        $params = [
            'address_in' => $request->get('address_in'),
            'address_out' => $request->get('address_out'),
            'txid_in' => $request->get('txid_in'),
            'txid_out' => $request->get('txid_out') !== null ? $request['txid_out'] : null,
            'confirmations' => $request->get('confirmations'),
            'value' => $request->get('value'),
            'value_coin' => $request->get('value_coin'),
            'value_forwarded' => $request->get('value_forwarded') !== null ? $request->get('value_forwarded') : null,
            'value_forwarded_coin' => $request->get('value_forwarded_coin') !== null ? $request->get('value_forwarded_coin') : null,
            'coin' => $request->get('coin'),
            'pending' => $request->get('pending') !== null ? $request->get('pending') : false,
        ];
        return $params;
    }

}
