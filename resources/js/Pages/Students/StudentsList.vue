<template>
    <Layout :isLogged="$page.props.auth.user!==null" :user="$page.props.auth.user">
        <p>Lista uczniów</p>
        <CustomTable :headers="headers" :dimensions="dimensions">
            <div v-for="(student, index) in students" class="p-4 flex flex-col gap-2 lg:gap-0 lg:flex-row lg:items-center" :class="[index%2===0 ? 'bg-[#f7f6f2]' : 'bg-[#FFFFFF]']">
                <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="dimensions[0]">
                    <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{headers[0]}}: </span>{{ index + 1 }}</p>
                </div>
                <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="dimensions[1]">
                    <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{headers[1]}}: </span>{{ student.first_name }}</p>
                </div>
                <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="dimensions[2]">
                    <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{headers[2]}}: </span>{{ student.last_name }}</p>
                </div>
                <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="dimensions[3]">
                    <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{headers[3]}}: </span>{{ student.email }}</p>
                </div>
                <div class="flex lg:justify-center xl:px-4 lg:py-2" :class="dimensions[4]">
                    <p class="text-[12px] xl:text-base leading-[16px]"><span class="font-bold lg:hidden">{{headers[4]}}: </span>{{ student.phone_number }}</p>
                </div>
                <div class="flex xl:justify-center xl:px-4 lg:py-2" :class="dimensions[5]">
                    <Btn btnType="primary" @click="addStudent(student.id)">Zaproś</Btn>
                </div>
            </div>
        </CustomTable>
    </Layout>
</template>

<!--<tr class="bg-[#D3D3D3]">&ndash;&gt;-->
<!--                <th>LP</th>-->
<!--                <th>Imię</th>-->
<!--                <th>Nazwisko</th>-->
<!--                <th>Email</th>-->
<!--                <th>Numer Telefonu</th>-->
<!--                <th>Dodaj</th>-->
<!--            </tr>-->
<!--            <tr v-for="(student, index) in students" :class="[index%2===0 ? 'bg-[#f7f6f2]' : 'bg-[#FFFFFF]']">-->
<!--                <td>-->
<!--                    <div class="flex justify-center px-4 py-2">-->
<!--                        <p>{{ index + 1 }}</p>-->
<!--                    </div>-->
<!--                </td>-->
<!--                <td>-->

<script setup>
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";
import {useMainStore} from "@/Store/mainStore.js";
import Btn from "@/Components/Buttons/Btn.vue";
import CustomTable from "@/Components/Universal/CustomTable.vue";

const store = useMainStore();

const props = defineProps({
    users: {
        type: Array,
        default() {
            return []
        }
    }
})

const students = ref(props.users);

const dimensions = ref(
    ['lg:w-[10%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[20%]', 'lg:w-[10%]']
)
const headers = ref(
    ['LP', 'Imię', 'Nazwisko', 'Email', 'Numer telefonu', 'Dodaj']
)

function addStudent(id) {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        axios.post(route('students.invite'), {
            student_id: id,
        })
            .then(response => {
                // Obsługa sukcesu
                store.showSnackbar(response.data.message, 'success');
                students.value = response.data.students;

            })
            .catch(error => {
                // Obsługa błędu
                console.error(error);
                store.showSnackbar(error.response.data.error, 'error');
            }).finally(() => {
            store.setIsLock(false);
        });
    }
}
</script>

<style scoped lang="scss">
.parent {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-template-rows: repeat(5, 1fr);
    grid-column-gap: 0px;
    grid-row-gap: 0px;
}

.div1 {
    grid-area: 1 / 1 / 2 / 2;
}

.div2 {
    grid-area: 1 / 3 / 2 / 4;
}

.div3 {
    grid-area: 1 / 2 / 2 / 3;
}

.div4 {
    grid-area: 1 / 4 / 2 / 5;
}

.div5 {
    grid-area: 1 / 5 / 2 / 6;
}

.div6 {
    grid-area: 2 / 1 / 3 / 2;
}

.div7 {
    grid-area: 2 / 2 / 3 / 3;
}

.div8 {
    grid-area: 2 / 3 / 3 / 4;
}

.div9 {
    grid-area: 2 / 4 / 3 / 5;
}

.div10 {
    grid-area: 2 / 5 / 3 / 6;
}
</style>
