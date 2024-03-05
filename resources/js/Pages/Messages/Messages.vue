<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <p>Wiadomości</p>
    </Layout>
</template>

<script setup>

import Layout from "@/Layouts/Layout.vue";
</script>

<style scoped>

</style>
