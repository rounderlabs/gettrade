<template>
    <div class="row row-sm">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-header border-bottom">
                    <h3>Crypto Price</h3>
                </div>
                <div class="card-body">
                    <div class="">
                        <form @submit.prevent="updateToken">
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label
                                            class="form-label mt-2 font-weight-semibold mt-2">Token Price</label>
                                    </div>

                                    <div class="col-md-9">

                                        <input type="text" class="mh form-control" placeholder="Token Price"
                                               v-model="tokenForm.price_in_usd">
                                        <input type="hidden" v-model="tokenForm.id" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9 text-right">
                                        <loading-button :disabled="tokenForm.processing">
                                            Update <i class="fa fa-spin fa-spinner" v-if="tokenForm.processing"></i>
                                        </loading-button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingButton from "@/components/LoadingButton";
import {useForm} from "@inertiajs/vue3";
import {toast} from "@/utils/toast";
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue";

export default {
    name: "Token",
    components: {LoadingButton},
    layout: MainAdminLayout,
    props: {
        CryptoPrice: Object
    },
    setup(props) {
        const tokenForm = useForm({
            crypto_id: props.CryptoPrice.id,
            price_in_usd: props.CryptoPrice.price_in_usd
        })

        function updateToken() {
            tokenForm.post(route('admin.token.store'), {
                onSuccess: () => {
                    toast('Token price successfully updated !')
                },
                onError: errors => {
                    for (const [key, value] of Object.entries(errors)) {
                        toast(value, 'danger')
                    }
                }
            })
        }

        return {tokenForm, updateToken}
    }
}
</script>

<style scoped>

</style>
