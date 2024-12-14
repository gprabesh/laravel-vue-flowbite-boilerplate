<template>
  <fwb-modal size="7xl" v-if="showVoucherPreviewModal" :not-escapable="notEscapable" :persistent="persistent">
    <template #header>
      <div class="flex items-center text-lg">Preview</div>
    </template>
    <template #body>
      <div ref="printContent" class="voucher-container">
        <PrintVoucher :print-content="printData" v-if="printData" />
      </div>
    </template>
    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="$emit('closeVoucherPreviewModal')" color="alternative">Close</fwb-button>
        <fwb-button class="print-button" @click="printVoucher" color="green">Print</fwb-button>
      </div>
    </template>
  </fwb-modal>
</template>

<script setup name="VoucherPreview">
  import { ref, onMounted } from "vue";
  import PrintVoucher from "@/components/Print/PrintVoucher.vue";
  import { FwbModal, FwbButton } from "flowbite-vue";
  const props = defineProps({
    showVoucherPreviewModal: {
      type: Boolean,
      required: true,
      default: false,
    },
    notEscapable: {
      type: Boolean,
      default: false,
    },
    persistent: {
      type: Boolean,
      default: false,
    },
    transactionId: {
      type: Number,
      required: true,
    },
  });

  // Reference for print content
  const printContent = ref(null);
  const printData = ref(null);

  // Print method
  const printVoucher = () => {
    // Create a new window for printing
    const printWindow = window.open("", "", "height=500, width=800");

    // Clone the print content
    const content = printContent.value.innerHTML;

    // Write to the new window
    printWindow.document.write("<html>");
    printWindow.document.write("<head>");
    printWindow.document.write("<title>Print Voucher</title>");

    // Native CSS for print styling
    printWindow.document.write(`
    <style>
    @page {
        size: A4;
      }
      body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 20px;
      }
      .voucher-container {
        max-width: 800px;
        margin: 0 auto;
      }
      h1 { 
        text-align: center;
        font-size: 24px;
        margin-bottom: 10px;
      }
      h3 { 
        text-align: center;
        font-size: 16px;
        margin-bottom: 10px;
      }
      .voucher-number {
        margin-bottom: 15px;
        font-weight: bold;
      }
      .voucher-reference-row{
        display: flex;
        justify-content: space-between;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
      }
      table, th, td {
        border: 1px solid #000;
        padding: 8px;
      }
      th {
        background-color: #f2f2f2;
      }
      .description {
        margin-top: 20px;
        padding-top: 10px;
      }
      .created-by {
        margin-top: 20px;
        padding-top: 10px;
      }
      .text-left{
        text-align:left;
      }
      @media print {
        body {
          margin: 0;
          padding: 0;
        }
      }
    </style>
  `);

    printWindow.document.write("</head>");
    printWindow.document.write("<body>");
    printWindow.document.write(content);
    printWindow.document.write("</body></html>");

    printWindow.document.close();
    printWindow.print();
    printWindow.close();
  };

  onMounted(async () => {
    try {
      const printResponse = await axios.get("transactions/get-print-data/" + props.transactionId);
      printData.value = printResponse.data.print_data;
    } catch (error) {
      console.log(error);
      Swal.fire("Failed to get data");
    }
  });
</script>

<style scoped>
  .voucher-container {
  max-width: 800px;
  margin: 0 auto;
}
</style>
