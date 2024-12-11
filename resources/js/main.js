import "../../node_modules/flowbite-vue/dist/index.css";
import "../css/main.css";
import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import { AxiosPlugin } from "./plugins/axios";
import { useDarkModeStore } from "./stores/darkMode";

const pinia = createPinia();
createApp(App).use(router).use(pinia).use(AxiosPlugin).mount("#app");

const darkModeStore = useDarkModeStore(pinia);

if (
  (!localStorage["darkMode"] && window.matchMedia("(prefers-color-scheme: dark)").matches) ||
  localStorage["darkMode"] === "1"
) {
  darkModeStore.set(true);
}

const defaultDocumentTitle = "Admin One Vue 3 Tailwind";

router.afterEach((to) => {
  document.title = to.meta?.title ? `${to.meta.title} — ${defaultDocumentTitle}` : defaultDocumentTitle;
});
