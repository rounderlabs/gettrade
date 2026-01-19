<template>
    <div class="row">
        <div class="col-lg-6 col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon1 mt-2">
                                <i class="fe fe-users text-success tx-40"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mt-0 text-right">
                                <span class="text-muted ">Team</span>
                                <h3 class="mb-0">{{ team.left + team.right }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon1 mt-2">
                                <i class="fe fe-user text-pink tx-40"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mt-0 text-right">
                                <span class="text-muted ">Team Left</span>
                                <h3 class="mb-0">{{ team.left }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon1 mt-2">
                                <i class="fe fe-user text-purple tx-40"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mt-0 text-right">
                                <span class="text-muted ">Team Right</span>
                                <h3 class="mb-0">{{ team.right }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 col-md-6 col-sm-6 col-12">
            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="icon1 mt-2">
                                <i class="fe fe-user text-danger tx-40"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mt-0 text-right">
                                <span class="text-muted ">Team Direct</span>
                                <h3 class="mb-0">{{ team.direct }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TeamWidget",
    props: {
        team: Object
    }
}
</script>

<style scoped>

</style>
