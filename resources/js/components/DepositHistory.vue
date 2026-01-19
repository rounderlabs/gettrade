<template>
    <div class="card overflow-hidden">
        <div class="card-body">

            <div class="">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-10">Deposit History</h4> <i
                    class="mdi mdi-dots-horizontal text-gray"></i></div>
                <p class="tx-12 tx-gray-500 mb-2">A cryptocurrency is a new form of digital
                    asset based on a network that is distributed across a large number of
                    computers. This decentralized structure allows them to exist outside the
                    control of governments and central authorities.<a href="">Learn more</a></p>
            </div>
            <div class="table-responsive market-values">
                <table
                    class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Icon</th>
                        <th>Price</th>
                        <th>% 24h</th>
                        <th>Market Cap</th>
                        <th>CMGR/Month</th>
                        <th>Exchange</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-bottom">
                        <td>Ethereum</td>
                        <td><i class="cf cf-eth tx-22"></i></td>
                        <td>$966.61</td>
                        <td class="text-red">-6%</td>
                        <td class="text-right">$95,270,125,036</td>
                        <td>22.62% / 29</td>
                        <td><a href="#" class="btn btn-sm btn-success">Transfer Now</a></td>
                    </tr>
                    <tr class="border-bottom">
                        <td>Bitcoin</td>
                        <td><i class="cf cf-btc tx-22 text-orange"></i></td>
                        <td>$10513.00</td>
                        <td class="text-red">-7%</td>
                        <td class="text-right">$179,470,305,923</td>
                        <td>8.11% / 57</td>
                        <td><a href="#" class="btn btn-sm btn-success">Transfer Now</a></td>
                    </tr>
                    <tr class="border-bottom">
                        <td>NEM</td>
                        <td><i class="cf cf-xem tx-20 text-teal"></i></td>
                        <td>$1547.00</td>
                        <td class="text-red">-11%</td>
                        <td class="text-right">$26,720,210,956</td>
                        <td>21.30% / 6</td>
                        <td><a href="#" class="btn btn-sm btn-success">Transfer Now</a></td>
                    </tr>
                    <tr class="border-bottom">
                        <td>Ripple</td>
                        <td><i class="cf cf-xrp tx-22 text-primary"></i></td>
                        <td>$1.2029</td>
                        <td class="text-red">-11%</td>
                        <td class="text-right">$47,649,145,657</td>
                        <td>10.85% / 53</td>
                        <td><a href="#" class="btn btn-sm btn-success">Transfer Now</a></td>
                    </tr>
                    <tr class="border-bottom">
                        <td>Litecoin</td>
                        <td><i class="cf cf-ltc tx-20 text-muted"></i></td>
                        <td>$173.86</td>
                        <td class="text-red">-7%</td>
                        <td class="text-right">$9,670,920,267</td>
                        <td>6.87% / 57</td>
                        <td><a href="#" class="btn btn-sm btn-success">Transfer Now</a></td>
                    </tr>
                    <tr class="border-bottom">
                        <td>Dash</td>
                        <td><i class="cf cf-dash tx-22 text-purple"></i></td>
                        <td>$0.935049</td>
                        <td class="text-red">-11%</td>
                        <td class="text-right">$8,415,440,999</td>
                        <td>26.99% / 33</td>
                        <td><a href="#" class="btn btn-sm btn-success">Transfer Now</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
// import Overlay from "@/Components/Overlay";
export default {
    name: "DepositHistory",
    components: {}
}
</script>

<style scoped>

</style>
