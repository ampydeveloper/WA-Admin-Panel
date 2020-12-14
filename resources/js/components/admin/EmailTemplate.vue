<template>
  <v-container id="dashboard" fluid tag="section">
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title top_heading">
            Emails
            <span class="right-bor"></span>
          </h4>
        </li>
        <li>
          <router-link to="/admin/dashboard" class="home_svg">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24px"
              height="24px"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-home h-5 w-5 mb-1 stroke-current text-primary"
            >
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
              <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16px"
                height="16px"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-chevrons-right w-4 h-4"
              >
                <polyline points="13 17 18 12 13 7" />
                <polyline points="6 17 11 12 6 7" />
              </svg>
            </span>
          </router-link>
        </li>
        <li>
          <router-link to="/admin/jobs">
            Emails
            <span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16px"
                height="16px"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-chevrons-right w-4 h-4"
              >
                <polyline points="13 17 18 12 13 7" />
                <polyline points="6 17 11 12 6 7" />
              </svg>
            </span>
          </router-link>
        </li>
        <li>All</li>
      </ul>
    </div>

    <div class="main_box">
      <v-container fluid>
        <v-row>
          <v-col cols="12" md="12">
            <v-form
              ref="form"
              v-model="valid"
              class="v-form custom_form_field"
              id="form_field"
              lazy-validation
            >
              <v-row>
                <v-col class="pt-0 pb-0" cols="12">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Email Type</label>
                  </v-col>
                  <v-col sm="9" class="pt-0">
                    <v-select
                      v-model="addForm.emails_type"
                      :items="emailsType"
                      label="Select Email Type"
                      :rules="[(v) => !!v || 'Email Type is required.']"
                      item-text="name"
                      item-value="id"
                      @change="getEmailDetails"
                    ></v-select>
                  </v-col>
                </v-col>
                <v-col cols="12" class="textarea-parent pt-0 pb-0" v-if="subContent">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Subject</label>
                  </v-col>
                  <v-col sm="9" class="pt-0">
                    <v-text-field
                      label="Enter Subject"
                      v-model="addForm.subject"
                      required
                      :rules="[(v) => !!v || 'Subject is required.']"
                      placeholder
                    ></v-text-field>
                  </v-col>
                </v-col>

                <v-col cols="12" class="textarea-parent pt-0 pb-0" v-if="subContent">
                  <v-col sm="2" class="label-align pt-0">
                    <label>Content</label>
                  </v-col>
                  <v-col sm="9" class="pt-0">
                    <ckeditor
                      label="Enter Content"
                      placeholder
                      rows="3"
                      auto-grow
                      v-model="addForm.content"
                      :rules="[(v) => !!v || 'Content is required.']"
                      required
                    ></ckeditor>
                  </v-col>
                </v-col>

                <v-col class="pt-0 pb-0" cols="12" md="12">
                  <v-col sm="2"></v-col>
                  <v-col sm="9" class="pt-0 pb-0">
                    <v-btn
                      type="submit"
                      :loading="loading"
                      :disabled="loading"
                      color="success"
                      class="custom-save-btn mt-4"
                      @click="submit"
                      id="submit_btn"
                      >Update</v-btn
                    >
                  </v-col>
                </v-col>
              </v-row>
            </v-form>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-container>
</template>

<script>
import { jobService } from "../../_services/job.service";
export default {
  data() {
    return {
      loading: false,
      valid: true,
      subContent: false,
      emails_type: "",
      subject: "",
      content: "",
      emailsType: [],
      addForm: {
        emails_type: "",
        email_id: "",
        subject: "",
        content: "",
      },
    };
  },
  mounted() {
    jobService.getEmails().then((response) => {
      //handle response
      if (response.status) {
        this.emailsType = response.data;
      } else {
        this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right",
        });
      }
    });
  },
  created() {},
  methods: {
    getEmailDetails(val) {
        this.subContent = true;
      var i;
      for (i = 0; i < this.emailsType.length; i++) {
        if (val == this.emailsType[i].id) {
             this.addForm.email_id = this.emailsType[i].id;
          this.addForm.subject = this.emailsType[i].subject;
          this.addForm.content = this.emailsType[i].content;
        }
      }
    },
    submit() {
      if (this.$refs.form.validate()) {
        this.loading = true;
        jobService.updateEmail(this.addForm).then((response) => {
          //stop loading
          this.loading = false;
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
            });
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right",
            });
          }
        });
      }
    },
  },
};
</script>