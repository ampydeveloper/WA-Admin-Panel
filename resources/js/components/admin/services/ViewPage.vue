<template>
  <v-app>
    <v-container fluid>
      <v-row>
        <h2>View Service</h2>

        <v-subheader>Service</v-subheader>
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>Name</v-list-item-title>
            <v-list-item-subtitle>{{services}}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { serviceService } from "../../../_services/service.service";
export default {
  data() {
    return {
      avatar: null,
      services: "",
    };
  },
  mounted: function () {
    serviceService.getService(this.$route.params.id).then((response) => {
      //handle response
      if (response.status) {
        this.services = response.data;
      } else {
        router.push("/admin/services");
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right",
        });
      }
    });
  },
  methods: {},
};
</script>
