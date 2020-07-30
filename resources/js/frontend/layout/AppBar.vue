<template>
  <v-app-bar id="app-bar" class="custom-toolbar" absolute app color="transparent" flat height="75">
    <v-btn class="mr-3" elevation="1" fab small>
      <v-icon v-if="value">mdi-view-quilt</v-icon>

      <v-icon v-else>mdi-dots-vertical</v-icon>
    </v-btn>

    <v-row class="user-image">
      <v-col class="user-image-inner" cols="12" md="12">
        <v-row class="float-right" justify="space-around">
          <v-menu bottom origin="center center" transition="scale-transition">

            <v-list class="header-right-menu">
              <v-list-item>
                <v-list-item-title>
                    Profile
                </v-list-item-title>
                <v-list-item-title>
                    User Management
                </v-list-item-title>
              </v-list-item>
            </v-list>

          </v-menu>
        </v-row>
      </v-col>
    </v-row>

  </v-app-bar>
</template>