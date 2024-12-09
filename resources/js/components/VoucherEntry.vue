<script setup name="VoucherEntry">
  import { FwbModal, FwbButton } from "flowbite-vue";
  import { ref, onMounted } from "vue";
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
  onMounted(async () => {
    accounts.value = await fetchAccounts();
  });
  const fetchAccounts = async () => {
    return axios.get("/accounts");
  };
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
      <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
        With less than a month to go before the European Union enacts new
        consumer privacy laws for its citizens, companies around the world are
        updating their terms of service agreements to comply.
      </p>
      <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
        The European Union’s General Data Protection Regulation (G.D.P.R.) goes
        into effect on May 25 and is meant to ensure a common set of data rights
        in the European Union. It requires organizations to notify users as soon
        as possible of high-risk data breaches that could personally affect
        them.
      </p>
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
