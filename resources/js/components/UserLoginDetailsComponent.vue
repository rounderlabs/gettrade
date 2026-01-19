<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "UserLoginDetailsComponent",
    components: {Link},

    props: ['user'],
}

</script>

<template>
    <section class="section-b-space">
        <div class="custom-container">
            <div class="card-box">
                <div class="card-details">
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-semibold">Profile</h5>
                    </div>

                    <h1 class="mt-2 text-white">Name: {{user.name}}</h1>
                    <h1 class="mt-2 text-white">Login ID: {{user.username}}</h1>

                    <div class="amount-details">
                        <div class="amount w-50 text-start">
                            <div class="d-flex align-items-center justify-content-start">
                                <h5>Email</h5>
                            </div>
                            <h3 class="text-white"> {{user.email}}</h3>
                        </div>
                        <div class="amount w-50 text-end border-0">
                            <div class="d-flex align-items-center justify-content-end">
                                <h5>Mobile</h5>
                            </div>
                            <h3 class="text-white">{{user.mobile}}</h3>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</template>

<style scoped>

.card-box .card-details {
    width: 100%;
    background-image: url("/shuchack/assets-panel/assets/images/background/user-card-login.jpg");/* webpackIgnore: true */
    background-repeat: no-repeat;
    background-size: cover;
    padding: 25px 20px;
    border-radius: 15px;
}

</style>
