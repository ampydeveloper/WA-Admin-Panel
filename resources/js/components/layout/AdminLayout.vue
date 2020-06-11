<template>
  <v-app>
    <dashboard-core-app-bar />

    <dashboard-core-drawer />

    <dashboard-core-view />
  </v-app>
</template>

<script>
  export default {
    name: 'DashboardIndex',

    components: {
      DashboardCoreAppBar: () => import('./AppBar'),
      DashboardCoreDrawer: () => import('./Drawer'),
      DashboardCoreView: () => import('./View'),
    },

    data: () => ({
      expandOnHover: false,
    }),
  }
</script>
