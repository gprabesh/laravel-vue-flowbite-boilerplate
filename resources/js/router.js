import { createRouter, createWebHistory } from "vue-router";
import Home from "@/views/HomeView.vue";
import JournalVoucher from "@/pages/JournalVoucher.vue";
import Ledger from "@/pages/Ledger.vue";
import TrialBalance from "@/pages/TrialBalance.vue";
import AccountCategory from "@/pages/setups/AccountCategory.vue";
import Account from "@/pages/setups/Account.vue";
import User from "@/pages/setups/User.vue";
import { useUserStore } from "@/stores/user";

const routes = [
  {
    meta: {
      title: "Journal Voucher",
      requiresAuth: true,
    },
    path: "/",
    name: "default",
    component: JournalVoucher,
  },
  {
    meta: {
      title: "Journal Voucher",
      requiresAuth: true,
    },
    path: "/journal-voucher",
    name: "journal-voucher",
    component: JournalVoucher,
  },
  {
    meta: {
      title: "Dashboard",
      requiresAuth: true,
    },
    path: "/dashboard",
    name: "dashboard",
    component: Home,
  },
  {
    meta: {
      title: "Ledger",
      requiresAuth: true,
    },
    path: "/ledger",
    name: "ledger",
    component: Ledger,
  },
  {
    meta: {
      title: "Trial Balance",
      requiresAuth: true,
    },
    path: "/trial-balance",
    name: "trial-balance",
    component: TrialBalance,
  },
  {
    meta: {
      title: "Account Categories",
      requiresAuth: true,
    },
    path: "/account-categories",
    name: "account-categories",
    component: AccountCategory,
  },
  {
    meta: {
      title: "Accounts",
      requiresAuth: true,
    },
    path: "/accounts",
    name: "accounts",
    component: Account,
  },
  {
    meta: {
      title: "Users",
      requiresAuth: true,
    },
    path: "/users",
    name: "users",
    component: User,
  },

  {
    meta: {
      title: "Tables",
    },
    path: "/tables",
    name: "tables",
    component: () => import("@/views/TablesView.vue"),
  },
  {
    meta: {
      title: "Forms",
      requiresAuth: true,
    },
    path: "/forms",
    name: "forms",
    component: () => import("@/views/FormsView.vue"),
  },
  {
    meta: {
      title: "Profile",
      requiresAuth: true,
    },
    path: "/profile",
    name: "profile",
    component: () => import("@/views/ProfileView.vue"),
  },
  {
    meta: {
      title: "Ui",
    },
    path: "/ui",
    name: "ui",
    component: () => import("@/views/UiView.vue"),
  },
  {
    meta: {
      title: "Responsive layout",
    },
    path: "/responsive",
    name: "responsive",
    component: () => import("@/views/ResponsiveView.vue"),
  },
  {
    meta: {
      title: "Login",
    },
    path: "/login",
    name: "login",
    component: () => import("@/views/LoginView.vue"),
  },
  {
    meta: {
      title: "Error",
    },
    path: "/error",
    name: "error",
    component: () => import("@/views/ErrorView.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0 };
  },
});
router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore();
  if (to.meta.requiresAuth) {
    await userStore.fetchUser();
  }
  if (to.matched.some((record) => record.meta.requiresAuth) && !userStore.isAuthenticated) {
    console.log("redirecting");
    next("/login");
  } else {
    next();
  }
});

export default router;
