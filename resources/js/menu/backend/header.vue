<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      clipped
    >
      <v-list dense>
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title> 
              <!-- <a href="/admin/dashboard" class="nav-item nav-link">Dashboard</a> -->
              <router-link to="/admin/dashboard" class="nav-item nav-link">Dashboard</router-link>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-settings</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Settings</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      clipped-left
    >
        <v-row>
           <v-col cols="3" md="3"> 
               <div class="float-right">
                <!--<img :src="`images/logo.png`" alt="">-->
               </div>
               <div class="float-left"><v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon></div>
              </v-col>
           <v-col cols="9" md="9"> 
           <v-row class="float-right" justify="space-around">
    <v-menu
      bottom
      origin="center center"
      transition="scale-transition"
    >
      <template v-slot:activator="{ on }">
               <v-list-item-avatar v-on="on">
              <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John">
            </v-list-item-avatar>
      </template>
       <v-list>
        <v-list-item>
          <v-list-item-title>
            <!-- <a href="/admin/profile" class="nav-item nav-link">Profile</a> -->
            <router-link to="/admin/profile" class="nav-item nav-link">Profile</router-link>
          </v-list-item-title>
          <v-list-item-title>  
            <button type="button" @click="logout" class="nav-item nav-link">Logout</button>
            <!-- <router-link @click="logout" to="" class="nav-item nav-link">Logout</router-link> -->
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </v-row>
           </v-col>
        </v-row>
   
    </v-app-bar>
  </v-app>
</template>

<script>
    import { authenticationService } from "../../_services/authentication.service";
import { router } from "../../_helpers/router";
  export default {
    props: {
      source: String,
    },

    data: () => ({
      drawer: null,
    }),

    created () {
    
    },
    methods: {
    logout() {
      authenticationService.logout();
      router.push("/login");
    }
  }
  }
</script>