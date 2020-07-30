<template>
  <v-app-bar id="app-bar" class="custom-toolbar" absolute app color="transparent" flat height="75">
    <v-row class="user-image">
      <v-col class="user-image-inner" cols="12" md="12">
        <v-row class="float-right" justify="space-around">
          <v-menu bottom origin="center center" transition="scale-transition">
            <template v-slot:activator="{ on }">
              <span class="logged-name">
                <span class="log-name">
		{{userdata.first_name}} {{userdata.last_name}}
                </span>
                <span v-if="isAdmin">Admin</span>
                <span v-if="isManager">Manager</span>
                <span v-if="isDriver">Driver</span>
              </span>
   <v-list-item-avatar v-on="on">
                <img :src="profileImage" id="userImage" />
              </v-list-item-avatar>
            </template>
            <v-list class="header-right-menu">
              <v-list-item>
                <v-list-item-title v-if="isAdmin">
                  <router-link to="/admin/profile" class="nav-item nav-link">  <user-icon size="1.5x" class="custom-class"></user-icon>
Profile</router-link>
                </v-list-item-title>
                <v-list-item-title v-if="isAdmin">
                  <router-link to="/admin/changepassword" class="nav-item nav-link">  <edit-3-icon size="1.5x" class="custom-class"></edit-3-icon>
Change Password</router-link>
                </v-list-item-title>
                <v-list-item-title v-if="isManager">
                  <router-link to="/manager/profile" class="nav-item nav-link">  <user-icon size="1.5x" class="custom-class"></user-icon>
Profile</router-link>
                </v-list-item-title>
                <v-list-item-title v-if="isManager">
                  <router-link
                    to="/manager/changepassword"
                    class="nav-item nav-link"
                  >Change Password</router-link>
                </v-list-item-title>
                <v-list-item-title v-if="isDriver">
                  <router-link to="/driver/profile" class="nav-item nav-link"><user-icon size="1.5x" class="custom-class"></user-icon>Profile</router-link>
                </v-list-item-title>
                <v-list-item-title v-if="isDriver">
                  <router-link to="/driver/changepassword" class="nav-item nav-link">  <edit-3-icon size="1.5x" class="custom-class"></edit-3-icon>
Change Password</router-link>
                </v-list-item-title>
                <v-list-item-title v-if="isAdmin">
                  <router-link to="/admin/admin/add" class="nav-item nav-link">  <user-plus-icon size="1.5x" class="custom-class"></user-plus-icon>
Add Admin</router-link>
                </v-list-item-title>
                <v-list-item-title v-if="isAdmin">
                  <router-link to="/admin/admin" class="nav-item nav-link">  <list-icon size="1.5x" class="custom-class"></list-icon>
List Admin</router-link>
                </v-list-item-title>
                <v-list-item-title>
                  <button type="button" @click="logout" class="nav-item nav-link">  <log-out-icon size="1.5x" class="custom-class"></log-out-icon>
 Logout</button>
                </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-row>
              <span class="notification"><bell-icon size="1.5x" class="custom-class"></bell-icon><span class="count">5</span></span>
      </v-col>
    </v-row>
    <!-- <v-btn class="mr-3" elevation="1" fab small @click="setDrawer(!drawer)">
      <v-icon v-if="value">mdi-view-quilt</v-icon>

      <v-icon v-else>mdi-dots-vertical</v-icon>
    </v-btn>-->

    <div class="header-right">
      <v-icon v-if="!drawer" @click="setDrawer(!drawer)">mdi-format-indent-increase</v-icon>
      <v-icon v-if="drawer" @click="setDrawer(!drawer)">mdi-format-indent-decrease</v-icon>
      <span class="page-title">Overview</span>
    </div>

    <!--  <v-spacer />

    <div class="mx-3" />-->

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
import { UserIcon, LogOutIcon, UserPlusIcon, ListIcon, Edit3Icon, BellIcon } from 'vue-feather-icons'

export default {
  name: "DashboardCoreAppBar",

  components: {
        UserIcon,
	LogOutIcon,
	UserPlusIcon,
	ListIcon,
	Edit3Icon,
	BellIcon,
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
    profileImage: "",
    isManager: false,
    isDriver: false,
    isAdmin: false,
    userdata: '',
  }),

  created() {
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    this.userdata = currentUser.data.user;
    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else if (currentUser.data.user.role_id == 2) {
      this.isManager = true;
    } else if (currentUser.data.user.role_id == 3) {
      this.isDriver = true;
    } else {
      this.isAdmin = true;
    }
    this.loadProfileImage();
  },
  computed: {
    ...mapState(["drawer"])
  },

  watch: {
    'drawer'(newVal) {
      if(this.drawer) {
        document.getElementById("app-bar").style.width = "calc(100% - 260px)";
      } else {
        document.getElementById("app-bar").style.width = "unset";
      }
    }
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
