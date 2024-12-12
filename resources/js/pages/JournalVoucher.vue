<script setup>
  import { ref, onMounted } from "vue";
  import { mdiNewspaperVariantMultiple, mdiPlusCircle } from "@mdi/js";
  import SectionMain from "@/components/SectionMain.vue";
  import CardBox from "@/components/CardBox.vue";
  import BaseButton from "@/components/BaseButton.vue";
  import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
  import { TabulatorFull as Tabulator } from "tabulator-tables";
  import VoucherEntry from "@/components/Forms/VoucherEntry.vue";
  import Swal from "sweetalert2";
  const isShowModal = ref(false);
  const selectedTransactionId = ref(null);

  function closeModalSuccess() {
    isShowModal.value = false;
    searchData();
  }

  function closeModal() {
    isShowModal.value = false;
  }
  function showModal() {
    isShowModal.value = true;
  }

  let tabledata = ref([]);
  const tabulator = ref(null);
  onMounted(async () => {
    tabulator.value = new Tabulator("#tabulator", {
      placeholder: "No record(s) found",
      pagination: "local",
      paginationSize: 300,
      paginationSizeSelector: [300, 600, 900, 1200],
      persistentSort: false,
      layout: "fitColumns",
      height: window.innerHeight - 220,
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
          title: "Accounts",
          field: "accounts",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },
        {
          title: "Amount",
          field: "transaction_amount",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
          hozAlign: "right",
          bottomCalc: "sum",
          bottomCalcFormatter: "money",
        },
        {
          title: "Created by",
          field: "created_by",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },

        {
          title: "Action",
          field: "transaction_date",
          frozen: true,
          formatter: actionButtons,
          cellClick: function (e, cell) {
            e.preventDefault();
            if (e.target.dataset.type == "edit") {
              selectedTransactionId.value = +e.target.dataset.transaction_id;
              showModal();
            } else if (e.target.dataset.type == "print") {
              const transaction_id = e.target.dataset.transaction_id;
              console.log("transaction id", transaction_id);
            }
          },
        },
      ],
    });
    await searchData();
  });
  const onSearch = () => {
    console.log("Search performed");
  };
  const searchData = async () => {
    try {
      const voucherResponse = await axios.get("/transactions");
      tabledata.value = voucherResponse.data.transactions;
      tabulator.value.setData(tabledata.value);
    } catch (error) {
      console.log(error);
      Swal.fire("Failed to get data");
    }
  };
  const actionButtons = (cell, formatterParams) => {
    let buttons = "";
    var transaction_id = cell.getRow().getData().id;
    buttons += `<fwb-button data-type='edit' class="bg-blue-500 text-white rounded px-2 py-1 hover:bg-blue-600 m-1" data-transaction_id="${transaction_id}" >Edit</fwb-button>`;
    buttons += `<fwb-button data-type='print' class="bg-blue-500 text-white rounded px-2 py-1 hover:bg-blue-600 m-1" "data-toggle="tooltip" data-placement="top" title="Print" data-transaction_id="${transaction_id}">Print</fwb-button>`;
    return buttons;
  };
</script>

<template>
  <SectionMain>
    <SectionTitleLineWithButton :icon="mdiNewspaperVariantMultiple" title="Journal Voucher" main>
      <BaseButton :icon="mdiPlusCircle" @click="showModal" color="whiteDark" />
    </SectionTitleLineWithButton>
    <CardBox has-table>
      <div id="tabulator"></div>
    </CardBox>
    <VoucherEntry
      v-if="isShowModal"
      :transaction-id="selectedTransactionId"
      :is-show-modal="isShowModal"
      @closeModalSuccess="closeModalSuccess()"
      @closeModal="closeModal()"
    ></VoucherEntry>
  </SectionMain>
</template>
