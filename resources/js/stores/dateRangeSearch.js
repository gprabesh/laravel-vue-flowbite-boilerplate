import { defineStore } from "pinia";
import Swal from "sweetalert2";

export const useDateRangeSearch = defineStore("dateRangeSearch", {
  state: () => ({
    fromDate: null,
    toDate: null,
  }),
  actions: {
    setDateRange(fromDate, toDate) {
      if (!fromDate || !toDate) {
        Swal.fire("Both from and to dates are required");
        return;
      }

      if (fromDate > toDate) {
        Swal.fire("From date cannot be later than to date");
        return;
      }

      this.fromDate = fromDate;
      this.toDate = toDate;
    },
    initializeDateRange() {
      const today = new Date().toISOString().split("T")[0];
      this.fromDate = today;
      this.toDate = today;
    },
  },
  getters: {
    getFromDate: (state) => state.fromDate,
    getToDate: (state) => state.toDate,
  },
});
