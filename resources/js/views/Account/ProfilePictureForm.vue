<template>
    <main class="main mainheight" style="min-height: 621.039px; margin-top: 60.2109px; padding-bottom: 200px;">

        <div class="container">
            <div id="profilePicture" class="row mb-100">

                <div class="col-12 col-md-6 col-lg-6 col-xxl-6 mb-4 ">
                    <!-- Inventory card -->
                    <div class="card border-0 bg-gradient-theme-light theme-red h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="fw-medium">
                                        <i class="bi bi-lock-fill h5 me-1 avatar avatar-40 bg-light-theme rounded me-2"></i>
                                        Update Profile Picture
                                    </h6>
                                </div>
                                <div class="col-auto">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12  mb-4 mb-md-0">
                                <!-- Form elements -->

                                <form @submit.prevent="submit" enctype="multipart/form-data">
                                    <div id="my-great-dropzone" class="dropzone py-4 mb-3 dz-clickable">
                                        <div class="dz-default dz-message">
                                            <input class="dz-button"
                                                   type="file"
                                                   @change="previewImage"
                                                   ref="profile"  required="required" />
                                        </div>
                                    </div>
                                    <img v-if="url" :src="url" style="width:100px" />
                                    <div class="col-12 text-center">
                                        <button class="btn btn-xl btn-theme" type="submit">Update Profile Picture</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>



</template>

<script>
import UserLayout from "@/layouts/UserLayouts/UserLayout.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import InputError from "@/components/InputError.vue";
import { useForm } from "@inertiajs/vue3";

export default {
    name: "ProfilePictureForm",
    components: { InputError, FontAwesomeIcon },

    layout: UserLayout,
    props: {
        profile: Object
    },
    data() {
        return {
            url: null,
        };
    },
    setup() {
        const form = useForm({
            profile_picture: null,
        });

        return { form };
    },
    methods: {
        submit() {
            if (this.$refs.profile) {
                this.form.profile_picture = this.$refs.profile.files[0];
            }
            this.form.post(route("account.update.profile.picture"));
        },
        previewImage(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
        },
    }
}
</script>

<style scoped>

</style>
