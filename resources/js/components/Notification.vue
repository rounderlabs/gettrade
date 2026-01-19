<template>
    <!-- BEGIN: Notifications -->
    <div class="intro-x dropdown mr-4 sm:mr-6">
        <div
            class="dropdown-toggle notification notification--light notification--bullet cursor-pointer"
            role="button"
            aria-expanded="false"
        >
            <BellIcon class="notification__icon dark:text-gray-300"/>
        </div>
        <div class="notification-content pt-2 dropdown-menu">
            <div
                class="notification-content__box dropdown-menu__content box dark:bg-dark-6"
            >
                <div class="notification-content__title">Notifications</div>
                <div
                    v-for="(faker, fakerKey) in $_.take($f(), 5)"
                    :key="fakerKey"
                    class="cursor-pointer relative flex items-center"
                    :class="{ 'mt-5': fakerKey }"
                >
<!--                    <div class="w-12 h-12 flex-none image-fit mr-1">-->
<!--                        <img-->
<!--                            alt="Tinker Tailwind HTML Admin Template"-->
<!--                            class="rounded-full"-->
<!--                            :src="require(`@/assets/images/${faker.photos[0]}`)"-->
<!--                        />-->
<!--                        <div-->
<!--                            class="w-3 h-3 bg-theme-20 absolute right-0 bottom-0 rounded-full border-2 border-white"-->
<!--                        ></div>-->
<!--                    </div>-->
                    <div class="ml-2 overflow-hidden">
                        <div class="flex items-center">
                            <a href="javascript:;" class="font-medium truncate mr-5">{{
                                    faker.users[0].name
                                }}</a>
                            <div
                                class="text-xs text-gray-500 ml-auto whitespace-nowrap"
                            >
                                {{ faker.times[0] }}
                            </div>
                        </div>
                        <div class="w-full truncate text-gray-600 mt-0.5">
                            {{ faker.news[0].shortContent }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Notifications -->
</template>

<script>
export default {
    name: "Notification"
}
</script>

<style scoped>

</style>
