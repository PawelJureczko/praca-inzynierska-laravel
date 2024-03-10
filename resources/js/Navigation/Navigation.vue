<template>
    <nav class="fixed xl:flex top-0 h-[64px] container bg-white flex justify-between items-center z-[99]">
        <LogoIcon/>
        <template v-if="isLogged">
            <ul class="fixed xl:static w-screen xl:w-max h-[calc(100vh_-_64px)] xl:h-[64px] bg-[#FEFEFE] flex flex-col xl:flex-row xl:gap-8 items-start xl:items-center left-0 z-[99] transition-[bottom] px-4 md:px-[calc((100vw_-_672px)/2)] lg:px-[calc((100vw_-_960px)/2)] xl:px-0 pt-8 xl:pt-0"
                :class="isMobileOpen ? 'bottom-[0]' : '-bottom-[100vh]'"
            >
                <li class="border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0 xl:fixed xl:top-[12px] xl:right-[calc((100vw_-_1200px)/2)] xxl:right-[calc((100vw_-_1672px)/2)] xl:w-max" @click="isUserDetailsOpen=!isUserDetailsOpen">
                    <div class="relative">
                        <div class="hidden w-10 h-10 border border-dark_grey rounded-full xl:flex  items-center justify-center cursor-pointer">
                            <AvatarIcon />
                        </div>
                        <div class="xl:absolute xl:px-2 top-10 right-0 h-max overflow-hidden z-[2] bg-white xl:shadow-lg transition-[max-height]" :class="isUserDetailsOpen ? 'xl:max-h-[300px]' : 'xl:max-h-0'">
                            <div class="flex items-center gap-2 py-2 xl:mt-4 xl:px-2">
                                <AvatarIcon />
                                <div>
                                    <p class="text-[12px] leading-[16px] font-semibold">{{roles[user.role]}}</p>
                                    <p class="text-[14px]">{{user.first_name + ' ' + user.last_name}}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 py-2 cursor-pointer xl:hover:bg-light_grey xl:px-2 xl:rounded-lg" @click="handleElemClicked('profile.edit')">
                                <SettingsIcon />
                                <span>Ustawienia</span>
                            </div>
                            <Link class="flex items-center gap-2 py-2 xl:mb-4 xl:hover:bg-light_grey xl:px-2 xl:w-full xl:rounded-lg" :href="route('logout')" method="post" as="button">
                                <LogoutIcon />
                                Wyloguj
                            </Link>
                        </div>
                        <div class="hidden xl:block xl:fixed w-screen h-screen left-0 top-0 bg-[transparent] z-[1]" v-if="isUserDetailsOpen"></div>
                    </div>
                </li>
                <li class="flex items-center gap-2 cursor-pointer border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0" @click="handleElemClicked('schedule.index')">
                    <CalendarIcon />
                    <span class="whitespace-nowrap">Mój terminarz</span>
                </li>
                <li class="flex items-center gap-2  cursor-pointer border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0" @click="handleElemClicked('students.index')" v-if="user.role==='teacher'">
                    <StudentsIcon />
                    <span class="whitespace-nowrap">Moi uczniowie</span>
                </li>
                <li class="flex items-center gap-2 cursor-pointer border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0" @click="handleElemClicked('reports.index')">
                    <StatisticsIcon />
                    <span class="whitespace-nowrap">Raporty i statystyki</span>
                </li>
            </ul>
        </template>
        <template v-else>
            <Link :href="route('login')" as="button">
                Zaloguj</Link>
        </template>
        <div class="flex items-center gap-4 xl:mr-[56px]">
            <div class="w-10 h-10 flex items-center justify-center rounded-full border border-dark_grey cursor-pointer transition-[background-color] xl:hover:bg-light_grey" @click="handleElemClicked('messages.index')">
                <MessageIcon />
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-full border border-dark_grey cursor-pointer transition-[background-color] xl:hover:bg-light_grey" @click="handleElemClicked('notifications.index')">
                <NotificationIcon />
            </div>
            <Hamburger class="xl:hidden" v-model="isMobileOpen"/>
        </div>

    </nav>
</template>

<script setup>
import LogoIcon from "@/Components/Icons/LogoIcon.vue";
import {Link, router} from "@inertiajs/vue3";
import {ref} from "vue";
import Hamburger from "@/Navigation/Hamburger.vue";
import NotificationIcon from "@/Components/Icons/NotificationIcon.vue";
import LogoutIcon from "@/Components/Icons/LogoutIcon.vue";
import MessageIcon from "@/Components/Icons/MessageIcon.vue";
import SettingsIcon from "@/Components/Icons/SettingsIcon.vue";
import AvatarIcon from "@/Components/Icons/AvatarIcon.vue";
import CalendarIcon from "@/Components/Icons/CalendarIcon.vue";
import StudentsIcon from "@/Components/Icons/StudentsIcon.vue";
import StatisticsIcon from "@/Components/Icons/StatisticsIcon.vue";

const props = defineProps({
    isLogged: {
        type: Boolean,
        default: false
    },
    user: {
        type: Object,
        default: null
    }
})

const isMobileOpen = ref(false);
const isUserDetailsOpen = ref(false);

const roles = ref({
    'teacher': 'Nauczyciel',
    'student': 'Uczeń'
})

function handleElemClicked(path) {
    router.visit(route(path));
}

</script>

<style scoped>

</style>
