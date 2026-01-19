<template>

    <section>
        <div class="custom-container">
            <div class="title">
                <h2>My Team Stats</h2>
                <a href="#">See all</a>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="saving-plan-box">
                        <h3>Total Team</h3>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <h5 class="theme-color">{{ team.total }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="saving-plan-box">
                        <h3>Total Direct</h3>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <h5 class="theme-color">{{ team.direct }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <div class="saving-plan-box">
                        <h3>Active Team</h3>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <h5 class="theme-color">{{ team.active_total }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="saving-plan-box">
                        <h3>Active Direct</h3>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <h5 class="theme-color">{{ team.active_direct }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "TeamWidget",
    components: {Link},
    props: {
        team: Object
    }
};
</script>

<style scoped>
.saving-plan-box h3 {
    margin-top: 0px !important;
}
</style>
