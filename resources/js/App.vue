<script setup>
  import { RouterView } from "vue-router";
  import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
  import SectionMain from "@/components/SectionMain.vue";
  import { useUserStore } from "./stores/user";
  const userStore = useUserStore();
</script>

<template>
  <LayoutAuthenticated v-if="userStore.isAuthenticated">
    <RouterView />
  </LayoutAuthenticated>
  <RouterView v-else />
</template>
