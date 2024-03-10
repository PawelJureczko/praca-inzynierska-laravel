<template>
  <div class="w-10 h-10 border border-dark_grey rounded-full cursor-pointer" @click="handleHamburgerClick">
    <div class="relative top-[18px] left-3">
      <span aria-hidden="true" class="block absolute h-0.5 w-4 bg-black transform transition duration-500 ease-in-out" :class="{'rotate-45': open,' -translate-y-1.5': !open }"></span>
      <span aria-hidden="true" class="block absolute  h-0.5 w-4 bg-black  transform transition duration-500 ease-in-out" :class="{'opacity-0': open } "></span>
      <span aria-hidden="true" class="block absolute  h-0.5 w-4 bg-black transform  transition duration-500 ease-in-out" :class="{'-rotate-45': open, ' translate-y-1.5': !open}"></span>
    </div>
  </div>
</template>

<script setup>
const open = defineModel();

function handleHamburgerClick() {
    open.value = !open.value;
}
</script>

