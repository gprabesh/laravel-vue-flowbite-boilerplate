<template>
  <SectionMain>
    <SectionTitleLineWithButton :icon="mdiTable" title="Trial Balance" main>
      <BaseButton color="whiteDark" />
    </SectionTitleLineWithButton>
    <DateRangeSearch @search="searchData" :account-selection="false"></DateRangeSearch>
    <CardBox has-table>
      <DataLoader :isLoading="isLoading">
        <div id="tabulator"></div>
      </DataLoader>
    </CardBox>
  </SectionMain>
</template>
<script setup>
  import { ref, onMounted } from "vue";
  import { mdiTable } from "@mdi/js";
  import SectionMain from "@/components/SectionMain.vue";
  import CardBox from "@/components/CardBox.vue";
  import BaseButton from "@/components/BaseButton.vue";
  import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
  import { TabulatorFull as Tabulator } from "tabulator-tables";
  import Swal from "sweetalert2";
  import DateRangeSearch from "@/components/DateRangeSearch.vue";
  import DataLoader from "@/components/DataLoader.vue";
  import { useDateRangeSearch } from "@/stores/dateRangeSearch";
    const isLoading = ref(false);

  const tabledata = ref([]);
  const tabulator = ref(null);
  onMounted(async () => {
    initializeTabulator();
  });
  const searchData = async () => {
    const dateRangeSearch = useDateRangeSearch();
    try {
      isLoading.value = true;
      const voucherResponse = await axios.get("/transactions/get-trial-balance-data", {
        params: {
          from: dateRangeSearch.fromDate,
          to: dateRangeSearch.toDate,
        },
      });
      tabledata.value = voucherResponse.data.trialBalanceData;
      tabulator.value.setData(tabledata.value);
      isLoading.value = false;
    } catch (error) {
      console.log(error);
      isLoading.value = false;
      Swal.fire("Failed to get data");
    }
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
          title: "Account",
          field: "name",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },
        {
          title: "Opening DR.",
          field: "opening_debit",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
          hozAlign: "right",
          bottomCalc: "sum",
          formatter: "money",
          bottomCalcFormatter: "money",
        },
        {
          title: "Opening CR.",
          field: "opening_credit",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
          hozAlign: "right",
          bottomCalc: "sum",
          formatter: "money",
          bottomCalcFormatter: "money",
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
          title: "Closing DR.",
          field: "closing_debit",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
          hozAlign: "right",
          bottomCalc: "sum",
          formatter: "money",
          bottomCalcFormatter: "money",
        },
        {
          title: "Closing CR.",
          field: "closing_credit",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
          hozAlign: "right",
          bottomCalc: "sum",
          formatter: "money",
          bottomCalcFormatter: "money",
        },
      ],
    });
  };
</script>
