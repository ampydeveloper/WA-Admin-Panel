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

     <v-list>
          <v-list-group
            v-for="item in items"
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

  export default {
    name: 'DashboardCoreDrawer',
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
              { title: 'Overview', url: '/admin/dashboard' },
              { title: 'Jobs', url: '/admin/jobs'  },
              { title: 'Dispatches', url: '/admin/dispatches'  },
              { title: 'Services', url: '/admin/services'  }
            ]
          },
          {
            action: 'local_activity',
            title: 'Customer',
            items: [
              { title: 'Customer', url: '/admin/customer'  },
              { title: 'Customer Compnay', url: '/admin/company'  }
            ]
          },
          {
            action: 'local_activity',
            title: 'Employee',
            items: [
              { title: 'Managers', url: '/admin/manager' },
	      { title: 'Drivers', url: '/admin/truckdrivers' }
            ]
          },
          {
            action: 'local_activity',
            title: 'Fleet',
            items: [
              { title: 'Truck', url: '/admin/trucks' },
	      { title: 'SkidSteer', url: '/admin/skidsteers' }
            ]
          },
          {
            action: 'local_activity',
            title: 'Accounts',
            items: [
               { title: 'Accountings',url: '/admin/accounting' },
              { title: 'Reports', url: '/admin/reports' }
            ]
          }
        ]
    }),

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
        return this.items.map(this.mapItem)
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
