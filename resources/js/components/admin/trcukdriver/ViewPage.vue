<template>
  <v-app>{{driver}}</v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { driverService } from "../../../_services/driver.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
export default {
  components: {
    //      'image-component': imageVUE,
  },

  data() {
    return {
      menu2: false,
      valid: true,
      apiUrl: environment.apiUrl,
      avatar: null,
      driver: [],
    };
  },
  computed: {},
  created() {
    driverService.getDriver(this.$route.params.id).then(response => {
      if (response.status) {
      	this.driver = response.data;
      } else {
        router.push("/admin/drivers");
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right"
        });
      }
    });
  },
  methods: {}
};
</script>
