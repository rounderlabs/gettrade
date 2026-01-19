<?php


namespace App\Blockchains\Wallets;


use Exception;
use kornrunner\Ethereum\Address;
use PsychoB\Ethereum\AddressValidator;

class EthereumWallet
{

    private string $privateKey;

    public static function wallet(): self
    {
        return new static();
    }

    public function generateNewWallet()
    {
        $address = new Address();
        return [
            'address' => "0x" . $address->get(),
            'priv_key' => $address->getPrivateKey(),
            'public_key' => "0x" . $address->getPublicKey()
        ];
    }

    public function isValidAddress(string $address)
    {
        return AddressValidator::isValid($address) == AddressValidator::ADDRESS_VALID;
    }

    public function fromPrivateKey(string $privateKey): self
    {
        $this->privateKey = $privateKey;
        return $this;
    }

    public function getAddress()
    {
        if (!isset($this->privateKey)) {
            throw new Exception('Invalid Private key');
        }
        $address = new Address($this->privateKey);
        return $address->get();
    }
}
