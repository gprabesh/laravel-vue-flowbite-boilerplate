<template>
  <fwb-modal size="7xl" v-if="showCrudModal" :not-escapable="notEscapable" :persistent="persistent">
    <template #header>
      <div class="flex items-center text-lg">Account</div>
    </template>
    <template #body>
      <DataLoader :isLoading="isLoading">
        <div class="p-4 space-y-4 mx-auto">
          <form class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Name</label>
              <input
                v-model="formData.name"
                type="text"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Account Category</label>
              <select
                v-model="formData.account_category_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
              >
                <option :value="null">Select Account Category</option>
                <option
                  v-for="accountCategory in accountCategories"
                  :key="accountCategory.id"
                  :value="accountCategory.id"
                >
                  {{ accountCategory.name }}
                </option>
              </select>
            </div>
          </form>
        </div>
      </DataLoader>
    </template>
    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="$emit('closeCrudModal', false)" color="alternative"> Close </fwb-button>
        <fwb-button @click="submitForm()" color="green"> {{ accountId ? "Update" : "Create" }} </fwb-button>
      </div>
    </template>
  </fwb-modal>
</template>
<script setup name="Account">
  import { FwbModal, FwbButton } from "flowbite-vue";
  import { ref, onMounted } from "vue";

  import Swal from "sweetalert2";

  const emit = defineEmits(["closeCrudModal"]);
  const props = defineProps({
    showCrudModal: {
      type: Boolean,
      required: true,
      default: false,
    },
    notEscapable: {
      type: Boolean,
      default: true,
    },
    persistent: {
      type: Boolean,
      default: true,
    },
    accountId: {
      type: Number,
      default: null,
    },
  });
  const formData = ref({
    name: "",
    account_category_id: null,
  });
  const accountCategories = ref([]);
  const isLoading = ref(false);

  const validateForm = () => {
    let valid = true;
    if (!formData.value.name) {
      Swal.fire("Name is required");
      valid = false;
    }
    if (!formData.value.account_category_id) {
      Swal.fire("Account category is required");
      valid = false;
    }
    return valid;
  };

  const submitForm = async () => {
    const confirmation = await Swal.fire({
      title: "Are you sure?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Confirm",
    });
    if (!confirmation.isConfirmed) {
      return;
    }
    if (!validateForm()) {
      return;
    }
    try {
      let url = `/accounts`;
      if (props.accountId > 0) {
        formData.value._method = "PUT";
        url = "/accounts/" + props.accountId;
      }
      const response = await axios.post(url, formData.value);
      Swal.fire(response?.data?.message);
      emit("closeCrudModal", true);
    } catch (error) {
      console.log(error);
      Swal.fire("Failed to save data");
    }
  };
  const fetchAccountCategories = async () => {
    try {
      const response = await axios.get("/account-categories");
      accountCategories.value = response.data.account_categories;
    } catch (error) {
      console.error("Error fetching account categories:", error);
    }
  };
  const fetchAccount = async () => {
    if (!props.accountId) return;
    try {
      const response = await axios.get(`accounts/${props.accountId}/edit`);
      formData.value = {
        name: response.data.account.name,
        account_category_id: response.data.account.account_category_id,
      };
    } catch (error) {
      console.error("Error fetching account:", error);
    }
  };
  onMounted(async () => {
    try {
      isLoading.value = true;
      await fetchAccountCategories();
      if (props.accountId > 0) {
        await fetchAccount();
      }
      isLoading.value = false;
    } catch (error) {
      console.log(error);
      Swal.fire("Failed to get data");
      isLoading.value = false;
    }
  });
</script>
