<template>
  <div class="print-voucher">
    <h1 class="text-center company-name">{{ printContent?.company ?? "-" }}</h1>
    <h3 class="text-left date">{{ printContent?.transaction_date ?? "-" }}</h3>

    <div class="voucher-reference-row">
      <div class="voucher-number">Voucher No: {{ printContent?.voucher_no ?? "-" }}</div>
      <div class="voucher-number">Reference No: {{ printContent?.reference_no ?? "-" }}</div>
    </div>

    <table class="voucher-table">
      <thead>
        <tr>
          <th>Account</th>
          <th>Debit</th>
          <th>Credit</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(entry, index) in JSON.parse(printContent?.transaction_details ?? '[]')" :key="index">
          <td>{{ entry.account }}</td>
          <td class="text-right">{{ entry.debit_amount }}</td>
          <td class="text-right">{{ entry.credit_amount }}</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td>Total:</td>
          <td class="text-right">{{ printContent?.total_debit ?? "-" }}</td>
          <td class="text-right">{{ printContent?.total_debit ?? "-" }}</td>
        </tr>
      </tfoot>
    </table>
    <div class="voucher-reference-row">
      <div class="description">Description: {{ printContent?.description ?? "-" }}</div>
      <div class="description">Created by: {{ printContent?.created_by ?? "-" }}</div>
    </div>
  </div>
</template>

<script setup>
  import { defineProps } from "vue";
  const props = defineProps({
    printContent: {
      type: Object,
      required: true,
    },
  });
</script>

<style scoped>
  .print-voucher {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
  }
  .company-name {
    font-size: 24px;
    margin-bottom: 10px;
  }
  
  .company-address {
    font-size: 16px;
    margin-bottom: 10px;
  }
  
  .date {
    margin-bottom: 20px;
  }
  
  .voucher-number {
    text-align: left;
    margin-bottom: 15px;
    font-weight: bold;
  }
  
  .voucher-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
  }
  
  .voucher-table th, 
  .voucher-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
  
  .voucher-table th {
    background-color: #f2f2f2;
    font-weight: bold;
  }
  
  .description {
    margin-top: 20px;
    padding-top: 10px;
  }
  .voucher-reference-row{
    display: flex;
    justify-content: space-between;
  }
  .text-right{
    text-align: right !important;
  }
</style>
