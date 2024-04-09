<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import TextField from "@/Components/Inputs/TextField.vue";
import Layout from "@/Layouts/Layout.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import {ref} from "vue";
import {useMainStore} from "@/Store/mainStore.js";
import Btn from "@/Components/Buttons/Btn.vue";

defineProps({
    status: {
        type: String,
    },
});

const form = ref({
    email: '',
});

// const submit = () => {
//     form.post(route('password.email'));
// };

const isBtnLoader = ref(false);
const store = useMainStore();

function submit() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.post(route('password.email'), form.value)
            .then(response => {
                store.showSnackbar('Link wysłany, sprawdź skrzynkę mailową!', 'success', 10000);
                // if (response.data.status === 'ok') {
                // }
            })
            .catch(error => {
                // Obsługa błędu
                console.log(error.response.data.errors)
                if (error.response.status === 422) {
                    store.setErrors(error.response.data.errors);
                } else {
                    console.log(error)
                }
            }).finally(() => {
            store.setIsLock(false);
            isBtnLoader.value=false;
        })}
}
</script>

<template>
    <Layout :isLogged="$page.props.auth.user!==null">
        <div class="max-w-[500px] mx-auto">
            <TitleComponent desc="Przypomnij hasło" :isSearch="false" />
            <div>
                <TextField
                    type="text"
                    errorName="email"
                    v-model="form.email"
                    label="Email"
                />
            </div>
            <Btn btnType="primary" class="w-max mt-4" @click="submit" :isLoader="isBtnLoader">
                Wyślij link do zmiany hasła
            </Btn>
        </div>
    </Layout>
<!--    <GuestLayout>-->
<!--        <Head title="Forgot Password" />-->

<!--        <div class="mb-4 text-sm text-gray-600">-->
<!--            Forgot your password? No problem. Just let us know your email address and we will email you a password reset-->
<!--            link that will allow you to choose a new one.-->
<!--        </div>-->

<!--        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">-->
<!--            {{ status }}-->
<!--        </div>-->

<!--        <form @submit.prevent="submit">-->
<!--            <div>-->
<!--                <InputLabel for="email" value="Email" />-->

<!--                <TextInput-->
<!--                    id="email"-->
<!--                    type="email"-->
<!--                    class="mt-1 block w-full"-->
<!--                    v-model="form.email"-->
<!--                    required-->
<!--                    autofocus-->
<!--                    autocomplete="username"-->
<!--                />-->

<!--                <InputError class="mt-2" :message="form.errors.email" />-->
<!--            </div>-->

<!--            <div class="flex items-center justify-end mt-4">-->
<!--                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">-->
<!--                    Email Password Reset Link-->
<!--                </PrimaryButton>-->
<!--            </div>-->
<!--        </form>-->
<!--    </GuestLayout>-->
</template>
