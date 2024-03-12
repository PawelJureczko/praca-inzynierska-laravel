<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <div class="flex flex-wrap items-center w-full justify-between">
            <TitleComponent :isSearch="false" desc="Pojedyncza wiadomosc"/>
            <p>Wróć</p>
        </div>
    </Layout>
</template>

<script setup>

import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Btn from "@/Components/Buttons/Btn.vue";
</script>

<style scoped>

</style>
