<template>
 
    <v-row>
     <v-col cols="8" md="8">
 <v-card
    class="mx-auto"
    outlined
  >
    <v-list-item three-line>
      <v-list-item-content>
        <div class="overline mb-4">{{currentUser.first_name}} {{currentUser.last_name}}</div>
        <v-list-item-title class="headline mb-1">{{currentUser.email}}</v-list-item-title>
      </v-list-item-content>

      <v-list-item-avatar
        tile
        size="80"
        color="grey"
      >
   <img v-if="currentUser.user_image" :src="'../'+currentUser.user_image" alt="John" />
                      <img v-if="!currentUser.user_image" src="/images/avatar.png" alt="driver" />
</v-list-item-avatar>
    </v-list-item>

    <v-card-actions>
      <v-btn text @click="logout">logout</v-btn>
    </v-card-actions>
  <v-card-actions>
       <router-link to="payment" class="nav-item nav-link">
                    Payment
                    </router-link>
    </v-card-actions>
  </v-card>

</v-col>

  </v-row>
</template>

<script>
import { authenticationService } from "../_services/authentication.service";
import { router } from "../_helpers/router";
  export default {
    data () {
      return {
        colors: [
          'primary',
          'secondary',
          'yellow darken-2',
          'red',
          'orange',
        ],
	currentUser: '',
        model: 1,
      }
    },
    mounted() {
       var Userdata = JSON.parse(localStorage.getItem("currentUser"));
	this.currentUser = Userdata.data.user;
   },
  methods: {
    logout() {
      authenticationService.logout();
      router.push("/login");
    }
  }
  }
</script>
