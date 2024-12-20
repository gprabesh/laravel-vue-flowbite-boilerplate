import {
  mdiAccountCircle,
  mdiAlertCircle,
  mdiSquareEditOutline,
  mdiTable,
  mdiTelevisionGuide,
  mdiResponsive,
  mdiCogBox,
  mdiNewspaperVariantMultiple,
  mdiBookCheck,
} from "@mdi/js";

export default [
  // {
  //   to: "/dashboard",
  //   icon: mdiMonitor,
  //   label: "Dashboard",
  // },
  {
    to: "/journal-voucher",
    icon: mdiNewspaperVariantMultiple,
    label: "Journal Voucher",
  },
  {
    to: "/ledger",
    icon: mdiBookCheck,
    label: "Ledger",
  },
  {
    to: "/trial-balance",
    icon: mdiTable,
    label: "Trial Balance",
  },
  {
    to: "/tables",
    label: "Tables",
    icon: mdiTable,
  },
  {
    to: "/forms",
    label: "Forms",
    icon: mdiSquareEditOutline,
  },
  {
    to: "/ui",
    label: "UI",
    icon: mdiTelevisionGuide,
  },
  {
    to: "/responsive",
    label: "Responsive",
    icon: mdiResponsive,
  },
  {
    to: "/profile",
    label: "Profile",
    icon: mdiAccountCircle,
  },
  {
    to: "/error",
    label: "Error",
    icon: mdiAlertCircle,
  },
  {
    label: "Setup",
    icon: mdiCogBox,
    menu: [
      {
        label: "Account Category",
        to: "/account-categories",
      },
    ],
  },
];
