import { defineStore } from 'pinia'
import {computed, ref} from "vue";

export const useMainStore = defineStore('mainStore', () => {
    //variables
    let isLock = ref(false);

    //getters
    const getIsLock = computed(() => {
        return isLock;
    })

    //setters
    function setIsLock(payload) {
        isLock.value = payload;
    }

    return {
        //variables
        isLock,


        //getters
        getIsLock,


        //setters
        setIsLock
    }
})
