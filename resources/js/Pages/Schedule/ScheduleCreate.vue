<template>
    <Layout :user="$page.props.auth.user" :isLogged="$page.props.auth.user!==null" :userType="$page.props.auth.user.role">
        <TitleComponent desc="Stwórz nowe zajęcia" :isSearch="false"/>
    </Layout>
</template>

<script setup>

import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Layout from "@/Layouts/Layout.vue";
</script>

<style scoped>

</style>
