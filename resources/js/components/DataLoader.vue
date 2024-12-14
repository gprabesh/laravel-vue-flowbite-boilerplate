<template>
  <div class="relative" :class="{ 'cursor-wait': isLoading }">
    <!-- Parent content slot -->
    <slot></slot>

    <!-- Loader Overlay -->
    <transition
      enter-active-class="transition-opacity duration-300"
      leave-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      leave-to-class="opacity-0"
    >
      <div v-if="isLoading" class="absolute inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50">
        <!-- Close Button -->
        <button
          v-if="closeable"
          @click="$emit('close')"
          class="absolute top-2 right-2 text-white hover:text-gray-200 transition-colors"
        >
          <i class="fas fa-times"></i>
        </button>

        <!-- Spinner -->
        <div class="flex flex-col items-center">
          <div
            class="w-12 h-12 border-4 border-t-4 border-blue-500 border-t-transparent rounded-full animate-spin"
          ></div>
          <p class="text-white mt-4">{{ loadingText }}</p>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
  import { defineProps } from "vue";

  // Define props
  const props = defineProps({
    isLoading: {
      type: Boolean,
      default: false,
    },
    closeable: {
      type: Boolean,
      default: true,
    },
    loadingText: {
      type: String,
      default: "Loading...",
    },
  });

  // Define emits
  const emit = defineEmits(["closeLoader"]);
</script>
