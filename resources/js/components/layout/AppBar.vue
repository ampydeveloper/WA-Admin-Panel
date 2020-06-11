<template>
  <v-app-bar id="app-bar" class="custom-toolbar" absolute app color="transparent" flat height="75">
    <v-btn class="mr-3" elevation="1" fab small @click="setDrawer(!drawer)">
      <v-icon v-if="value">mdi-view-quilt</v-icon>

      <v-icon v-else>mdi-dots-vertical</v-icon>
    </v-btn>

    <!--  <v-spacer />

    <div class="mx-3" /> -->

    <v-row class="user-image">
      <v-col class="user-image-inner" cols="12" md="12">
        <v-row class="float-right" justify="space-around">
          <v-menu bottom origin="center center" transition="scale-transition">
            <template v-slot:activator="{ on }">
              <v-list-item-avatar v-on="on">
                <img :src="profileImage" id="userImage" />
              </v-list-item-avatar>
            </template>
            <v-list class="header-right-menu">
              <v-list-item>
                <v-list-item-title>
                  <router-link to="/admin/profile" class="nav-item nav-link">Profile</router-link>
                </v-list-item-title>
                <v-list-item-title>
                  <router-link to="/admin/changepassword" class="nav-item nav-link">Change Password</router-link>
                </v-list-item-title>
                <v-list-item-title>
                  <router-link to="/admin/admin/add" class="nav-item nav-link">Add Admin</router-link>
                </v-list-item-title>
                <v-list-item-title>
                  <router-link to="/admin/admin" class="nav-item nav-link">List Admin</router-link>
                </v-list-item-title>
                <v-list-item-title>
                  <button type="button" @click="logout" class="nav-item nav-link">Logout</button>
                </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-row>
      </v-col>
    </v-row>

    <!-- <v-toolbar-title class="hidden-sm-and-down font-weight-normal main-title mt-5" v-text="$route.name" />  -->

  </v-app-bar>
</template>

<script>
import { authenticationService } from "../../_services/authentication.service";
import { router } from "../../_helpers/router";
import { environment } from "../../config/test.env";
// Components
import { VHover, VListItem } from "vuetify/lib";

// Utilities
import { mapState, mapMutations } from "vuex";

export default {
  name: "DashboardCoreAppBar",

  components: {
    AppBarItem: {
      render(h) {
        return h(VHover, {
          scopedSlots: {
            default: ({ hover }) => {
              return h(
                VListItem,
                {
                  attrs: this.$attrs,
                  class: {
                    "black--text": !hover,
                    "white--text secondary elevation-12": hover
                  },
                  props: {
                    activeClass: "",
                    dark: hover,
                    link: true,
                    ...this.$attrs
                  }
                },
                this.$slots.default
              );
            }
          }
        });
      }
    }
  },

  props: {
    value: {
      type: Boolean,
      default: false
    }
  },

  data: () => ({
    profileImage: ""
  }),

  created() {
    this.loadProfileImage();
  },
  computed: {
    ...mapState(["drawer"])
  },

  methods: {
    ...mapMutations({
      setDrawer: "SET_DRAWER"
    }),
    loadProfileImage() {
      const currentUser = JSON.parse(localStorage.getItem("currentUser"));
      if (currentUser.data.user.user_image) {
        this.profileImage = "../../" + currentUser.data.user.user_image;
      } else {
        this.profileImage = "/images/avatar.png";
      }
    },
    logout() {
      authenticationService.logout();
      router.push("/login");
    }
  }
};
</script>
