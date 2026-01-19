<script setup>
import { onMounted } from "vue";
import { Link } from "@inertiajs/vue3";

// Import Swiper core and required modules
import Swiper from "swiper";
import "swiper/css";
import Navigation from "swiper/css/navigation";
import Pagination from "swiper/css/pagination";

onMounted(() => {
    // Register Swiper modules
    Swiper.use([Navigation, Pagination]);

    // Banner Swiper
    new Swiper(".banner", {
        slidesPerView: 1.1,
        spaceBetween: 15,
        loop: true,
    });

    // Card slider
    new Swiper(".card-slider", {
        grabCursor: true,
        spaceBetween: 0,
        slidesPerView: 3,
        loop: true,
    });

    // Portfolio Swiper
    new Swiper(".portfolio", {
        slidesPerView: 1.4,
        spaceBetween: 15,
        loop: true,
    });

    // Onboarding Swiper
    new Swiper(".onboarding", {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: false,
        navigation: {
            nextEl: ".onboarding-next",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        keyboard: true,
    });
});
</script>

<template>
    <section>
        <div class="custom-container">
            <div class="swiper banner">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <Link :href="route('dashboard')">
                            <img
                                alt="banner2"
                                class="img-fluid banner-img"
                                src="/shuchack/assets-panel/assets/images/banner/banner2.png"
                            />
                        </Link>
                    </div>
                    <div class="swiper-slide">
                        <Link :href="route('dashboard')">
                            <img
                                alt="banner3"
                                class="img-fluid banner-img"
                                src="/shuchack/assets-panel/assets/images/banner/banner1.png"
                            />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.banner-img {
    border-radius: 12px;
    width: 100%;
    object-fit: cover;
}
</style>
