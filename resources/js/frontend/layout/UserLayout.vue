<template>
  <v-app>
    <user-core-app-bar />

    <user-core-drawer />

    <user-core-view />
  </v-app>
</template>

<script>
  export default {
    name: 'userIndex',

    components: {
      UserCoreAppBar: () => import('./AppBar'),
      UserCoreDrawer: () => import('./Drawer'),
      UserCoreView: () => import('./View'),
    },

    data: () => ({
      expandOnHover: false,
    }),
  }
</script>
