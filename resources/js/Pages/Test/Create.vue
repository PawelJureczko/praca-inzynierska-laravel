<template>
    <p>Create</p>

    <p> {{form.name + ' ' + form.surname}}</p>
    <input v-model="form.name" />
    <input v-model="form.surname" />

    <button @click="save">save</button>
</template>

<script setup>
import {ref} from "vue";

const form = ref({
    name: '',
    surname: ''
});

function save() {
    axios.post(route('test.store'), form.value)
        .then(response => {
            // Obsługa odpowiedzi z backendu
            console.log(response.data);
        })
        .catch(error => {
            // Obsługa błędu
            console.error(error);
        });
}
</script>

<style scoped>

</style>
