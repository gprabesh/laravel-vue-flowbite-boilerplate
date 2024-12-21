import "../../node_modules/flowbite-vue/dist/index.css";
import "../css/main.css";
import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import { AxiosPlugin } from "./plugins/axios";
import { useDarkModeStore } from "./stores/darkMode";
import DataLoader from "@/components/DataLoader.vue";
import DateRangeSearch from "@/components/DateRangeSearch.vue";
import VoucherEntry from "@/components/Forms/VoucherEntry.vue";
import VoucherPreview from "@/components/VoucherPreview.vue";
import { Multiselect } from "vue-multiselect";

const pinia = createPinia();
const app = createApp(App);
app.use(router);
app.use(pinia);
app.use(AxiosPlugin);
app.component("DataLoader", DataLoader);
app.component("DateRangeSearch", DateRangeSearch);
app.component("VoucherEntry", VoucherEntry);
app.component("VoucherPreview", VoucherPreview);
app.component("Multiselect", Multiselect);

app.mount("#app");

const darkModeStore = useDarkModeStore(pinia);

if (
  (!localStorage["darkMode"] && window.matchMedia("(prefers-color-scheme: dark)").matches) ||
  localStorage["darkMode"] === "1"
) {
  darkModeStore.set(false);
}

const defaultDocumentTitle = import.meta.env.VITE_APP_NAME ?? "Finance";

router.afterEach((to) => {
  document.title = to.meta?.title ? `${to.meta.title} — ${defaultDocumentTitle}` : defaultDocumentTitle;
});
