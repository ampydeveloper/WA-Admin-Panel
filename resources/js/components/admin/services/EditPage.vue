<template>
  <v-app>
    <div class="bread_crum">
      <ul>
        <li>
          <h4 class="main-title text-left top_heading">
            Update Service <span class="right-bor"></span>
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
            Services
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
        <li>Update</li>
      </ul>
    </div>
    <div class="main_box">
      <v-container fluid>
        <v-row>
          <v-col cols="12" md="12" class="slide-left pl-0 pt-0">
            <v-form
              ref="form"
              v-model="valid"
              lazy-validation
              @submit="update"
              class="custom_form_field"
              id="form_field"
            >
              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Service Name</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <v-text-field
                    label="Enter Service Name"
                    placeholder
                    v-model="editForm.service_name"
                    :rules="nameRules"
                    required
                  ></v-text-field>
                </v-col>
              </v-col>

              <!-- <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Service For</label>
                </v-col>
                <v-col sm="8" class="label-align pt-0 pb-0 radio-group-outer">
                  <v-radio-group
                    sm="4"
                    class="test"
                    row
                    v-model="editForm.service_for"
                    :mandatory="true"
                    required
                    :rules="[v => !!v || 'Service For is required.']"
                  >
                    <v-radio
                      label="Customer"
                      @change="getSelectedType(4)"
                      :value="4"
                      class="mor_eve"
                    ></v-radio>
                    <v-radio label="Hauler" @change="getSelectedType(6)" :value="6" class="mor_eve"></v-radio>
                  </v-radio-group>
                </v-col>
              </v-col>-->

              <div v-if="selectedType == 4">
                <v-col cols="12" md="12" class="pt-0 pb-0">
                  <v-col sm="2" class="label-align pt-0">
                    <label class="label_text">Service Time</label>
                  </v-col>
                  <v-col sm="4" class="pt-0 pb-0">
                    <div class="custom-checkbox d-ib">
                      <input
                        type="checkbox"
                        class="pr-6"
                        v-model="editForm.slot_type"
                        :checked="editForm.slot_type.includes(1) ? true : false"
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
                        v-model="editForm.slot_type"
                        :checked="editForm.slot_type.includes(2) ? true : false"
                        @change="getTime(2)"
                        value="2"
                        id="afternoonJob"
                      />
                      <label for="afternoonJob"></label>
                      <span class="checkbox-title mor_eve">Afternoon</span>
                    </div>

                    <div class="custom-checkbox d-ib">
                      <input
                        type="checkbox"
                        class="pr-6"
                        v-model="editForm.slot_type"
                        :checked="editForm.slot_type.includes(3) ? true : false"
                        @change="getTime(3)"
                        value="3"
                        id="eveningJob"
                      />
                      <label for="eveningJob"></label>
                      <span class="checkbox-title mor_eve">Evening</span>
                    </div>
                    <div
                      class="v-messages theme--light error--text"
                      role="alert"
                      v-if="!timeSlotErr"
                    >
                      <div class="v-messages__wrapper">
                        <div class="v-messages__message">
                          Service Time is required.
                        </div>
                      </div>
                    </div>
                  </v-col>
                </v-col>

                <v-col
                  class="time-slots pt-0"
                  cols="12"
                  md="12"
                  v-if="morningSlots.length"
                >
                  <v-col sm="2" class="pt-0"></v-col>
                  <v-col sm="8" class="pt-0 pb-0">
                    <template v-for="timeSlot in morningSlots">
                      <span
                        class="checkbox"
                        v-bind:class="[
                          editForm.slot_time.includes(timeSlot.id)
                            ? 'activeClass'
                            : '',
                        ]"
                      >
                        <input
                          type="checkbox"
                          @click="setTimeSlot(timeSlot.id)"
                          :value="timeSlot.id"
                          :id="timeSlot.id"
                          required
                          :checked="
                            editForm.slot_time.includes(timeSlot.id)
                              ? true
                              : false
                          "
                        />
                        <label v-bind:for="timeSlot.id">{{
                          timeSlot.slot_start + "-" + timeSlot.slot_end
                        }}</label>
                      </span>
                      <!-- <v-checkbox v-model="editForm.slot_time" :value="timeSlot.id" class="mx-2" :label="timeSlot.slot_start+'-'+timeSlot.slot_end"></v-checkbox> -->
                    </template>
                  </v-col>
                </v-col>

                <v-col
                  class="time-slots pt-0 pb-0"
                  cols="12"
                  md="12"
                  v-if="eveningSlots.length"
                >
                  <v-col sm="2" class="pt-0"></v-col>
                  <v-col sm="8" class="pt-0 pb-0">
                    <template v-for="timeSlot in eveningSlots">
                      <span
                        class="checkbox"
                        v-bind:class="[
                          editForm.slot_time.includes(timeSlot.id)
                            ? 'activeClass'
                            : '',
                        ]"
                      >
                        <input
                          type="checkbox"
                          @click="setTimeSlot(timeSlot.id)"
                          :value="timeSlot.id"
                          :id="timeSlot.id"
                          required
                          :checked="
                            editForm.slot_time.includes(timeSlot.id)
                              ? true
                              : false
                          "
                        />
                        <label v-bind:for="timeSlot.id">{{
                          timeSlot.slot_start + "-" + timeSlot.slot_end
                        }}</label>
                      </span>
                      <!-- <v-checkbox v-model="editForm.slot_time" :value="timeSlot.id" class="mx-2" :label="timeSlot.slot_start+'-'+timeSlot.slot_end"></v-checkbox> -->
                    </template>
                  </v-col>
                </v-col>
              </div>

              <v-col
                cols="12"
                md="12"
                class="pt-0 pb-0"
                v-if="selectedType == 4"
              >
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text"
                    >Complete Time <small>(in mins)</small></label
                  >
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <v-select
                    v-model="editForm.time_taken_to_complete_service"
                    :items="completeTime"
                    item-text="value"
                    item-value="id"
                    label="Select Complete Time"
                    :rules="[(v) => !!v || 'Complete Time is required.']"
                  ></v-select>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Service Price</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <v-text-field
                    type="number"
                    max="100"
                    min="0"
                    v-model="editForm.price"
                    :rules="priceRules"
                    label="Enter Service Price"
                    required
                  ></v-text-field>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text">Overhead Cost</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <v-text-field
                    type="number"
                    max="100"
                    min="0"
                    v-model="editForm.overhead_cost"
                    :rules="overheadCostRules"
                    label="Enter overhead cost"
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
                    rows="3"
                    auto-grow
                    v-model="editForm.description"
                    :rules="descRules"
                    label="Enter Description"
                    required
                  ></v-textarea>
                </v-col>
              </v-col>
              <v-col cols="12" md="12" class="pt-0 mb-4 pb-0">
                <v-col sm="2" class="label-align pt-0 image-upload-label">
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
                    v-on:addfilestart="setUploadIndex"
                    v-on:processfile="handleProcessFile"
                    v-on:processfilerevert="handleRemoveFile"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png"
                  />
                  <div
                    class="v-messages theme--light error--text"
                    role="alert"
                    v-if="docError"
                  >
                    <div class="v-messages__wrapper">
                      <div class="v-messages__message">
                        Document upload is required.
                      </div>
                    </div>
                  </div>
                  <v-col sm="12" class="p-0">
                    <div
                      class="service-image-outer"
                      v-if="editForm.service_image"
                    >
                      <button
                        type="button"
                        class="close"
                        v-if="cross"
                        @click="Remove()"
                      >
                        <span>&times;</span>
                      </button>
                      <img :src="editForm.service_image" alt />
                    </div>
                  </v-col>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <div v-if="selectedType == 4">
                  <v-col sm="2" class="label-align pt-0">
                    <label class="label_text">Service Type</label>
                  </v-col>
                  <v-col sm="8" class="label-align pt-0 pb-0 radio-group-outer">
                    <v-radio-group
                      row
                      v-model="editForm.service_type"
                      :mandatory="false"
                      required
                      :rules="[(v) => !!v || 'Service type is required.']"
                    >
                      <v-radio
                        label="Per Load"
                        :value="1"
                        class="mor_eve"
                      ></v-radio>
                      <v-radio
                        label="Trip"
                        :value="2"
                        class="mor_eve"
                      ></v-radio>
                      <v-radio
                        label="Bucket"
                        :value="3"
                        class="mor_eve"
                      ></v-radio>
                    </v-radio-group>
                  </v-col>
                </div>

                <v-col class="pt-0 pb-0" cols="12" md="12">
                  <v-row class="m-0">
                    <v-col sm="2"></v-col>
                    <v-col sm="10" class="p-0">
                      <v-btn
                        type="submit"
                        :loading="loading"
                        :disabled="loading"
                        color="success"
                        class="custom-save-btn mt-4"
                        @click="update"
                        id="submit_btn"
                        >Update</v-btn
                      >
                      <router-link
                        to="/admin/services"
                        class="btn-custom-danger mt-4"
                        >Cancel</router-link
                      >
                    </v-col>
                  </v-row>
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
      completeTime: [
        { id: 1, value: "15 Mins" },
        { id: 2, value: "30 Mins" },
        { id: 3, value: "45 Mins" },
        { id: 4, value: "60 Mins" },
        { id: 5, value: "75 Mins" },
        { id: 6, value: "90 Mins" },
      ],
      valid: true,
      loading: false,
      avatar: null,
      docError: false,
      apiUrl: environment.apiUrl,
      baseUrl: environment.baseUrl,
      timeSlotErr: true,
      selectedType: 4,
      cross: false,
      editForm: {
        service_id: "",
        service_name: "",
        service_for: "",
        price: "",
        overhead_cost: "",
        description: "",
        service_image: "",
        service_type: "",
        slot_type: [],
        time_taken_to_complete_service: 0,
      },
      checkedSlot: {
        slot_type: "",
      },
      morningSlots: [],
      eveningSlots: [],
      nameRules: [(v) => !!v || "Service Name is required."],
      priceRules: [(v) => !!v || "Service Price is invalid/required."],
      overheadCostRules: [(v) => !!v || "Overhead cost is invalid/required."],
      descRules: [(v) => !!v || "Service Description is required."],
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
  mounted: function () {
    serviceService.getService(this.$route.params.id).then((response) => {
      //handle response
      if (response.status) {
        this.editForm.service_id = response.data.id;
        this.editForm.service_name = response.data.service_name;
        this.editForm.price = response.data.price;
        this.editForm.overhead_cost = response.data.overhead_cost;
        this.editForm.description = response.data.description;
        this.editForm.service_image = response.data.service_image;
        this.editForm.time_taken_to_complete_service =
          response.data.time_taken_to_complete_service;
        this.editForm.slot_time = JSON.parse(
          JSON.stringify(response.data.slot_time)
        );
        this.editForm.slot_type = JSON.parse(
          JSON.stringify(response.data.slot_type)
        );

        this.editForm.service_for = response.data.service_for;

        //service for check
        this.selectedType = this.editForm.service_for;

        if (response.data.service_image) {
          this.cross = true;
        }
        this.editForm.service_type = response.data.service_type;

        // if (this.editForm.slot_type.includes("1")) {
        //   this.getTime(1);
        // }
        // if (this.editForm.slot_type.includes("2")) {
        //   this.getTime(2);
        // }
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
  methods: {
    setUploadIndex() {
      this.uploadInProgress = true;
    },
    Remove() {
      this.cross = false;
      this.editForm.service_image = "";
    },
    // getTime(choosenCheckbox) {
    //   this.checkedSlot.slot_type = choosenCheckbox;

    //   serviceService.getTimeSlots(this.checkedSlot).then((response) => {
    //     //handle response
    //     if (response.status) {
    //       if (choosenCheckbox == 1) {
    //         if (this.morningSlots.length > 0) {
    //           for (var i = 0; i < this.morningSlots.length; i++) {
    //             if (this.editForm.slot_time.includes(this.morningSlots[i].id)) {
    //               this.editForm.slot_time.splice(
    //                 this.editForm.slot_time.indexOf(this.morningSlots[i].id),
    //                 1
    //               );
    //             }
    //           }
    //           this.morningSlots = [];
    //         } else {
    //           this.morningSlots = [];
    //           this.morningSlots = response.data;
    //         }
    //       } else {
    //         if (this.eveningSlots.length > 0) {
    //           for (var i = 0; i < this.eveningSlots.length; i++) {
    //             if (this.editForm.slot_time.includes(this.eveningSlots[i].id)) {
    //               this.editForm.slot_time.splice(
    //                 this.editForm.slot_time.indexOf(this.eveningSlots[i].id),
    //                 1
    //               );
    //             }
    //           }
    //           this.eveningSlots = [];
    //         } else {
    //           this.eveningSlots = [];
    //           this.eveningSlots = response.data;
    //         }
    //       }
    //     } else {
    //       this.timeSlotErr = false;
    //       this.$toast.open({
    //         message: response.message,
    //         type: "error",
    //         position: "top-right",
    //       });
    //     }
    //   });
    // },
    getSelectedType(checkType) {
      this.selectedType = checkType;
    },
    //set time slow
    setTimeSlot(timeSlotId) {
      var findIndex = this.editForm.slot_time.indexOf(timeSlotId);
      if (findIndex > -1) {
        this.editForm.slot_time.splice(findIndex, 1);
      } else {
        this.editForm.slot_time.push(timeSlotId);
      }
    },
    handleProcessFile: function (error, file) {
      this.editForm.service_image = file.serverId;
      this.docError = false;
      this.uploadInProgress = false;
    },
    handleRemoveFile: function (file) {
      this.editForm.service_image = "";
      this.docError = true;
    },
    update: function (e) {
      //stop page to reload
      e.preventDefault();

      if (this.uploadInProgress) {
        this.$toast.open({
          message: "Image uploading is in progress.",
          type: "error",
          position: "top-right",
        });
        return false;
      }
      // if (this.editForm.service_type == "perload") {
      //   this.editForm.service_type = 1;
      // } else if (this.editForm.service_type == "trip") {
      //   this.editForm.service_type = 2;
      // } else {
      //   this.editForm.service_type = 3;
      // }

      //time slot validation if customer service selected
      // if (this.selectedType == 4) {
      //   //time slots validation
      //   if (this.editForm.slot_time.length > 0) {
      //     //morning check
      //     if (this.morningSlots.length > 0) {
      //       var checkMorning = 0;
      //       for (var i = 0; i < this.morningSlots.length; i++) {
      //         if (this.editForm.slot_time.includes(this.morningSlots[i].id)) {
      //           checkMorning++;
      //         }
      //       }
      //       //check if any morning selected
      //       if (checkMorning == 0) {
      //         this.$toast.open({
      //           message: "Please select at least one morning time slot.",
      //           type: "error",
      //           position: "top-right",
      //         });
      //         return false;
      //       }
      //     }

      //     //check for time slots
      //     if (this.eveningSlots.length > 0) {
      //       var checkEvening = 0;
      //       for (var i = 0; i < this.eveningSlots.length; i++) {
      //         if (this.editForm.slot_time.includes(this.eveningSlots[i].id)) {
      //           checkEvening++;
      //         }
      //       }
      //       //check if any morning selected
      //       if (checkEvening == 0) {
      //         this.$toast.open({
      //           message: "Please select at least one evening time slot.",
      //           type: "error",
      //           position: "top-right",
      //         });
      //         return false;
      //       }
      //     }
      //   } else {
      //     this.$toast.open({
      //       message: "Please select at least one time slot.",
      //       type: "error",
      //       position: "top-right",
      //     });
      //     return false;
      //   }
      //   //time slots validation
      // }

      if (
        this.editForm.service_image == "" ||
        this.editForm.service_image == null
      ) {
        this.docError = true;
      }

      if (this.$refs.form.validate() && !this.docError) {
        if (this.loading) {
          return false;
        }
        //start loading
        this.loading = true;

        serviceService.edit(this.editForm).then((response) => {
          //stop loading
          this.loading = false;
          //handle response
          if (response.status) {
            this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right",
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
              position: "top-right",
            });
          }
        });
      }
    },
  },
};
</script>