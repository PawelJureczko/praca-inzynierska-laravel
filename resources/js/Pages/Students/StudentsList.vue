<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
    <p>Lista uczniów</p>

        <div class="flex gap-4" v-for="(student, index) in students">
            <p>{{index+1}}</p>
            <p>{{student.first_name}}</p>
            <p>{{student.last_name}}</p>
            <p>{{student.email}}</p>
            <p>{{student.phone_number}}</p>
            <button @click="addStudent(student.id)">dodaj ucznia</button>
<!--            <p>{{user}}</p>-->
        </div>
</Layout>
</template>

<script setup>
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";

const props = defineProps({
    users: {
        type: Array,
        default() {
            return []
        }
    }
})

const students = ref(props.users);

function addStudent(id) {
    axios.post(route('students.invite'), {
        student_id: id,
    })
        .then(response => {
            // Obsługa sukcesu
            console.log(response.data);
            students.value = response.data.students;

        })
        .catch(error => {
            // Obsługa błędu
            console.error(error);
        });
}
</script>

<style scoped>

</style>
