import {
  mdiAccountCircle,
  mdiMonitor,
  mdiGithub,
  mdiLock,
  mdiAlertCircle,
  mdiSquareEditOutline,
  mdiTable,
  mdiViewList,
  mdiTelevisionGuide,
  mdiResponsive,
  mdiPalette,
  mdiReact,
  mdiCogBox,
  mdiNewspaperVariantMultiple,
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
        label: "Company",
      },
    ],
  },
];
