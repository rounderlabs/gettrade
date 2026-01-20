<template>
    <section>
        <div class="custom-container">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="news-update-box">
                        <div class="d-flex align-items-center gap-3">
                            <!-- QR Image -->
                            <a @click="copyRef()">
                                <img :src="`data:image/svg+xml;base64,${ref_qr}`"
                                     class="img-fluid"
                                     style="background: #ffffff; padding: 5px;"
                                     alt="Scan">
                            </a>

                            <!-- Copy Button -->
                            <div class="news-update-content">
                                <h3>Scan To Refer Someone</h3>
                                <div class="news-writer">
                                    <button class="btn theme-btn" @click="copyRef()">Copy Referral Link</button>
                                </div>
                            </div>
                        </div>

                        <!-- Share Buttons -->
                        <h5 class="dark-text mt-3">Share Or download QR</h5>
                        <div class="d-flex align-items-center gap-3 mt-2">

                            <button
                                @click="downloadAndShare('whatsapp')"
                                class="btn btn-dark d-flex align-items-center gap-2 mt-0"
                            >
                                <img src="/user-panel/assets-panel/assets/images/svg/whatsapp.svg"
                                     width="20"
                                     alt="WA" />
                            </button>

                            <button
                                @click="downloadAndShare('telegram')"
                                class="btn btn-dark d-flex align-items-center gap-2 mt-0"
                            >
                                <img src="/user-panel/assets-panel/assets/images/svg/telegram.svg"
                                     width="20"
                                     alt="TG" />
                            </button>

                            <a
                                :href="qrCodeData"
                                download="referral-qr.png"
                                class="btn btn-dark d-flex align-items-center gap-2 mt-0"
                            >
                                <VueFeather type="download"></VueFeather>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import QRCode from "qrcode";
import { toast } from "@/utils/toast";
import VueFeather from "vue-feather";

const props = defineProps({
    subscriptions: Array,
    ref_qr: String,
    user:Object,
});


const page = usePage();
const user = props.user;
const referralLink = `${window.location.origin}/register/${user.username}`;

const sharePageUrl = `${window.location.origin}/register/${user.username}`;
const qrCodeData = ref("");

// Generate QR Code
onMounted(async () => {
    qrCodeData.value = await QRCode.toDataURL(referralLink, { width: 250 });
});

// Copy referral link
function copyRef() {
    navigator.clipboard.writeText(referralLink);
    toast("Referral Link Copied!", "success");
}

// Download and share
async function downloadAndShare(platform) {
    const link = document.createElement("a");
    link.href = qrCodeData.value;
    link.download = `${user.username}-referral-qr.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    const message = encodeURIComponent(`🌸 Join The Shuchak using my referral:\n${sharePageUrl}`);

    if (platform === "whatsapp") {
        window.open(`https://wa.me/?text=${message}`, "_blank");
    } else if (platform === "telegram") {
        window.open(`https://t.me/share/url?url=${sharePageUrl}&text=${message}`, "_blank");
    }
}
</script>


<style scoped>
.news-update-box {
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
.btn img {
    vertical-align: middle;
}
</style>
