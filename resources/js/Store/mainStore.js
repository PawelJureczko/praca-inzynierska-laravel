import { defineStore } from 'pinia'
import {computed, ref} from "vue";

export const useMainStore = defineStore('mainStore', () => {
    //variables
    let isLoader = ref(false);
    let isLock = ref(false);
    let snackbar = ref({
        isVisible: false,
        snackbarMessage: '',
        snackbarType: 'success'
    })
    let errors = ref({});

    //getters
    const getIsLoader = computed(() => {
        return isLoader.value;
    })
    const getIsLock = computed(() => {
        return isLock.value;
    })
    const getSnackbar = computed(() => {
        return snackbar.value
    })
    const getErrors = computed(() => {
        return errors.value;
    })

    //actions
    function setIsLock(value, showLoader = true) {
        isLock.value = value;
        if (showLoader) {
            isLoader.value = value
        }
    }

    function setErrors(val) {
        errors.value = val;
        setTimeout(() => {
            if (document.querySelector('.error')) {
                document.querySelector('.error').scrollIntoView({ behavior: "smooth", block: "center", inline: "center" });
            }
        }, 1);
    }

    function clearErrors() {
        errors.value = {}
    }

    function showSnackbar(message, type='success', timeout=3000) {
        if (!snackbar.value.isVisible) {
            snackbar.value = {
                isVisible: true,
                snackbarMessage: message,
                snackbarType: type
            }

            setTimeout(() => {
                hideSnackbar()
            }, timeout)
        }
    }

    function hideSnackbar() {
        snackbar.value = {
            isVisible: false,
        }
    }

    return {
        //variables
        isLock,


        //getters
        getErrors,
        getIsLoader,
        getIsLock,
        getSnackbar,

        //actions
        clearErrors,
        hideSnackbar,
        setErrors,
        setIsLock,
        showSnackbar,
    }
})
