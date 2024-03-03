<template>
    <div class="container">
        <header>
            <Navigation />
        </header>
        <main>
            <slot/>
        </main>
        <footer>

        </footer>
    </div>
</template>

<script setup>

import Navigation from "@/Navigation/Navigation.vue";
</script>

<style scoped>

</style>
