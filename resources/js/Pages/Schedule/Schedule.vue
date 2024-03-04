<template>
    <Layout :isLogged="$page.props.auth.user!==null" :userType="$page.props.auth.user.role">
        <p>Mój plan</p>
    </Layout>
</template>

<script setup>

import Layout from "@/Layouts/Layout.vue";
</script>

<style scoped>

</style>
