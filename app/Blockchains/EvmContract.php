<?php


namespace App\Blockchains;


class EvmContract
{

    const CHAIN = [
        'eth' => 'eth',
        'bsc' => 'bsc'
    ];
    private string $abiJson;
    private string $rpcUri;
    private string $contractAddress;
    private string $fromAddress;
    private string $fromPrivateKey;
    private string $chain;

    public function __construct(string $chain = 'eth')
    {
        $this->chain = $chain;
    }

    public static function init(string $chain = 'eth'): self
    {
        return new static($chain);
    }

    public function setAbi(string $abiJson): self
    {
        $this->abiJson = $abiJson;
        return $this;
    }

    public function setRpc(string $rpcUri): self
    {
        $this->rpcUri = $rpcUri;
        return $this;
    }

    public function setFromAddress(string $fromAddress): self
    {
        $this->fromAddress = strtolower($fromAddress);
        return $this;
    }

    public function setFromPrivateKey(string $fromPrivateKey): self
    {
        $this->fromPrivateKey = $fromPrivateKey;
        return $this;
    }

    public function setContractAddress(string $contractAddress): self
    {
        $this->contractAddress = $contractAddress;
        return $this;
    }

}
