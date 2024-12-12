<script setup name="VoucherEntry">
  import { FwbModal, FwbButton } from "flowbite-vue";
  import { ref, onMounted, computed } from "vue";
  import { Multiselect } from "vue-multiselect";
  import Swal from "sweetalert2";

  const emit = defineEmits(["closeModal", "closeModalSuccess"]);
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
    voucherId: {
      type: Number,
      default: null,
    },
  });
  // Initialize form data with two default transactions
  const formData = ref({
    transactionDate: new Date().toISOString().split("T")[0],
    referenceNo: null,
    transactions: [
      { id: null, account: null, debitAmount: 0, creditAmount: 0 },
      { id: null, account: null, debitAmount: 0, creditAmount: 0 },
    ],
    description: "",
  });

  // Accounts options (to be populated from API)
  const accountOptions = ref([]);
  const calenderMaxDate = new Date().toISOString().split("T")[0];

  // Add a new transaction entry
  const addTransaction = () => {
    formData.value.transactions.push({
      id: null,
      account: null,
      debitAmount: 0,
      creditAmount: 0,
    });
  };
  // Remove a transaction entry (only for entries beyond the first two)
  const removeTransaction = (index) => {
    if (formData.value.transactions.length > 2) {
      formData.value.transactions.splice(index, 1);
    }
  };
  const totalDebit = computed(() => {
    return formData.value.transactions.reduce((sum, t) => sum + (t.debitAmount || 0), 0);
  });
  const totalCredit = computed(() => {
    return formData.value.transactions.reduce((sum, t) => sum + (t.creditAmount || 0), 0);
  });

  // Validation to check total debit and credit amounts
  const validateDebitCredit = () => {
    const totalDebit = formData.value.transactions.reduce((sum, t) => sum + (t.debitAmount || 0), 0);
    const totalCredit = formData.value.transactions.reduce((sum, t) => sum + (t.creditAmount || 0), 0);

    return totalDebit === totalCredit;
  };
  const validateForm = () => {
    let valid = true;
    if (!formData.value.description) {
      Swal.fire("Description is required");
      valid = false;
    }
    if (!formData.value.transactionDate) {
      Swal.fire("Date is required");
      valid = false;
    }
    if (totalDebit.value != totalCredit.value) {
      Swal.fire("Please balance debit and credit");
      valid = false;
    }
    if (totalDebit.value == 0 || totalCredit.value == 0) {
      Swal.fire("Total debit/credit cannot be zero");
      valid = false;
    }
    if (totalDebit.value < 0 || totalCredit.value < 0) {
      Swal.fire("Total debit/credit cannot be negative");
      valid = false;
    }
    return valid;
  };
  const setDebitOrCredit = (event, index) => {
    if (event.key == "d" || event.key == "D") {
      formData.value.transactions[index].type = "DR";
    }
    if (event.key == "c" || event.key == "C") {
      formData.value.transactions[index].type = "CR";
    }
  };
  const resetDebit = (event, index) => {
    formData.value.transactions[index].debitAmount = 0;
  };
  const resetCredit = (event, index) => {
    formData.value.transactions[index].creditAmount = 0;
  };

  // Form submission handler
  const submitForm = async () => {
    // Validate transactions
    if (!validateDebitCredit()) {
      Swal.fire("Total Debit and Credit amounts must be equal");
      return;
    }
    if (!validateForm()) {
      return;
    }
    const formSubmitData = {};
    formSubmitData.transactions = [];
    formSubmitData.description = formData.value.description;
    formData.value.transactions.forEach((element) => {
      formSubmitData.transactions.push({
        id: element.id,
        account_id: element.account.id,
        debit_amount: element.debitAmount,
        credit_amount: element.creditAmount,
      });
    });
    formSubmitData.reference_no = formData.value.referenceNo;
    formSubmitData.transaction_date = formData.value.transactionDate;
    try {
      const saveResponse = await axios.post("/transactions", formSubmitData);
      Swal.fire("Journal Saved");
      emit("closeModalSuccess");
    } catch (error) {
      console.log(error);
      Swal.fire("Failed to save data");
    }
  };
  const fetchAccounts = async () => {
    try {
      const response = await axios.get("/accounts");
      accountOptions.value = response.data.accounts;
    } catch (error) {
      console.error("Error fetching accounts:", error);
    }
  };
  onMounted(() => {
    fetchAccounts();
  });
</script>

<template>
  <fwb-modal size="7xl" v-if="isShowModal" :not-escapable="true" :persistent="true">
    <template #header>
      <div class="flex items-center text-lg">Journal Entry</div>
    </template>
    <template #body>
      <div class="p-4">
        <form @submit.prevent="submitForm" class="space-y-4">
          <div class="flex">
            <div class="flex items-center space-x-4 m-1">
              <label class="font-semibold">Entry Date:</label>
              <input
                type="date"
                v-model="formData.transactionDate"
                :max="calenderMaxDate"
                class="border rounded px-2 py-1"
              />
            </div>
            <div class="flex items-center space-x-4 m-1">
              <label class="font-semibold">Reference no:</label>
              <input type="text" v-model="formData.referenceNo" class="border rounded px-2 py-1" />
            </div>
          </div>

          <table>
            <thead>
              <tr>
                <!-- <td width="10%">DR/CR</td> -->
                <td width="35%">Account</td>
                <td width="20%">Debit</td>
                <td width="20%">Credit</td>
                <td width="15%">
                  Action <fwb-button type="button" @click="addTransaction" color="green" class="m-1"> + </fwb-button>
                </td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(transaction, index) in formData.transactions" :key="index">
                <!-- <td>
                  <select class="form-control" v-model="transaction.type">
                    <option value="DR">Dr</option>
                    <option value="CR">Cr</option>
                  </select>
                </td> -->
                <td>
                  <multiselect
                    v-model="transaction.account"
                    :options="accountOptions"
                    placeholder="Select Account"
                    label="name"
                    track-by="id"
                    @change="setDebitOrCredit($event, index)"
                  ></multiselect>
                </td>
                <td>
                  <input
                    type="number"
                    v-model.number="transaction.debitAmount"
                    placeholder="Debit Amount"
                    class="border rounded px-2 py-1 w-24"
                    min="0"
                    step="0.01"
                    @change="resetCredit($event, index)"
                  />
                </td>
                <td>
                  <input
                    type="number"
                    v-model.number="transaction.creditAmount"
                    placeholder="Credit Amount"
                    class="border rounded px-2 py-1 w-24"
                    min="0"
                    step="0.01"
                    @change="resetDebit($event, index)"
                  />
                </td>
                <td>
                  <fwb-button
                    v-if="index === formData.transactions.length - 1"
                    type="button"
                    @click="addTransaction"
                    color="green"
                    class="m-1"
                  >
                    +
                  </fwb-button>

                  <!-- Remove Transaction Button (for entries after first two) -->
                  <fwb-button type="button" @click="removeTransaction(index)" color="red" class="m-1"> X </fwb-button>
                </td>
              </tr>
            </tbody>
          </table>
          <!-- Description Input -->
          <div>
            <label class="block mb-2">Description:</label>
            <textarea v-model="formData.description" class="w-full border rounded px-2 py-1" rows="3"></textarea>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600">
            Submit Transaction
          </button>
        </form>
      </div>
    </template>
    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="$emit('closeModal')" color="alternative"> Decline </fwb-button>
        <fwb-button @click="$emit('closeModal')" color="green"> I accept </fwb-button>
      </div>
    </template>
  </fwb-modal>
</template>
