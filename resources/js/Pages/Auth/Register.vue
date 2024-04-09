<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import TextField from "@/Components/Inputs/TextField.vue";
import TitleComponent from "@/Components/Views/TitleComponent.vue";
import Radiobox from "@/Components/Inputs/Radiobox.vue";
import {ref} from "vue";
import Btn from "@/Components/Buttons/Btn.vue";
import {useMainStore} from "@/Store/mainStore.js";

const form = ref({
    first_name: 'asd',
    last_name: 'asd',
    email: 'palobo@o2.pl',
    phone_number: '123123123',
    password: 'password',
    password_confirmation: 'password',
    role: 'admin',
});

const isBtnLoader = ref(false);

const store = useMainStore();

// const submit = () => {
//     form.post(route('register'), {
//         onFinish: () => form.reset('password', 'password_confirmation'),
//     });
// };

function submit() {
    if (store.getIsLock === false) {
        store.setIsLock(true);
        isBtnLoader.value = true;
        store.clearErrors();
        axios.post(route('register'), form.value)
            .then(response => {
                    router.visit(route('login'));
                    store.showSnackbar('Potwierdź konto klikając w link w mailu', 'success', 10000);
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
            <TitleComponent desc="Rejestracja" :isSearch="false" />
            <div>
                <TextField
                    type="text"
                    errorName="first_name"
                    v-model="form.first_name"
                    label="Imię"
                />

            </div>

            <div class="mt-4">
                <TextField
                    type="text"
                    errorName="last_name"
                    v-model="form.last_name"
                    label="Nazwisko"/>
            </div>
            <div class="mt-4">
                <TextField
                    type="text"
                    errorName="email"
                    v-model="form.email"
                    label="E-mail"/>
            </div>
            <div class="mt-4">
                <TextField
                    type="text"
                    errorName="phone_number"
                    v-model="form.phone_number"
                    label="Numer telefonu"/>
            </div>
            <div class="mt-4">
                <p class="text-base font-medium">Typ konta</p>
                <div class="flex items-center gap-4 mt-2">
                    <Radiobox label="Nauczyciel" :selectedValue="form.role" value="admin" @click="form.role='admin'"/>
                    <Radiobox label="Uczeń" :selectedValue="form.role" value="user" @click="form.role='user'"/>
                </div>
            </div>
            <input type="password" class="w-0 h-0 p-0 absolute pointer-events-none opacity-0" tabindex="-1"/>
            <div class="mt-4">
                <TextField
                    type="password"
                    errorName="password"
                    v-model="form.password"
                    label="Hasło"/>
            </div>
            <div class="mt-4">
                <TextField
                    type="password"
                    errorName="password_confirmation"
                    v-model="form.password_confirmation"
                    label="Powtórz hasło"/>
            </div>

            <div class="mt-8 flex items-center justify-between">
                <Btn btnType="primary" class="w-max" @click="submit" :isLoader="isBtnLoader">
                    Zarejestruj
                </Btn>
                <Btn btnType="secondary" class="w-max" @click="$inertia.visit(route('login'))">
                    Wróć do logowania
                </Btn>
            </div>
        </div>
    </Layout>

<!--    <GuestLayout>-->
<!--        <Head title="Register" />-->

<!--        <form @submit.prevent="submit">-->
<!--            <div>-->
<!--                <InputLabel for="first_name" value="First name" />-->

<!--                <TextInput-->
<!--                    id="first_name"-->
<!--                    type="text"-->
<!--                    class="mt-1 block w-full"-->
<!--                    v-model="form.first_name"-->
<!--                    autofocus-->
<!--                    autocomplete="first_name"-->
<!--                />-->

<!--                <InputError class="mt-2" :message="form.errors.first_name" />-->
<!--            </div>-->

<!--            <div class="mt-4">-->
<!--                <InputLabel for="last_name" value="Last name" />-->

<!--                <TextInput-->
<!--                    id="name"-->
<!--                    type="text"-->
<!--                    class="mt-1 block w-full"-->
<!--                    v-model="form.last_name"-->
<!--                    autofocus-->
<!--                    autocomplete="last_name"-->
<!--                />-->

<!--                <InputError class="mt-2" :message="form.errors.last_name" />-->
<!--            </div>-->

<!--            <div class="mt-4">-->
<!--                <InputLabel for="email" value="Email" />-->

<!--                <TextInput-->
<!--                    id="email"-->
<!--                    type="email"-->
<!--                    class="mt-1 block w-full"-->
<!--                    v-model="form.email"-->
<!--                    autocomplete="username"-->
<!--                />-->

<!--                <InputError class="mt-2" :message="form.errors.email" />-->
<!--            </div>-->

<!--            <div class="mt-4">-->
<!--                <InputLabel for="phone_number" value="Phone number" />-->

<!--                <TextInput-->
<!--                    id="phone_number"-->
<!--                    type="text"-->
<!--                    class="mt-1 block w-full"-->
<!--                    v-model="form.phone_number"-->
<!--                />-->

<!--                <InputError class="mt-2" :message="form.errors.phone_number" />-->
<!--            </div>-->

<!--            <div>-->
<!--                <InputLabel for="role" value="Role" />-->
<!--                <div>-->
<!--                    <p>admin</p>-->
<!--                    <input type="radio" for="role" value="admin" v-model="form.role"/>-->
<!--                </div>-->
<!--                <div>-->
<!--                    <p>uzytkownik</p>-->
<!--                    <input type="radio" for="role" value="user" v-model="form.role"/>-->
<!--                </div>-->
<!--            </div>-->

<!--            <div class="mt-4">-->
<!--                <InputLabel for="password" value="Password" />-->

<!--                <TextInput-->
<!--                    id="password"-->
<!--                    type="password"-->
<!--                    class="mt-1 block w-full"-->
<!--                    v-model="form.password"-->
<!--                    autocomplete="new-password"-->
<!--                />-->

<!--                <InputError class="mt-2" :message="form.errors.password" />-->
<!--            </div>-->

<!--            <div class="mt-4">-->
<!--                <InputLabel for="password_confirmation" value="Confirm Password" />-->

<!--                <TextInput-->
<!--                    id="password_confirmation"-->
<!--                    type="password"-->
<!--                    class="mt-1 block w-full"-->
<!--                    v-model="form.password_confirmation"-->
<!--                    autocomplete="new-password"-->
<!--                />-->

<!--                <InputError class="mt-2" :message="form.errors.password_confirmation" />-->
<!--            </div>-->

<!--            <div class="flex items-center justify-end mt-4">-->
<!--                <Link-->
<!--                    :href="route('login')"-->
<!--                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"-->
<!--                >-->
<!--                    Already registered?-->
<!--                </Link>-->

<!--                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">-->
<!--                    Register-->
<!--                </PrimaryButton>-->
<!--            </div>-->
<!--        </form>-->
<!--    </GuestLayout>-->
</template>
