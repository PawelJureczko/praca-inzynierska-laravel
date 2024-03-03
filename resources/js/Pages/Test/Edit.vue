<template>
    <p>Edit</p>
    <input v-model="form.name" />
    <input v-model="form.surname" />

    <button @click="update">update</button>
</template>

<script setup>
import {ref} from "vue";

const props = defineProps({
    record: {
        type: Object
    }
})

const form = ref({
    name: props.record.name,
    surname: props.record.surname
})

function update() {
    axios.put(route('test.update', props.record.id), form.value)
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
