import { defineStore } from 'pinia'
import {computed, ref} from "vue";

export const useMainStore = defineStore('mainStore', () => {
    //variables
    let isLoader = ref(false);
    let isLock = ref(false);

    //getters
    const getIsLoader = computed(() => {
        return isLoader.value;
    })
    const getIsLock = computed(() => {
        return isLock.value;
    })

    //setters
    function setIsLock(value, showLoader = true) {
        isLock.value = value;
        if (showLoader) {
            isLoader.value = value
        }
    }

    return {
        //variables
        isLock,


        //getters
        getIsLoader,
        getIsLock,

        //setters
        setIsLock
    }
})
