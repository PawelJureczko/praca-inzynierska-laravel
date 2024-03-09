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

    //actions
    function setIsLock(value, showLoader = true) {
        isLock.value = value;
        if (showLoader) {
            isLoader.value = value
        }
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
        getIsLoader,
        getIsLock,
        getSnackbar,

        //actions
        hideSnackbar,
        setIsLock,
        showSnackbar,
    }
})
