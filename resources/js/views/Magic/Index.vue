<template>

    <section class="section-b-space">
        <div class="custom-container">
            <div class="news-details text-center w-100 ">
                <img alt="img1" class="img-fluid news-img magic"
                     src="/sunlotusinfra/assets-panel/assets/images/magic.gif">
                <div class="news-content text-center">
                    <h2 class="mt-4 mb-4">Rapid Magic Bonus</h2>
                    <div class="news-description">
                        <p>Achieve $500 or more in personal or direct team business on any single day of the week, and
                            unlock your exclusive Magic Bonus!</p>

                        <p>Every Next Day You can Claim Your Magic Income </p>

                        <!--                        <div class="investment-banner">-->
                        <!--                            <div class="investment-content">-->
                        <!--                                <h5>How to earn more cash so you may invest more</h5>-->
                        <!--                                <h6>Earn 50% with this investment.</h6>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                    </div>
                </div>

            </div>
            <button class="btn theme-btn w-100">Claim Now</button>
        </div>
    </section>


</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: "Index",
    components: {Link},
    metaInfo: {title: 'Magic'},
    layout: UserLayout,
    props: {}
}
</script>

<style scoped>

.successfully-pay {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    height: 40vh;
}

.magic {
    width: 50%;
}

@media screen and (min-width: 600px) {
    /* Styles for viewports 600px and wider */
    .magic {
        width: 20%;
    }
}
</style>
