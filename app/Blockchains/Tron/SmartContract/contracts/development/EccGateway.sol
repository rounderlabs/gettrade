pragma solidity ^0.5.4;

contract Owned {

    address owner;
    constructor() public {owner = msg.sender;}


    // This contract only defines a modifier but does not use
    // it: it will be used in derived contracts.
    // The function body is inserted where the special symbol
    // `_;` in the definition of a modifier appears.
    // This means that if the owner calls this function, the
    // function is executed and otherwise, an exception is
    // thrown.
    modifier onlyOwner {
        require(
            msg.sender == owner,
            "Only owner can call this function."
        );
        _;
    }
}

contract EccGateway is Owned {

    event NewPayment(address indexed from, uint256 userId, uint256 amount, uint256 paymentTime);

    address paymentCollector;
    constructor(address _paymentCollector) public {
        paymentCollector = _paymentCollector;
    }

    function withdrawLostTRXFromBalance() public onlyOwner {
        send(owner, address(this).balance);
    }


    function depositNewPayment(uint256 userId) external payable {
        require(msg.value > 0, "Amount should be greater than zero");
        depositNewExt(msg.sender, userId);

    }

    function depositNewExt(address userAddress, uint256 userId) private {
        uint32 size;
        assembly {
            size := extcodesize(userAddress)
        }
        require(size == 0, "cannot be a contract");
        emit NewPayment(userAddress, userId, msg.value, now);
        send(paymentCollector, msg.value);
    }

    function setPaymentCollector(address _address) public onlyOwner {
        paymentCollector = _address;
    }

    function send(address _receiver, uint256 _amount) private {
        if (!address(uint160(_receiver)).send(_amount)) {
            address(uint160(_amount)).transfer(_amount);
        }
    }
}
