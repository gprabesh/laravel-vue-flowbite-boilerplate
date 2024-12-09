<script setup>
  import { ref, onMounted } from "vue";
  import { useMainStore } from "@/stores/main";
  import { mdiNewspaperVariantMultiple, mdiPlusCircle } from "@mdi/js";
  import SectionMain from "@/components/SectionMain.vue";
  import CardBox from "@/components/CardBox.vue";
  import BaseButton from "@/components/BaseButton.vue";
  import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
  import { TabulatorFull as Tabulator } from "tabulator-tables";
  import VoucherEntry from "@/components/VoucherEntry.vue";

  const mainStore = useMainStore();
  const isShowModal = ref(true);

  function closeModal() {
    isShowModal.value = false;
  }
  function showModal() {
    isShowModal.value = true;
  }

  let tabledata = ref([
    {
      id: 1,
      name: "Billy Bob",
      age: 12,
      gender: "male",
      height: 95,
      col: "red",
      dob: "14/05/2010",
    },
    {
      id: 2,
      name: "Jenny Jane",
      age: 42,
      gender: "female",
      height: 142,
      col: "blue",
      dob: "30/07/1954",
    },
    {
      id: 3,
      name: "Steve McAlistaire",
      age: 35,
      gender: "male",
      height: 176,
      col: "green",
      dob: "04/11/1982",
    },
  ]);
  let tabulator = ref(null);
  onMounted(() => {
    console.log(tabledata.value);
    tabulator = new Tabulator("#tabulator", {
      data: tabledata.value,
      autoColumns: true,
    });
  });
  const onSearch = () => {
    console.log("Search performed");
  };
</script>

<template>
  <SectionMain>
    <SectionTitleLineWithButton
      :icon="mdiNewspaperVariantMultiple"
      title="Journal Voucher"
      main
    >
      <BaseButton :icon="mdiPlusCircle" @click="showModal" color="whiteDark" />
    </SectionTitleLineWithButton>
    <CardBox has-table>
      <div id="tabulator"></div>
    </CardBox>
    <VoucherEntry
      v-if="isShowModal"
      :is-show-modal="isShowModal"
      @closeModal="closeModal()"
    ></VoucherEntry>
  </SectionMain>
</template>
