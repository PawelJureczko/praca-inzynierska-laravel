<template>
  <div class="w-[60px] h-9 border border-black rounded-full cursor-pointer" @click="handleHamburgerClick">
    <div class="relative top-4 left-[17px]">
      <span aria-hidden="true" class="block absolute h-0.5 w-6 bg-black transform transition duration-500 ease-in-out" :class="{'rotate-45': open,' -translate-y-1.5': !open }"></span>
      <span aria-hidden="true" class="block absolute  h-0.5 w-6 bg-black  transform transition duration-500 ease-in-out" :class="{'opacity-0': open } "></span>
      <span aria-hidden="true" class="block absolute  h-0.5 w-6 bg-black transform  transition duration-500 ease-in-out" :class="{'-rotate-45': open, ' translate-y-1.5': !open}"></span>
    </div>
  </div>
</template>

<script setup>
const open = defineModel();

function handleHamburgerClick() {
    open.value = !open.value;
}
</script>

