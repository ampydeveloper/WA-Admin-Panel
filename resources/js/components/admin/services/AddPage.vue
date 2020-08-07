<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Add Services
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
          <router-link to="/admin/services">
            List
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
        <li>Add</li>
      </ul>
    </div>
    <div class="main_box">
      <v-container fluid>
        <v-row>
          <v-col cols="12" md="12" class="pl-0 pt-0 slide-left">
            <v-form
              ref="form"
              v-model="valid"
              lazy-validation
              @submit="save"
              class="custom_form_field"
              id="form_field"
            >
              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Service name</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <v-text-field
                    v-model="addForm.service_name"
                    required
                    :rules="[v => !!v || 'Service name is required.']"
                    label="Enter service name"
                    placeholder
                  ></v-text-field>
                  <!-- <template>
                  <div>
                    <v-text-field label="Service name" :rules="rules" hide-details="auto"></v-text-field>
                    <v-text-field label="Another input"></v-text-field>
                  </div>
                  </template>-->
                </v-col>
              </v-col>
              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Service Time</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <div class="custom-checkbox d-ib">
                    <input
                      type="checkbox"
                      class="pr-6"
                      v-model="addForm.slot_type"
                      :checked="true"
                      @change="getTime(1)"
                      value="1"
                      id="morningJob"
                    />
                    <label for="morningJob"></label>
                    <span class="checkbox-title mor_eve">Morning</span>
                  </div>
                  <div class="custom-checkbox d-ib">
                    <input
                      type="checkbox"
                      class="pr-6"
                      v-model="addForm.slot_type"
                      @change="getTime(2)"
                      value="2"
                      id="eveningJob"
                    />
                    <label for="eveningJob"></label>
                    <span class="checkbox-title mor_eve">Evening</span>
                  </div>
                  <div class="v-messages theme--light error--text" role="alert" v-if="!timeSlotErr">
                    <div class="v-messages__wrapper">
                      <div class="v-messages__message">Service time is required.</div>
                    </div>
                  </div>
                </v-col>
              </v-col>

              <v-col class="time-slots pt-0" cols="12" md="12" v-if="morningSlots.length">
                <v-col sm="2"></v-col>
                <v-col sm="9" class="pt-0 pb-0">
                  <template v-for="(timeSlot, index) in morningSlots">
                    <span
                      class="checkbox"
                      v-bind:class="[addForm.slot_time.includes(timeSlot.id) ? 'activeClass' : '']"
                    >
                      <input
                        type="checkbox"
                        @click="setTimeSlot(timeSlot.id, index)"
                        :value="timeSlot.id"
                        :id="timeSlot.id"
                      />
                      <label v-bind:for="timeSlot.id">{{timeSlot.slot_start+'-'+timeSlot.slot_end}}</label>
                    </span>
                  </template>
                </v-col>
              </v-col>

              <v-col class="time-slots pt-0 pb-0" cols="12" md="12" v-if="eveningSlots.length">
                <v-col sm="2"></v-col>
                <v-col sm="9" class="pt-0 pb-0">
                  <template v-for="(timeSlot, index) in eveningSlots">
                    <span
                      class="checkbox"
                      v-bind:class="[addForm.slot_time.includes(timeSlot.id) ? 'activeClass' : '']"
                    >
                      <input
                        type="checkbox"
                        @click="setTimeSlot(timeSlot.id, index)"
                        :value="timeSlot.id"
                        :id="timeSlot.id"
                      />
                      <label v-bind:for="timeSlot.id">{{timeSlot.slot_start+'-'+timeSlot.slot_end}}</label>
                    </span>
                  </template>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Service Price</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <v-text-field
                    label="Enter service price"
                    placeholder
                    type="number"
                    max="100"
                    min="0"
                    v-model="addForm.price"
                    :rules="priceRules"
                    required
                  ></v-text-field>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="textarea-parent pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Description</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <v-textarea
                    label="Enter description"
                    placeholder
                    rows="3"
                    auto-grow
                    clearable
                    clear-icon="cancel"
                    v-model="addForm.description"
                    :rules="descriptionRules"
                    required
                  ></v-textarea>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0 service-image1">
                  <label class="label_text">Service Image</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Drop or Browse your files"
                    v-bind:allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    v-on:processfile="handleProcessFile"
                    v-on:processfilerevert="handleRemoveFile"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png"
                  />
                  <div class="v-messages theme--light error--text" role="alert" v-if="docError">
                    <div class="v-messages__wrapper">
                      <div class="v-messages__message">Service Image is required.</div>
                    </div>
                  </div>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Service Rate</label>
                </v-col>
                <v-col sm="8" class="label-align pt-0 pb-0 radio-group-outer">
                  <v-radio-group
                    sm="4"
                    class="test"
                    row
                    v-model="addForm.service_rate"
                    :mandatory="false"
                    required
                    :rules="[v => !!v || 'Service rate is required.']"
                  >
                    <v-radio label="Per Load" value="1" class="mor_eve"></v-radio>
                    <v-radio label="Round" value="2" class="mor_eve"></v-radio>
                  </v-radio-group>
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
                    @click="save"
                    id="submit_btn"
                  >Add Service</v-btn>
                </v-col>
              </v-col>
            </v-form>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { serviceService } from "../../../_services/service.service";
import { router } from "../../../_helpers/router";
import { environment } from "../../../config/test.env";
import { authenticationService } from "../../../_services/authentication.service";

export default {
  components: {
    //      'image-component': imageVUE,
  },
  data() {
    return {
      loading: false,
      valid: true,
      avatar: null,
      docError: false,
      apiUrl: environment.apiUrl,
      timeSlotErr: true,
      addForm: {
        service_name: "",
        price: "",
        description: "",
        service_image: "",
        service_rate: "1",
        slot_type: ["1"],
        slot_time: [],
      },
      checkedSlot: {
        slot_type: "",
      },
      morningSlots: [],
      eveningSlots: [],
      priceRules: [(v) => !!v || "Service price is invalid/required"],
      descriptionRules: [(v) => !!v || "Service description is required"],
      myFiles: [],
    };
  },
  computed: {
    serverOptions() {
      const currentUser = JSON.parse(localStorage.getItem("currentUser"));
      return {
        url: this.apiUrl,
        withCredentials: false,
        process: {
          url: "uploadImage",
          headers: {
            Authorization: "Bearer " + currentUser.data.access_token,
          },
        },
        revert: {
          url: "deleteImage",
          headers: {
            Authorization: "Bearer " + currentUser.data.access_token,
          },
        },
      };
    },
    url() {
      if (this.file) {
        let parsedUrl = new URL(this.file);
        return [parsedUrl.pathname];
      } else {
        return null;
      }
    },
  },
  created() {},
  mounted: function () {
    this.getTime(1);
  },
  methods: {
    getTime(choosenCheckbox) {
      this.checkedSlot.slot_type = choosenCheckbox;
      serviceService.getTimeSlots(this.checkedSlot).then((response) => {
        //handle response
        if (response.status) {
          if (choosenCheckbox == 1) {
            if (this.morningSlots.length > 0) {
              for (var i = 0; i < this.morningSlots.length; i++) {
                if (this.addForm.slot_time.includes(this.morningSlots[i].id)) {
                  this.addForm.slot_time.splice(
                    this.addForm.slot_time.indexOf(this.morningSlots[i].id),
                    1
                  );
                }
              }
              this.morningSlots = [];
            } else {
              this.morningSlots = [];
              this.morningSlots = response.data;
            }
          } else {
            if (this.eveningSlots.length > 0) {
              for (var i = 0; i < this.eveningSlots.length; i++) {
                if (this.addForm.slot_time.includes(this.eveningSlots[i].id)) {
                  this.addForm.slot_time.splice(
                    this.addForm.slot_time.indexOf(this.eveningSlots[i].id),
                    1
                  );
                }
              }
              this.eveningSlots = [];
            } else {
              this.eveningSlots = [];
              this.eveningSlots = response.data;
            }
          }
        } else {
          this.timeSlotErr = false;
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    //set time slow
    setTimeSlot(timeSlotId, index) {
      var findIndex = this.addForm.slot_time.indexOf(timeSlotId);
      if (findIndex > -1) {
        this.addForm.slot_time.splice(findIndex, 1);
      } else {
        this.addForm.slot_time.push(timeSlotId);
      }
    },
    handleProcessFile: function (error, file) {
      this.addForm.service_image = file.serverId;
      this.docError = false;
    },
    handleRemoveFile: function (file) {
      this.addForm.service_image = "";
      this.docError = true;
    },
    save: function (e) {
      //stop page to reload
      e.preventDefault();

      //time slots validation
      if (this.addForm.slot_time.length > 0) {
        //morning check
        if (this.morningSlots.length > 0) {
          var checkMorning = 0;
          for (var i = 0; i < this.morningSlots.length; i++) {
            if (this.addForm.slot_time.includes(this.morningSlots[i].id)) {
              checkMorning++;
            }
          }
          //check if any morning selected
          if (checkMorning == 0) {
            this.$toast.open({
              message: "Please select atleast one morning time slot",
              type: "error",
              position: "top-right",
            });
            return false;
          }
        }

        //check for time slots
        if (this.eveningSlots.length > 0) {
          var checkEvening = 0;
          for (var i = 0; i < this.eveningSlots.length; i++) {
            if (this.addForm.slot_time.includes(this.eveningSlots[i].id)) {
              checkEvening++;
            }
          }
          //check if any morning selected
          if (checkEvening == 0) {
            this.$toast.open({
              message: "Please select atleast one evening time slot",
              type: "error",
              position: "top-right",
            });
            return false;
          }
        }
      } else {
        this.$toast.open({
          message: "Please select atleast one time slot",
          type: "error",
          position: "top-right",
        });
        return false;
      }
      //time slots validation

      if (this.addForm.service_image == "") {
        this.docError = true;
      }

      if (this.$refs.form.validate() && this.timeSlotErr && !this.docError) {
        if (this.loading) {
          return false;
        }
        //start loading
        this.loading = true;

        serviceService.add(this.addForm).then((response) => {
          //stop loading
          this.loading = false;
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "bottom-right",
            });
            //redirect to login
            const currentUser = authenticationService.currentUserValue;
            if (currentUser.data.user.role_id == 1) {
              router.push("/admin/services");
            } else {
              router.push("/manager/services");
            }
          } else {
            this.$toast.open({
              message: response.message,
              type: "error",
              position: "bottom-right",
            });
          }
        });
      }
    },
  },
};
</script>
