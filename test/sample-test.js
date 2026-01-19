const {expect} = require("chai");
const {ethers} = require("hardhat");

describe("Greeter", function () {
    it("Should have 7 crore tokens", async function () {
        const Smartoken = await ethers.getContractFactory("Smartoken");
        const smartoken = await Smartoken.deploy();
        await smartoken.deployed();
        const [owner] = await ethers.getSigners();
        let bal = await smartoken.balanceOf(owner.address);
        // console.log(bal.toString())

        expect(bal.toString()).to.equal("7000000000000000");
        expect(await smartoken.decimals()).to.equal(8);
        //
        // const setGreetingTx = await greeter.setGreeting("Hola, mundo!");
        //
        // // wait until the transaction is mined
        // await setGreetingTx.wait();
        //
        // expect(await greeter.greet()).to.equal("Hola, mundo!");
    });
});
