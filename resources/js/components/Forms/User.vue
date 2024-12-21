<template>
  <fwb-modal size="7xl" v-if="showCrudModal" :not-escapable="notEscapable" :persistent="persistent">
    <template #header>
      <div class="flex items-center text-lg">User</div>
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
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-model="formData.email"
                type="email"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Password</label>
              <input
                v-model="formData.password"
                type="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
              <input
                v-model="formData.password_confirmation"
                type="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Books</label>
              <Multiselect
                v-model="formData.account_books"
                :options="accountBooks"
                placeholder="Select Books"
                label="name"
                open-direction="bottom"
                track-by="id"
                :multiple="true"
              ></Multiselect>
            </div>
          </form>
        </div>
      </DataLoader>
    </template>
    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="$emit('closeCrudModal', false)" color="alternative"> Close </fwb-button>
        <fwb-button @click="submitForm()" color="green"> {{ userId ? "Update" : "Create" }} </fwb-button>
      </div>
    </template>
  </fwb-modal>
</template>
<script setup name="User">
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
    userId: {
      type: Number,
      default: null,
    },
  });
  const formData = ref({
    name: "",
    email: "",
    password: null,
    password_confirmation: null,
    account_books: [],
  });
  const accountBooks = ref([]);
  const isLoading = ref(false);

  const validateForm = () => {
    let valid = true;
    if (!formData.value.name) {
      Swal.fire("Name is required");
      valid = false;
    }
    if (!formData.value.email) {
      Swal.fire("Email is required");
      valid = false;
    }
    if (formData.value.account_books.length == 0) {
      Swal.fire("Account book should be assigned to the user");
      valid = false;
    }
    if (props.userId > 0) {
      if (formData.value.password && formData.value.password !== formData.value.password_confirmation) {
        Swal.fire("Password input and confirmed should match");
        valid = false;
      }
    } else {
      if (!formData.value.password || !formData.value.password_confirmation) {
        Swal.fire("Password is required should be confirmed");
        valid = false;
      } else if (formData.value.password !== formData.value.password_confirmation) {
        Swal.fire("Password input and confirmed should match");
        valid = false;
      }
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
      let url = `/users`;
      if (props.userId > 0) {
        formData.value._method = "PUT";
        url = "/users/" + props.userId;
      }
      const response = await axios.post(url, formData.value);
      Swal.fire(response?.data?.message);
      emit("closeCrudModal", true);
    } catch (error) {
      console.log(error);
      Swal.fire(error.response?.data?.message);
    }
  };
  const fetchAccountBooks = async () => {
    try {
      const response = await axios.get("/account-books");
      accountBooks.value = response.data.account_books;
    } catch (error) {
      console.error("Error fetching books: ", error);
    }
  };
  const fetchUser = async () => {
    if (!props.userId) return;
    try {
      const response = await axios.get(`users/${props.userId}`);
      formData.value.name = response.data.user.name;
      formData.value.email = response.data.user.email;
      formData.value.account_books = response.data.user.account_books.map((element) => {
        return accountBooks.value.find((book) => element == book.id);
      });
    } catch (error) {
      console.error("Error fetching user:", error);
    }
  };
  onMounted(async () => {
    try {
      isLoading.value = true;
      await fetchAccountBooks();
      if (props.userId > 0) {
        await fetchUser();
      }
      isLoading.value = false;
    } catch (error) {
      console.log(error);
      Swal.fire("Failed to get data");
      isLoading.value = false;
    }
  });
</script>
