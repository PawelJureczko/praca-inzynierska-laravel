<template>
    <nav class="flex justify-between items-center">
        <LogoIcon />

        <ul class="flex items-center gap-12">
            <li>
                <span>Mój terminarz</span>
            </li>
            <li>
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

    </nav>
</template>

<script setup>
import LogoIcon from "@/Components/Icons/LogoIcon.vue";
import {Link} from "@inertiajs/vue3";

</script>

<style scoped>

</style>
