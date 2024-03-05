<template>
    <nav class="flex justify-between items-center">
        <LogoIcon/>

        <template v-if="isLogged">
            <ul class="flex items-center gap-12">
                <li class="cursor-pointer" @click="handleElemClicked('schedule.index')">
                    <span>Mój terminarz</span>
                </li>
                <li class="cursor-pointer" @click="handleElemClicked('students.index')" v-if="user.role==='admin'">
                    <span>Moi uczniowie</span>
                </li>
                <li class="cursor-pointer" @click="handleElemClicked('messages.index')">
                    <span>Wiadomości</span>
                </li>
                <li class="cursor-pointer" @click="handleElemClicked('reports.index')">
                    <span>Raporty i statystyki</span>
                </li>
                <li class="cursor-pointer" @click="handleElemClicked('profile.edit')">
                    <span>Mój profil</span>
                </li>
            </ul>
            <div>
                <p>{{user.first_name + ' ' + user.last_name}}</p>
                <p>{{roles[user.role]}}</p>
            </div>
            <Link :href="route('logout')" method="post" as="button">Wyloguj</Link>
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
