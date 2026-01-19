<template>
    <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
        <!-- BEGIN: Personal Information -->
        <div class="intro-y box lg:mt-5">
            <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                <h2 class="font-medium text-base mr-auto">
                    Personal Information
                </h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-12 gap-5">
                    <div class="col-span-12 xl:col-span-6">
                        <div>
                            <label>Username</label>
                            <div class="input w-full border bg-gray-100 cursor-not-allowed mt-2">{{ $page.props.auth.user.username }}</div>
                        </div>
                        <div class="mt-3">
                            <label>Name</label>
                            <div class="input w-full border bg-gray-100 cursor-not-allowed mt-2">{{ $page.props.auth.user.name }}</div>
                        </div>
                        <div class="mt-3">
                            <label>Email</label>
                            <div class="input w-full border bg-gray-100 cursor-not-allowed mt-2">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <div class="mt-3">
                            <label>Phone Number</label>
                            <div class="input w-full border bg-gray-100 cursor-not-allowed mt-2">{{ $page.props.auth.user.mobile }}</div>
                        </div>
                    </div>
                    <div class="col-span-12 xl:col-span-6">
                        <div>
                            <label>My Referral code</label>
                            <div class="input w-full border bg-gray-100 cursor-not-allowed mt-2">{{ $page.props.auth.user.ref_code }}</div>
                        </div>
                        <div class="mt-3">
                            <label>Joined On</label>
                            <div class="input w-full border bg-gray-100 cursor-not-allowed mt-2">{{ $page.props.auth.user.created_at }}</div>
                        </div>
                        <div class="mt-3">
                            <label>Sponsor ID</label>
                            <div class="input w-full border bg-gray-100 cursor-not-allowed mt-2">{{ $page.props.profile.sponsor_name}}</div>
                        </div>
                        <div class="mt-3">
                            <label>Country</label>
                            <div class="input w-full border bg-gray-100 cursor-not-allowed mt-2">{{  $page.props.profile.country.name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Personal Information -->
    </div>

</template>

<script>

export default {
    name: "ProfileRight",

    props: {
        profile: Object,
        myData:Object
    },
}
</script>

<style scoped>

</style>
