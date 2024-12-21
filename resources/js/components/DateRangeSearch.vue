<template>
  <div class="w-full flex items-center space-x-4 bg-white p-4 rounded-lg shadow-md">
    <!-- Account Input -->
    <div class="flex-grow" v-if="accountSelection">
      <label for="fromDate" class="block text-sm font-medium text-gray-700 mb-1"> From Date </label>
      <Multiselect
        v-model="account"
        :options="accountOptions"
        placeholder="Select Account"
        open-direction="bottom"
        label="name"
        track-by="id"
      ></Multiselect>
    </div>
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

  const props = defineProps({
    accountSelection: {
      type: Boolean,
      default: false,
    },
  });

  // Emit custom events
  const emit = defineEmits(["search"]);

  // Initialize Pinia store
  const dateRangeStore = useDateRangeSearch();

  // Local state for date inputs
  const localFromDate = ref(null);
  const localToDate = ref(null);
  const account = ref(null);
  const accountOptions = ref([]);

  const fetchAccounts = async () => {
    try {
      const response = await axios.get("/accounts");
      accountOptions.value = response.data.accounts;
    } catch (error) {
      console.error("Error fetching accounts:", error);
    }
  };

  // Initialize dates when component mounts
  onMounted(async () => {
    // Check if store has no dates initialized
    if (!dateRangeStore.fromDate || !dateRangeStore.toDate) {
      dateRangeStore.initializeDateRange();
    }
    localFromDate.value = dateRangeStore.fromDate;
    localToDate.value = dateRangeStore.fromDate;
    if (props.accountSelection) {
      await fetchAccounts();
    }
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

      if (props.accountSelection && !account.value) {
        Swal.fire("Please select an account");
        return;
      }

      // Emit search event
      emit("search", account.value);
    } catch (error) {
      console.log(error);
    }
  };
</script>
