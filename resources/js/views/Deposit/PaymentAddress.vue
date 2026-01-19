<template>
    <section class="section-b-space">
        <div class="custom-container ">
            <div class="card-box">
                <div class="card-details">
                    <h5 class="fw-semibold text-center">Add USDT To Fund Wallet</h5>
                    <h1 class="mt-2 text-white text-center">Scan & Pay</h1>
                    <h5 class="fw-semibold mt-3 text-center">PAY {{ parseFloat(amount).toFixed(2) }}
                        {{ currency.toUpperCase() }}
                        <copy-icon class="w-4 h-4 ml-2 cursor-pointer" @click="copy(amount)"></copy-icon>
                    </h5>
                    <div class="amount-details text-center">
                        <img :src="`data:image/svg+xml;base64,${qr}`" alt="" class="p-2 m-2 bg-white">
                    </div>
                    <h6 class="text-center">
                        <a :href="explorer" class="text-white fw-light " target="_blank">{{address }}</a>
                    </h6>
                    <div class="text-center">
                        <button class="btn btn-theme mb-3" @click="copy(address)">Copy Address</button>
                        <h6><a :href="explorer" class="fw-500" target="_blank">View On Blockchain</a></h6>
                    </div>


                </div>
            </div>
        </div>
    </section>
</template>


<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import {toast} from "@/utils/toast";
import {VueClipboard} from '@soerenmartius/vue3-clipboard'
import {onMounted} from "vue";

export default {
    name: "PaymentForm",
    layout: UserLayout,
    props: {
        address: String,
        qr: String,
        currency: String,
        amount: String,
        explorer: String
    },
    components: {
        VueClipboard
    },
    setup() {

        onMounted(() => {
            window.copyText = function (value) {
                var s = document.createElement('input');
                s.value = value;
                document.body.appendChild(s);

                if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
                    s.contentEditable = true;
                    s.readOnly = false;
                    var range = document.createRange();
                    range.selectNodeContents(s);
                    var sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                    s.setSelectionRange(0, 999999);
                } else {
                    s.select();
                }
                try {
                    document.execCommand('copy');

                } catch (err) {

                }
                s.remove();
            };
        })

        function copy(text) {
            window.copyText(text)
            toast('Copied!', 'success')
        }

        return {copy}
    }
}
</script>

<style scoped>
.card-box .card-details {
    width: 100%;
    background-repeat: no-repeat;
    background-size: cover;
    padding: 25px 20px;
    border-radius: 15px;
}


.card-box .card-details .amount-details {
    display: -webkit-box;
    display: -ms-flexbox;
    display: block;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    margin-top: 50px;
}
</style>
