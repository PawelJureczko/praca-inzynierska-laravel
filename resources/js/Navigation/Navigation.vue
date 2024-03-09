<template>
    <nav class="fixed xl:flex top-0 h-[64px] container bg-white flex justify-between items-center z-[99]">
        <LogoIcon/>

        <Hamburger class="xl:hidden" v-model="isMobileOpen"/>
        <template v-if="isLogged">
            <ul class="fixed xl:static w-screen xl:w-full h-[calc(100vh_-_64px)] xl:h-[64px] bg-[#FEFEFE] flex flex-col xl:flex-row items-start xl:items-center left-0 z-[99] transition-[bottom] px-4 md:px-[calc((100vw_-_672px)/2)] lg:px-[calc((100vw_-_960px)/2)] xl:px-0 pt-8 xl:pt-0"
                :class="isMobileOpen ? 'bottom-[0]' : '-bottom-[100vh]'"
            >
                <li class="cursor-pointer border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0" @click="handleElemClicked('schedule.index')">
                    <span>Mój terminarz</span>
                </li>
                <li class="cursor-pointer border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0" @click="handleElemClicked('students.index')" v-if="user.role==='teacher'">
                    <span>Moi uczniowie</span>
                </li>
                <li class="cursor-pointer border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0" @click="handleElemClicked('messages.index')">
                    <span>Wiadomości</span>
                </li>
                <li class="cursor-pointer border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0" @click="handleElemClicked('reports.index')">
                    <span>Raporty i statystyki</span>
                </li>
                <li class="cursor-pointer border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0" @click="handleElemClicked('profile.edit')">
                    <span>Mój profil</span>
                </li>
                <li class="cursor-pointer border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0" @click="handleElemClicked('notifications.index')">
                    <span>Powiadomienia</span>
                </li>
                <li class="cursor-pointer border-b border-[#DDDDDD] xl:border-none w-full py-6 xl:py-0">
                    <div>
                        <p>{{user.first_name + ' ' + user.last_name}}</p>
                        <p>{{roles[user.role]}}</p>
                    </div>
                </li>
                <li class="cursor-pointer py-6 xl:py-0">
                    <Link :href="route('logout')" method="post" as="button">Wyloguj</Link>
                </li>
            </ul>
        </template>
        <template v-else>
            <Link :href="route('login')" as="button">Zaloguj</Link>
        </template>

    </nav>
</template>

<script setup>
import LogoIcon from "@/Components/Icons/LogoIcon.vue";
import {Link, router} from "@inertiajs/vue3";
import {ref} from "vue";
import Hamburger from "@/Navigation/Hamburger.vue";

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

const roles = ref({
    'admin': 'Nauczyciel',
    'user': 'Uczeń'
})

function handleElemClicked(path) {
    router.visit(route(path));
}

</script>

<style scoped>

</style>
