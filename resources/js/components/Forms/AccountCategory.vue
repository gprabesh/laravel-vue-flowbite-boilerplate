<template>
  <fwb-modal size="7xl" v-if="showCrudModal" :not-escapable="notEscapable" :persistent="persistent">
    <template #header>
      <div class="flex items-center text-lg">Account Category</div>
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
              <label class="block text-sm font-medium text-gray-700">Account Class</label>
              <select
                v-model="formData.account_class_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
              >
                <option :value="null">Select Account Class</option>
                <option v-for="accountClass in accountClasses" :key="accountClass.id" :value="accountClass.id">
                  {{ accountClass.name }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Parent Category (Optional)</label>
              <select
                v-model="formData.parent_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
                <option :value="null">No Parent</option>
                <option v-for="category in parentCategories" :key="category.id" :value="category.id">
                  {{ category.name }}
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
        <fwb-button @click="submitForm()" color="green"> {{ categoryId ? "Update" : "Create" }} </fwb-button>
      </div>
    </template>
  </fwb-modal>
</template>
<script setup name="AccountCategory">
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
    categoryId: {
      type: Number,
      default: null,
    },
  });
  const formData = ref({
    name: "",
    account_class_id: null,
    parent_id: null,
  });
  const accountClasses = ref([]);
  const parentCategories = ref([]);
  const isLoading = ref(false);

  const validateForm = () => {
    let valid = true;
    if (!formData.value.name) {
      Swal.fire("Name is required");
      valid = false;
    }
    if (!formData.value.account_class_id) {
      Swal.fire("Account class is required");
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
      let url = `/account-categories`;
      if (props.categoryId > 0) {
        formData.value._method = "PUT";
        url = "/account-categories/" + props.categoryId;
      }
      const response = await axios.post(url, formData.value);
      Swal.fire(response?.data?.message);
      emit("closeCrudModal", true);
    } catch (error) {
      console.log(error);
      Swal.fire("Failed to save data");
    }
  };
  const fetchAccountClasses = async () => {
    try {
      const response = await axios.get("/get-account-classes");
      accountClasses.value = response.data.account_classes;
    } catch (error) {
      console.error("Error fetching account classes:", error);
    }
  };
  const fetchParentCategories = async () => {
    try {
      const response = await axios.get("/get-parent-categories");
      parentCategories.value = response.data.parent_categories;
    } catch (error) {
      console.error("Error fetching parent categories:", error);
    }
  };
  const fetchCategory = async () => {
    if (!props.categoryId) return;
    try {
      const response = await axios.get(`account-categories/${props.categoryId}/edit`);
      formData.value = {
        name: response.data.account_category.name,
        account_class_id: response.data.account_category.account_class_id,
        parent_id: response.data.account_category.parent_id,
      };
    } catch (error) {
      console.error("Error fetching category:", error);
    }
  };
  onMounted(async () => {
    try {
      isLoading.value = true;
      await fetchAccountClasses();
      await fetchParentCategories();
      if (props.categoryId > 0) {
        await fetchCategory();
      }
      isLoading.value = false;
    } catch (error) {
      console.log(error);
      Swal.fire("Failed to get data");
      isLoading.value = false;
    }
  });
</script>
