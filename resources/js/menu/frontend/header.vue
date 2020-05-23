<template>
<nav  class="navbar navbar-expand navbar-dark bg-dark">
      <div class="navbar-nav">
        <router-link to="/" class="nav-item nav-link">Home</router-link>
        <a @click="logout" class="nav-item nav-link">Logout</a>
      </div>
    </nav>
    </template>  
<script>
export default {
  methods: {
    logout() {
      authenticationService.logout();
      router.push("/login");
    }
  }
}
</script>