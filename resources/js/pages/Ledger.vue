<template>
  <SectionMain>
    <SectionTitleLineWithButton :icon="mdiBookCheck" title="Ledger" main>
      <BaseButton color="whiteDark" />
    </SectionTitleLineWithButton>
    <DateRangeSearch @search="searchData" :account-selection="true"></DateRangeSearch>
    <CardBox has-table>
      <DataLoader :isLoading="isLoading">
        <div id="tabulator"></div>
      </DataLoader>
    </CardBox>
    <VoucherEntry
      v-if="showTransactionModal"
      :transaction-id="selectedTransactionId"
      :show-transaction-modal="showTransactionModal"
      @closeTransactionModalSuccess="closeTransactionModalSuccess()"
      @closeTransactionModal="closeTransactionModal()"
    ></VoucherEntry>
    <VoucherPreview
      :not-escapable="true"
      :persistent="true"
      v-if="showVoucherPreviewModal"
      :show-voucher-preview-modal="showVoucherPreviewModal"
      :transaction-id="selectedTransactionId"
      @closeVoucherPreviewModal="showVoucherPreviewModal = false"
    />
  </SectionMain>
</template>
<script setup>
  import { ref, onMounted } from "vue";
  import { mdiNewspaperVariantMultiple, mdiPlusCircle, mdiBookCheck } from "@mdi/js";
  import SectionMain from "@/components/SectionMain.vue";
  import CardBox from "@/components/CardBox.vue";
  import BaseButton from "@/components/BaseButton.vue";
  import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
  import { TabulatorFull as Tabulator } from "tabulator-tables";
  import VoucherEntry from "@/components/Forms/VoucherEntry.vue";
  import VoucherPreview from "@/components/VoucherPreview.vue";
  import Swal from "sweetalert2";
  import DateRangeSearch from "@/components/DateRangeSearch.vue";
  import DataLoader from "@/components/DataLoader.vue";
  import { useDateRangeSearch } from "@/stores/dateRangeSearch";
  const showTransactionModal = ref(false);
  const showVoucherPreviewModal = ref(false);
  const selectedTransactionId = ref(null);
  const isLoading = ref(false);

  function closeTransactionModalSuccess() {
    showTransactionModal.value = false;
    selectedTransactionId.value = null;
  }

  function closeTransactionModal() {
    showTransactionModal.value = false;
    selectedTransactionId.value = null;
  }

  const tabledata = ref([]);
  const tabulator = ref(null);
  onMounted(async () => {
    initializeTabulator();
  });
  const searchData = async (account) => {
    const dateRangeSearch = useDateRangeSearch();
    try {
      isLoading.value = true;
      const voucherResponse = await axios.get("/transactions/get-ledger-data/" + account.id, {
        params: {
          from: dateRangeSearch.fromDate,
          to: dateRangeSearch.toDate,
        },
      });
      tabledata.value = voucherResponse.data.ledgerData;
      tabulator.value.setData(tabledata.value);
      isLoading.value = false;
    } catch (error) {
      console.log(error);
      isLoading.value = false;
      Swal.fire("Failed to get data");
    }
  };
  const actionButtons = (cell, formatterParams) => {
    let buttons = "";
    const transaction_id = cell.getRow().getData().id;
    buttons += `<button data-type='edit' class="fa-solid fa-pen-to-square py-1 m-1" data-transaction_id="${transaction_id}" title="Edit"></button>`;
    buttons += `<button data-type='print' class="fa-solid fa-print py-1 m-1" "data-toggle="tooltip" data-placement="top" title="Print" data-transaction_id="${transaction_id}"></button>`;
    return buttons;
  };
  const initializeTabulator = () => {
    tabulator.value = new Tabulator("#tabulator", {
      placeholder: "No record(s) found",
      pagination: "local",
      paginationSize: 300,
      paginationSizeSelector: [300, 600, 900, 1200],
      persistentSort: false,
      layout: "fitColumns",
      height: window.innerHeight - 250,
      data: tabledata.value,
      columns: [
        {
          title: "Entry Date",
          field: "transaction_date",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },
        {
          title: "Voucher no.",
          field: "voucher_no",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },
        {
          title: "Reference no.",
          field: "reference_no",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },
        {
          title: "Description",
          field: "description",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },
        {
          title: "Created by",
          field: "created_by",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },
        {
          title: "Debit",
          field: "debit_amount",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
          hozAlign: "right",
          bottomCalc: "sum",
          formatter: "money",
          bottomCalcFormatter: "money",
        },
        {
          title: "Credit",
          field: "credit_amount",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
          hozAlign: "right",
          bottomCalc: "sum",
          formatter: "money",
          bottomCalcFormatter: "money",
        },
        {
          title: "Balance",
          frozen: true,
          hozAlign: "right",
          formatter: function (cell) {
            const balance = cell.getRow().getData().balance;
            const balance_type = cell.getRow().getData().balance_type;
            return `${balance} ${balance_type}`;
          },
        },
        {
          title: "Action",
          field: "transaction_date",
          frozen: true,
          formatter: actionButtons,
          cellClick: function (e, cell) {
            e.preventDefault();
            selectedTransactionId.value = +e.target.dataset.transaction_id;
            if (e.target.dataset.type == "edit") {
              showTransactionModal.value = true;
            } else if (e.target.dataset.type == "print") {
              showVoucherPreviewModal.value = true;
            }
          },
        },
      ],
    });
  };
</script>
