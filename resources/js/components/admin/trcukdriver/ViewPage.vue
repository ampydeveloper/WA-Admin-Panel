<template>
  <v-app></v-app>
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
      date: "",
      user_image: "",
      active: 0,
      addForm: {
        driver_name: "",
        email: "",
        driver_licence: "",
        expiry_date: "",
        salary_type: "",
        document: "",
        user_image: "",
        driver_address: "",
        driver_city: "",
        driver_state: "",
        driver_country: "",
        driver_zipcode: "",
        driver_phone: ""
      }
    };
  },
  computed: {},
  created() {
    driverService.getDriver(this.$route.params.id).then(response => {
      if (response.status) {
        this.addForm.user_id = response.data.user.id;
        if (response.data.user.user_image) {
          this.addForm.user_image = response.data.user.user_image;
        }
        if (response.data.user_image) {
          this.avatar = "../../../" + response.data.user.user_image;
        } else {
          this.avatar = "/images/avatar.png";
        }
        this.addForm.driver_name = response.data.user.first_name;
        this.addForm.email = response.data.user.email;
        this.addForm.phone = response.data.user.phone;
        this.active = response.data.salary_type;
        this.addForm.driver_licence = response.data.driver_licence;
        this.date = response.data.expiry_date;
        this.addForm.salary_type = response.data.salary_type;
        this.addForm.document = response.data.document;
        this.addForm.phone = response.data.user.phone;
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
