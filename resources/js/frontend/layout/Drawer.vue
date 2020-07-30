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
  <p>abcd</p>
  <!--
    <template v-slot:img="props">
      <v-img
        :gradient="`to bottom, ${barColor}`"
        v-bind="props"
      />
    </template>
      </v-list-item>
    </v-list>

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
    </v-list> -->
  </v-navigation-drawer>
</template>

<script>

  export default {
    name: 'UserCoreDrawer',
  }

</script>
