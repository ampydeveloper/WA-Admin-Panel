<template>
  <v-app>
    <dashboard-core-app-bar />

    <dashboard-core-drawer />
    <transition name="fade" mode="out-in">
      <dashboard-core-view />
    </transition>
  </v-app>
</template>

<script>
export default {
  name: "DashboardIndex",

  components: {
    DashboardCoreAppBar: () => import("./AppBar"),
    DashboardCoreDrawer: () => import("./Drawer"),
    DashboardCoreView: () => import("./View"),
  },

  data: () => ({
    expandOnHover: false,
  }),
};
</script>
