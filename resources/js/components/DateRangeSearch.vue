<template>
  <div class="w-full flex items-center space-x-4 bg-white p-4 rounded-lg shadow-md">
    <!-- From Date Input -->
    <div class="flex-grow">
      <label for="fromDate" class="block text-sm font-medium text-gray-700 mb-1"> From Date </label>
      <input
        id="fromDate"
        type="date"
        v-model="localFromDate"
        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
      />
    </div>

    <!-- To Date Input -->
    <div class="flex-grow">
      <label for="toDate" class="block text-sm font-medium text-gray-700 mb-1"> To Date </label>
      <input
        id="toDate"
        type="date"
        v-model="localToDate"
        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
      />
    </div>

    <!-- Search Button -->
    <div className="self-end">
      <button
        @click="performSearch"
        class="fa-solid fa-magnifying-glass bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
      ></button>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from "vue";
  import { useDateRangeSearch } from "@/stores/dateRangeSearch";
  import Swal from "sweetalert2";

  // Emit custom events
  const emit = defineEmits(["search"]);

  // Initialize Pinia store
  const dateRangeStore = useDateRangeSearch();

  // Local state for date inputs
  const localFromDate = ref(null);
  const localToDate = ref(null);

  // Initialize dates when component mounts
  onMounted(() => {
    // Check if store has no dates initialized
    if (!dateRangeStore.fromDate || !dateRangeStore.toDate) {
      dateRangeStore.initializeDateRange();
    }
    localFromDate.value = dateRangeStore.fromDate;
    localToDate.value = dateRangeStore.fromDate;
  });

  const performSearch = () => {
    try {
      // Validate date inputs
      if (!localFromDate.value || !localToDate.value) {
        Swal.fire("Both from and to dates are required");
        return;
      }

      const fromDate = localFromDate.value;
      const toDate = localToDate.value;

      // Validate date range
      if (fromDate > toDate) {
        Swal.fire("From date cannot be later than to date");
        return;
      }

      // Update store with validated dates
      dateRangeStore.setDateRange(fromDate, toDate);

      // Emit search event
      emit("search");
    } catch (error) {
      console.log(error);
    }
  };
</script>
