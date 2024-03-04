<template>
    <nav class="flex justify-between items-center">
        <LogoIcon/>

        <template v-if="isLogged">
            <ul class="flex items-center gap-12">
                <li class="cursor-pointer" @click="handleElemClicked('schedule.index')">
                    <span>Mój terminarz</span>
                </li>
                <li class="cursor-pointer" @click="handleElemClicked('students.index')" v-if="userType==='admin'">
                    <span>Moi uczniowie</span>
                </li>
                <li>
                    <span>Wiadomości</span>
                </li>
                <li>
                    <span>Raporty i statystyki</span>
                </li>
                <li>
                    <span>Mój profil</span>
                </li>
            </ul>
            <Link :href="route('logout')" method="post">Wyloguj</Link>
        </template>
        <template v-else>
            <Link :href="route('login')">Zaloguj</Link>
        </template>

    </nav>
</template>

<script setup>
import LogoIcon from "@/Components/Icons/LogoIcon.vue";
import {Link, router} from "@inertiajs/vue3";

const props = defineProps({
    isLogged: {
        type: Boolean,
        default: false
    },
    userType: {
        type: String,
        default: ''
    }
})

function handleElemClicked(path) {
    router.visit(route(path));
}

</script>

<style scoped>

</style>
