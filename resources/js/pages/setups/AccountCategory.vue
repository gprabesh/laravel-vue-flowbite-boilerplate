<template>
  <SectionMain>
    <SectionTitleLineWithButton :icon="mdiNewspaperVariantMultiple" title="Account Category" main>
      <BaseButton :icon="mdiPlusCircle" @click="showCrudModal = true" color="whiteDark" />
    </SectionTitleLineWithButton>
    <CardBox has-table>
      <DataLoader :isLoading="isLoading">
        <div id="tabulator"></div>
      </DataLoader>
    </CardBox>
    <AccountCategory
      v-if="showCrudModal"
      :category-id="selectedCategoryId"
      :show-crud-modal="showCrudModal"
      @closeCrudModal="closeCrudModal"
    ></AccountCategory>
  </SectionMain>
</template>
<script setup>
  import { ref, onMounted } from "vue";
  import { mdiNewspaperVariantMultiple, mdiPlusCircle } from "@mdi/js";
  import SectionMain from "@/components/SectionMain.vue";
  import CardBox from "@/components/CardBox.vue";
  import BaseButton from "@/components/BaseButton.vue";
  import SectionTitleLineWithButton from "@/components/SectionTitleLineWithButton.vue";
  import { TabulatorFull as Tabulator } from "tabulator-tables";
  import Swal from "sweetalert2";

  import AccountCategory from "@/components/Forms/AccountCategory.vue";
  const showCrudModal = ref(false);
  const selectedCategoryId = ref(null);
  const isLoading = ref(false);

  function closeCrudModal(success = false) {
    showCrudModal.value = false;
    selectedCategoryId.value = null;
    if (success) {
      searchData();
    }
  }

  const tabledata = ref([]);
  const tabulator = ref(null);
  onMounted(async () => {
    initializeTabulator();
    await searchData();
  });
  const searchData = async () => {
    try {
      isLoading.value = true;
      const accountCategoryResponse = await axios.get("/account-categories");
      tabledata.value = accountCategoryResponse.data.account_categories;
      tabulator.value.setData(tabledata.value);
      isLoading.value = false;
    } catch (error) {
      console.log(error);
      isLoading.value = false;
      Swal.fire("Failed to get data");
    }
  };
  const actionButtons = (cell) => {
    let buttons = "";
    const category_id = cell.getRow().getData().id;
    buttons += `<button data-type='edit' class="fa-solid fa-pen-to-square py-1 m-1" data-category_id="${category_id}" title="Edit"></button>`;
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
          title: "Name",
          field: "name",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },
        {
          title: "Class",
          field: "account_class.name",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },
        {
          title: "Parent Category",
          field: "parent.name",
          frozen: true,
          headerFilterPlaceholder: " ",
          headerFilter: true,
        },
        {
          title: "Action",
          frozen: true,
          formatter: actionButtons,
          cellClick: function (e) {
            e.preventDefault();
            selectedCategoryId.value = +e.target.dataset.category_id;
            if (e.target.dataset.type == "edit") {
              showCrudModal.value = true;
            }
          },
        },
      ],
    });
  };
</script>
