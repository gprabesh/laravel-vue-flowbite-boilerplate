import { defineStore } from "pinia";
import { axios } from "../plugins/axios";
import router from "../router";

export const useUserStore = defineStore("user", {
  state: () => ({
    user: null,
    isAuthenticated: false,
  }),

  actions: {
    async fetchUser() {
      try {
        const response = await axios.get("/me");
        this.user = response.data.user;
        this.isAuthenticated = true;
      } catch (error) {
        this.user = null;
        this.isAuthenticated = false;
      }
    },
    async logout() {
      try {
        await axios.post("/logout");
      } catch (error) {
        console.log(error);
      }
      this.user = null;
      this.isAuthenticated = false;
      router.push("/login");
    },
  },
});
