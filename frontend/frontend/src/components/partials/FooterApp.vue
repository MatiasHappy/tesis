<template>
  <div class="bg-white border-t-2 border-gray-400">
    <div class="grid grid-cols-5 pt-5 pb-2"> 
      <div class="flex border-r-2 m-auto"> 
        <router-link to="/dashboard">
          <HomeIcon class="w-1/2 m-auto text-duty" />
        </router-link>
      </div>
      <div class="flex">
        <router-link to="/tasks">
          <ClipboardDocumentCheckIcon class="w-1/2 m-auto text-duty" />
        </router-link>
      </div>
      <div class="rounded-full m-auto">
        <IsoButton @click="openModal" />
      </div>
      <div class="flex border-r-2"> 
        <router-link to="/tasks">
          <BanknotesIcon class="w-1/2 m-auto text-duty" />
        </router-link>
      </div>
      <div class="flex">
        <router-link to="/tasks">
          <CalendarDaysIcon class="w-1/2 m-auto text-duty" />
        </router-link>
      </div>
    </div>
    <CreateTask :open="isCreateTaskOpen" @update:open="isCreateTaskOpen = $event" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { HomeIcon, BanknotesIcon, CalendarDaysIcon, ClipboardDocumentCheckIcon } from '@heroicons/vue/24/solid';
import IsoButton from './IsoButton.vue';
import CreateTask from '../modals/CreateTask.vue';

const isCreateTaskOpen = ref(false);

const openModal = () => {
  isCreateTaskOpen.value = true;
};
</script>

<style>
/* Add any required CSS here */
</style>
