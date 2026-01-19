<?php

namespace App\Blockchains\BinanceSmartChain;

use App\Blockchains\EthereumRpc;
use App\Jobs\NewFilterLogJob;
use App\Models\Block;
use Exception;

class Handler
{

    protected $network;
    protected $contractAddress;
    protected $uri;
    protected $subscriptionMethods;
    protected $chain = 'bsc';
    protected $commandIds = [
        'blockFilter' => 1,
        'getFilterChanges' => 2,
        'getLogs' => 3,
        'getTransactionReceipt' => 4,
        'getBlockByNumber' => 6,
        'getLastestBlockNumber' => 7
    ];

    public function __construct()
    {
        $this->configure();
    }

    private function configure()
    {
        $this->setNetwork()
            ->setContractAddress()
            ->setUri()
            ->setSubscriptionMethods();
    }

    protected function setSubscriptionMethods(): self
    {
        $this->subscriptionMethods = config('network_bsc.subscription_methods');
        return $this;
    }

    private function setUri(): self
    {
        $this->uri = config('network_bsc.networks.' . $this->network . '.rpc_uri');
        return $this;
    }

    private function setContractAddress(): self
    {
        $contractAddress = config('network_bsc.contract_address');
        if (is_null($contractAddress)) {
            throw new Exception("Contract Address does not exist");
        }
        $this->contractAddress = strtolower($contractAddress);
        return $this;
    }

    private function setNetwork(): self
    {
        $this->network = strtolower(config('network_bsc.default'));
        return $this;
    }

    public static function init(): self
    {
        return new static();
    }

    public function run()
    {
        //        $this->subscribeToBlockFilter();
        $this->getLogs();
    }

    private function getLogs()
    {

        $getNextBlock = $this->getNextBlockNumber();
        $getLastBlock = $this->getLastBlock();
        $blockDiff = bcHexToDec($getNextBlock) - bcHexToDec($getLastBlock);
        if ($blockDiff < 1) {
            return;
        }
        $inputParams = [
            'fromBlock' => $getLastBlock,
            'toBlock' => $getNextBlock,
            'address' => $this->contractAddress,
            'topics' => []
        ];
        //        Log::info($this->uri);
        //        Log::info($this->contractAddress);
        //        Log::info(json_encode($inputParams));
        $geLogs = json_decode(EthereumRpc::getLogs($this->uri, $this->commandIds['getLogs'], $inputParams), true);
        //        Log::info($geLogs);
        if ($this->hasError($geLogs)) {
            return;
        }
        $this->processGetLogResult($geLogs['result'], $getNextBlock);
    }

    public function getNextBlockNumber()
    {
        $currentBlock = $this->getLastBlock();
        $getBlockNumber = json_decode(EthereumRpc::getBlockNumber($this->uri, $this->commandIds['getLastestBlockNumber']), true);

        if (!$this->hasError($getBlockNumber)) {
            $latestBlockNumber = $getBlockNumber['result'];
            $blockDiff = bcHexToDec($latestBlockNumber) - bcHexToDec($currentBlock);
            if ($blockDiff > 25) {
                $newBlock = (int)bcHexToDec($currentBlock);
                return strtolower("0x" . bcDecHex((string)($newBlock + 25)));
            } else {
                $latestBlockNumberDec = bcHexToDec($latestBlockNumber);
                return strtolower("0x" . bcDecHex((string)($latestBlockNumberDec - 1)));
            }
        } else {
            return $currentBlock;
        }
    }

    private function getLastBlock()
    {
        $block = Block::first()->getLastBlock();
        $blockInDec = (int)(bcHexToDec($block)) + 1;
        return strtolower("0x" . bcDecHex((string)$blockInDec));
    }

    private function hasError($rpcReponse)
    {
        if (!isset($rpcReponse) || isset($rpcReponse['error'])) {
            return true;
        }

    }

    private function processGetLogResult(array $results, string $nextBlockNumber)
    {
        if (count($results) > 0) {
            foreach ($results as $result) {
                if (strtolower($result['address']) !== strtolower($this->contractAddress)) {
                    continue;
                } elseif (isset($result['removed']) && $result['removed'] == true) {
                    continue;
                } else {
                    dispatch(new NewFilterLogJob($result))->onQueue('newFilter')->delay(now()->addSecond());
                    Block::first()->updateLastBlock(strtolower($nextBlockNumber));
                    continue;
                }

            }
        } else {
            Block::first()->updateLastBlock(strtolower($nextBlockNumber));
        }

    }

    public function getBlockByNumber($blockNumberHex)
    {
        $blockNumberHex = strtolower($blockNumberHex);
        return json_decode(EthereumRpc::getBlockByNumber($this->uri, $this->commandIds['getBlockByNumber'], $blockNumberHex));

    }

    public function getTransactionReceipt(string $txnHash)
    {
        if (!is_string($txnHash)) {
            return null;
        }

        $receipt = json_decode(EthereumRpc::getTransactionReceipt($this->uri, $txnHash), true);

        if ($this->hasError($receipt)) {
            return null;
        }

        return $receipt;

    }

    public function getContractAddress()
    {
        return $this->contractAddress;
    }

}
