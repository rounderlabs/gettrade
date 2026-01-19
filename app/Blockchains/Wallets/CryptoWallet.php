<?php


namespace App\Blockchains\Wallets;


use App\Blockchains\Bitcoin\BitcoinHandler;
use App\Blockchains\Tron\Handler;
use App\Models\Coin;
use Exception;

class CryptoWallet
{

    private string $coinName;
    private Coin $coinModel;


    /**
     * @throws Exception
     */
    public function __construct(string $coinName)
    {
        $this->coinName = $coinName;
        $this->addCoinModel();

    }

    /**
     * @throws Exception
     */
    private function addCoinModel()
    {
        $coin = Coin::where('name', strtolower($this->coinName))->first();
        if (!$coin) {
            throw new Exception("Invalid Coin");
        }
        $this->coinModel = $coin;
    }

    /**
     * @param string $coinName
     * @return CryptoWallet
     * @throws Exception
     */
    public static function init(string $coinName): self
    {
        return new static($coinName);
    }

    public function generateWallet(string $label = '')
    {
        if (!$this->coinModel) {
            return null;
        }
        if ($this->coinModel->name == 'btc') {
            return $this->generateBitcoinAddress($label);
        } elseif ($this->coinModel->name == 'eth') {
            return $this->generateEthereumWallet();
        } elseif ($this->coinModel->name == 'trx') {
            return $this->generateTronAddress();
        }
    }

    private function generateBitcoinAddress(string $label = '')
    {
        return BitcoinHandler::init()->getNewAddress($label);
    }

    private function generateEthereumWallet()
    {
        $ethereumWallet = EthereumWallet::wallet()->generateNewWallet();
        if (!is_array($ethereumWallet)) {
            return null;
        }
        if (strtolower($ethereumWallet['address']) !== '0x' . strtolower(EthereumWallet::wallet()->fromPrivateKey($ethereumWallet['priv_key'])->getAddress())) {
            return null;
        }
        return $ethereumWallet;
    }

    private function generateTronAddress()
    {
        return Handler::init()->getNewAddress();
    }
}
