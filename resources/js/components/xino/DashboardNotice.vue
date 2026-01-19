<template>
    <div id="modaldemo5" class="modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body tx-center pd-y-20 pd-x-20">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                        aria-hidden="true">&times;</span></button>
                    <i class="icon ion-ios-checkmark-circle tx-100 tx-info lh-1 mg-t-20 d-inline-block"></i>
                    <h4 class="tx-success tx-semibold mg-b-20">Important Notice!</h4>
                    <p class="mg-b-20 mg-x-20"></p>
                    <div class="row">
                        <div class="col">
                            <h2 >Congratulations to all smarties about <b class="text-primary">WWW.SMRTPRO.IO</b> , it
                                will live within 72 hours. Best wishes to all and happy earning..</h2>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-12 text-center">
                            <button aria-label="Close" class="btn ripple btn-success pd-x-25" data-dismiss="modal"
                                    type="button">Continue
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted} from "vue";

export default {
    name: "DashboardNotice",
    setup() {
        onMounted(() => {
            $('#modaldemo5').modal('toggle')
        })
    }
}
</script>

<style scoped>
.btn {
    display: initial !important;
}
</style>
