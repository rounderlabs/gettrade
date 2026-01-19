<template>
    <!-- Modal alert message -->
    <div id="modaldemo4" class="modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body tx-center pd-y-20 pd-x-20">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                        aria-hidden="true">&times;</span></button>
                    <i class="icon ion-ios-checkmark-circle-outline tx-100 tx-success lh-1 mg-t-20 d-inline-block"></i>
                    <h4 class="tx-success tx-semibold mg-b-20">Congratulations!</h4>
                    <p class="mg-b-20 mg-x-20">Quick look of your reward points</p>
                    <div class="row">
                        <div class="col">
                            <h6>Master Team RP</h6>
                            <h2 class="text-primary">{{ max_leg_points }}</h2>
                        </div>
                        <div class="col">
                            <h6>Other Team RP</h6>
                            <h2 class="text-primary">{{ other_leg_points }}</h2>
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
<!--    <div class="modal-backdrop show"></div>-->
</template>

<script>
import {onMounted} from "vue";

export default {
    name: "RewardPointPopup",
    props: {
        max_leg_points: String,
        other_leg_points: String,
    },
    setup(props){
        onMounted(() => {
            $('#modaldemo4').modal('toggle')
        })
    }
}
</script>

<style scoped>
.btn {
    display: initial !important;
}
</style>
