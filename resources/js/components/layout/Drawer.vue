<template>
  <v-navigation-drawer
    id="core-navigation-drawer"
    class="sidebar-nav"
    v-model="drawer"
    :dark="barColor !== 'rgba(228, 226, 226, 1), rgba(255, 255, 255, 0.7)'"
    :expand-on-hover="expandOnHover"
    :right="$vuetify.rtl"
    mobile-break-point="960"
    app
    width="260"
    v-bind="$attrs"
  >
    <template v-slot:img="props">
      <v-img
        :gradient="`to bottom, ${barColor}`"
        v-bind="props"
      />
    </template>
      </v-list-item>
    </v-list>

    <!-- <v-divider class="mb-2" /> -->

    <v-list
      expand
      nav
    >
      <div />

     <!-- <v-list v-if="isAdmin">

<v-list-group>
<template v-slot:activator>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Main</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </template>

            <v-list-item>
              <v-list-item-action>
                <bell-icon size='1.5x' class='custom-class'></bell-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title v-on:click="showAdvanced(subIindex, mainIndex)">
                  <router-link to="/admin/dashboard" class="nav-item nav-link">Overview</router-link>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
             <v-list-item>
              <v-list-item-action>
                <bell-icon size='1.5x' class='custom-class'></bell-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title v-on:click="showAdvanced(subIindex, mainIndex)">
                  <router-link to="/admin/dashboard" class="nav-item nav-link">Overview</router-link>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
  </v-list-group>
    </v-list> -->
    
<v-list v-if="isAdmin">
          <v-list-group
            v-for="(item, mainIndex) in items"
            :key="item.title"
            v-model="item.active"
            :prepend-icon="item.action"
            no-action
          >
            <template v-slot:activator>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </template>

            <v-list-item
              v-for="(subItem, subIindex) in item.items"
              :key="subItem.title"
              v-bind:class="{ 'overlay': isActive == subIindex+''+mainIndex }"
            >
              <v-list-item-action>
                <v-icon v-text="subItem.icon"></v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title v-on:click="showAdvanced(subIindex, mainIndex)">
                  <router-link :to="subItem.url" class="nav-item nav-link">{{ subItem.title }}</router-link>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>

          </v-list-group>
        </v-list>

     <v-list v-if="isManager">
          <v-list-group
            v-for="item in manageritems"
            :key="item.title"
            v-model="item.active"
            :prepend-icon="item.action"
            no-action
          >
            <template v-slot:activator>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </template>

            <v-list-item
              v-for="subItem in item.items"
              :key="subItem.title"
              @click=""
            >
              <v-list-item-content>
                <v-list-item-title>
		<router-link :to="subItem.url" class="nav-item nav-link">{{ subItem.title }}</router-link>
		</v-list-item-title>
              </v-list-item-content>

              <v-list-item-action>
                <v-icon>{{ subItem.action }}</v-icon>
              </v-list-item-action>
            </v-list-item>
          </v-list-group>
        </v-list>

   <v-list v-if="isDriver">
          <v-list-group
            v-for="item in driveritems"
            :key="item.title"
            v-model="item.active"
            :prepend-icon="item.action"
            no-action
          >
            <template v-slot:activator>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </template>

            <v-list-item
              v-for="subItem in item.items"
              :key="subItem.title"
              @click=""
            >
              <v-list-item-content>
                <v-list-item-title>
		<router-link :to="subItem.url" class="nav-item nav-link">{{ subItem.title }}</router-link>
		</v-list-item-title>
              </v-list-item-content>

              <v-list-item-action>
                <v-icon>{{ subItem.action }}</v-icon>
              </v-list-item-action>
            </v-list-item>
          </v-list-group>
        </v-list>


      <div />
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  // Utilities
  import { mapState } from 'vuex'
// import { UserIcon, LogOutIcon, UserPlusIcon, ListIcon, Edit3Icon, BellIcon } from 'vue-feather-icons';
  export default {
    name: 'DashboardCoreDrawer',

  //   components: {
  //       UserIcon,
	// LogOutIcon,
	// UserPlusIcon,
	// ListIcon,
	// Edit3Icon,
  // BellIcon,
  //   },
    props: {
      expandOnHover: {
        type: Boolean,
        default: false,
      },
    },

    data: () => ({
       items: [
          {
            action: 'local_activity',
            title: 'Main',
            active: true,
            items: [
              { title: 'Overview', url: '/admin/dashboard', icon: "mdi-view-dashboard" },
              { title: 'Jobs', url: '/admin/jobs', icon: 'mdi-wallet-travel' },
              { title: 'Dispatches', url: '/admin/dispatches', icon: 'mdi-cube-outline' },
              { title: 'Services', url: '/admin/services', icon: 'mdi-camera-timer' },
            ]
          },
          {
            action: 'local_activity',
            title: 'Customer',
            active: true,
            items: [
              { title: 'Customer', url: '/admin/customer', icon: 'mdi-account' },
              { title: 'Hauler', url: '/admin/hauler', icon: 'mdi-account-plus' }
            ]
          },
          {
            action: 'local_activity',
            title: 'Employee',
            active: true,
            items: [
              { title: 'Managers', url: '/admin/manager', icon: 'mdi-account-check' },
	            { title: 'Drivers', url: '/admin/truckdrivers', icon: 'mdi-account-outline' }
            ]
          },
          {
            action: 'local_activity',
            title: 'Fleet',
            active: true,
            items: [
              { title: 'Truck', url: '/admin/trucks', icon: 'mdi-truck' },
	            { title: 'SkidSteer', url: '/admin/skidsteers', icon: 'mdi-truck'  }
            ]
          },
          {
            action: 'local_activity',
            title: 'Accounts',
            active: true,
            items: [
               { title: 'Accountings',url: '/admin/accounting', icon: 'mdi-book-open' },
              { title: 'Reports', url: '/admin/reports', icon: 'mdi-file-document' }
            ]
          }
        ],
	 manageritems: [
          {
            action: 'local_activity',
            title: 'Main',
            active: true,
            items: [
              { title: 'Overview', url: '/manager/dashboard' },
               { title: 'Jobs', url: '/manager/jobs'  },
               { title: 'Dispatches', url: '/manager/dispatches'  },
            { title: 'Services', url: '/manager/services'  }
            ]
          },
          {
            action: 'local_activity',
            title: 'Customer',
            items: [
              { title: 'Customer', url: '/manager/customer'  },
              { title: 'Hauler', url: '/manager/company'  }
            ]
          },
          {
            action: 'local_activity',
            title: 'Employee',
            items: [
             { title: 'Managers', url: '/manager/manager' },
	      { title: 'Drivers', url: '/manager/truckdrivers' }
            ]
          },
          {
            action: 'local_activity',
            title: 'Fleet',
            items: [
            { title: 'Truck', url: '/manager/trucks' },
	     { title: 'SkidSteer', url: '/manager/skidsteers' }
            ]
          },
          {
            action: 'local_activity',
            title: 'Accounts',
            items: [
              { title: 'Accountings',url: '/manager/accounting' },
              { title: 'Reports', url: '/manager/reports' }
            ]
          }
        ],
	driveritems: [
	    {
            action: 'local_activity',
            active: true,
            title: 'Overview', url: '/driver/dashboard'
          },
         {
            action: 'local_activity',
            title: 'Ongoing Jobs', url: '/driver/dashboard'
          },
        {
            action: 'local_activity',
             title: 'Pending Jobs', url: '/driver/dashboard'
          },
        {
            action: 'local_activity',
             title: 'Delivered Jobs', url: '/driver/dashboard'
          },
        {
            action: 'local_activity',
             title: 'Monthly Earning', url: '/driver/dashboard'
          },

	],
	isManager: false,
	isDriver: false,
	isAdmin: false,
  isActive: null,
    }),
  created() {
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    if(currentUser.data.user.role_id == 1){
	this.isAdmin = true;
    }else if(currentUser.data.user.role_id == 2){
	 this.isManager = true;
    }else if(currentUser.data.user.role_id == 3){
	this.isDriver = true;
    }else{
	 this.isAdmin = true;
    }
  },
    computed: {
      ...mapState(['barColor', 'barImage']),
      drawer: {
        get () {
          return this.$store.state.drawer
        },
        set (val) {
          this.$store.commit('SET_DRAWER', val)
        },
      },
      computedItems () {
        const currentUser = JSON.parse(localStorage.getItem("currentUser"));
	if(currentUser.data.user.role_id == 1){
           return this.items.map(this.mapItem)
        }else if(currentUser.data.user.role_id == 2){
	  return this.manageritems.map(this.mapItem)
        }else if(currentUser.data.user.role_id == 3){
	return this.driveritems.map(this.mapItem)
        }else{
	 return this.items.map(this.mapItem)
	}
      },
      profile () {
        return {
          avatar: true,
         // title: this.$t('avatar'),
        }
      },
    },

    methods: {
      mapItem (item) {
        return {
          ...item,
          children: item.children ? item.children.map(this.mapItem) : undefined,
          //title: this.$t(item.title),
        }
      },
      showAdvanced: function(subIindex, mainIndex) {
        this.isActive = subIindex+''+mainIndex;
      },
    },
  }
</script>

<style lang="sass">
  @import '~vuetify/src/styles/tools/_rtl.sass'

  #core-navigation-drawer
    .v-list-group__header.v-list-item--active:before
      opacity: .24

    .v-list-item
      &__icon--text,
      &__icon:first-child
        justify-content: center
        text-align: center
        width: 20px

        +ltr()
          margin-right: 24px
          margin-left: 12px !important

        +rtl()
          margin-left: 24px
          margin-right: 12px !important

    .v-list--dense
      .v-list-item
        &__icon--text,
        &__icon:first-child
          margin-top: 10px

    .v-list-group--sub-group
      .v-list-item
        +ltr()
          padding-left: 8px

        +rtl()
          padding-right: 8px

      .v-list-group__header
        +ltr()
          padding-right: 0

        +rtl()
          padding-right: 0

        .v-list-item__icon--text
          margin-top: 19px
          order: 0

        .v-list-group__header__prepend-icon
          order: 2

          +ltr()
            margin-right: 8px

          +rtl()
            margin-left: 8px
</style>
