var EccGateway = artifacts.require("EccGateway.sol");

module.exports = function (deployer) {
    deployer.deploy(EccGateway, process.env.TRON_FUND_WALLET_ADDRESS);
};
