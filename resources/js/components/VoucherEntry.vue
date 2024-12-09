<script setup name="VoucherEntry">
  import { FwbModal, FwbButton } from "flowbite-vue";
  import { ref, onMounted } from "vue";
  import { Multiselect } from "vue-multiselect";
  const props = defineProps({
    isShowModal: {
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
  });
  const accounts = ref([]);
  const formData = ref({
    transactionDate: new Date().toISOString().split("T")[0],
    transactions: [
      {
        account: null,
        debit: 0,
        credit: 0,
      },
      {
        account: null,
        debit: 0,
        credit: 0,
      },
    ],
    description: "",
  });
  const addTransaction = () => {
    formData.value.transactions.push({
      account: null,
      debit: 0,
      credit: 0,
    });
  };

  const removeTransaction = (index) => {
    formData.value.transactions.splice(index, 1);
  };

  const submitForm = () => {
    console.log("Form data:", formData.value);
  };
  const fetchAccounts = async () => {
    try {
      const response = await axios.get("/accounts");
      accounts.value = response.data.accounts;
    } catch (error) {
      console.error("Error fetching accounts:", error);
    }
  };
  onMounted(() => {
    fetchAccounts();
  });
</script>

<template>
  <fwb-modal
    size="7xl"
    v-if="isShowModal"
    :not-escapable="true"
    :persistent="true"
  >
    <template #header>
      <div class="flex items-center text-lg">Journal Entry</div>
    </template>
    <template #body>
      <form @submit.prevent="submitForm">
        <div>
          <label for="date">Date</label>
          <input type="date" v-model="formData.transactionDate" />
        </div>
        <div
          v-for="(transaction, index) in formData.transactions"
          :key="index"
          class="transaction-item"
        >
          <div>
            <label for="account">Account</label>
            <Multiselect
              v-model="transaction.account"
              :options="accounts"
              label="name"
              track-by="id"
              placeholder="Select account"
              :close-on-select="true"
            />
          </div>

          <div>
            <label for="debit">Debit</label>
            <input
              type="number"
              v-model="transaction.debit"
              placeholder="Debit Amount"
            />
          </div>

          <div>
            <label for="credit">Credit</label>
            <input
              type="number"
              v-model="transaction.credit"
              placeholder="Credit Amount"
            />
          </div>

          <button
            @click.prevent="removeTransaction(index)"
            v-if="formData.transactions.length > 2"
          >
            Remove
          </button>
        </div>

        <button @click.prevent="addTransaction">Add Transaction</button>

        <div>
          <label for="description">Description</label>
          <textarea
            v-model="formData.description"
            placeholder="Enter description"
          ></textarea>
        </div>

        <button type="submit">Submit</button>
      </form>
    </template>
    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="$emit('closeModal')" color="alternative">
          Decline
        </fwb-button>
        <fwb-button @click="$emit('closeModal')" color="green">
          I accept
        </fwb-button>
      </div>
    </template>
  </fwb-modal>
</template>
