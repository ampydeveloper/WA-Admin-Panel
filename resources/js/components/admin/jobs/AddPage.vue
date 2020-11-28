<template>
  <v-app>
    <v-container fluid class="pt-0">
      <v-row>
        <div class="bread_crum">
          <ul>
            <li>
              <h4 class="main-title text-left top_heading">
                Create Pickup
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
                Pickups
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
            <li>Create</li>
          </ul>
        </div>

        <div class="main_box jap-mb">
          <v-form
            ref="form"
            v-model="valid"
            class="v-form custom_form_field"
            id="form_field"
            lazy-validation
          >
            <v-col class="pt-0 pb-0" cols="12">
              <v-col sm="2" class="label-align pt-0">
                <label>Customer</label>
              </v-col>
              <v-col sm="4" class="pt-0">
                <v-select
                  v-model="addForm.customer_id"
                  :items="customerName"
                  label="Select Customer"
                  :rules="[v => !!v || 'Customer is required.']"
                  item-text="first_name"
                  item-value="id"
                  @change="getCustomerFarm"
                ></v-select>
              </v-col>
            </v-col>

            <v-col class="pt-0 pb-0" cols="12" v-if="!customerNotHauler">
              <v-col sm="2" class="label-align pt-0">
                <label>Farm</label>
              </v-col>
              <v-col sm="4" class="pt-0">
                <v-select
                  :v-model="addForm.farm_id"
                  :items="farmName"
                  item-text="farm_address"
                  label="Select Farm"
                  :rules="[v => !!v || 'Farm is required.']"
                  item-value="id"
                  @change="getFarmManager"
                ></v-select>
              </v-col>
            </v-col>

            <v-col class="pt-0 pb-0" cols="12" v-if="!customerNotHauler">
              <v-col sm="2" class="label-align pt-0">
                <label>Farm Manager</label>
              </v-col>
              <v-col sm="4" class="pt-0">
                <v-select
                  :v-model="addForm.manager_id"
                  :items="managerName"
                  label="Select Manager"
                  :rules="[v => !!v || 'Manager is required.']"
                  item-text="first_name"
                  item-value="id"
                  @change="managerSelection"
                ></v-select>
              </v-col>
            </v-col>

            <v-col class="pt-0 pb-0" cols="12" v-if="customerNotHauler">
              <v-col sm="2" class="label-align pt-0">
                <label>Driver</label>
              </v-col>
              <v-col sm="4" class="pt-0 pb-0 farm-conatiner">
                <v-select
                  v-model="addForm.manager_id"
                  :items="driversList"
                  label="Select Driver"
                  item-text="first_name"
                  item-value="id"
                  :rules="[(v) => !!v || 'Driver is required.']"
                ></v-select>
              </v-col>
            </v-col>

            <v-col class="pt-0 pb-0" cols="12">
              <v-col sm="2" class="label-align pt-0">
                <label>Service</label>
              </v-col>
              <v-col sm="4" class="pt-0">
                <v-select
                  :v-model="addForm.service_id"
                  :items="serviceName"
                  label="Select Service"
                  :rules="[v => !!v || 'Service is required.']"
                  item-text="service_name"
                  item-value="id"
                  @change="serviceSelection"
                ></v-select>
              </v-col>
            </v-col>
            <v-col cols="12" class="pt-0 pb-0 service-time-timing-outer" v-if='slot_select'>
              <v-col sm="2" class="label-align pt-0">
                <label>Service Time</label>
              </v-col>
              <v-col sm="8" class="pt-0 pb-0 service-time-timing-out">
                <div class="pretty p-default p-round px-2" v-if='available_slots.includes(1) || available_slots.includes("1")'>
                  <input
                    type="radio"
                    name="slot_type"
                    v-model="addForm.time_slots_id"
                    value="1"
                  />
                  <div class="state">
                    <label>Morning</label>
                  </div>
                </div>
                <div class="pretty p-default p-round px-2" v-if='available_slots.includes(2) || available_slots.includes("2")'>
                  <input
                    type="radio"
                    name="slot_type"
                    v-model="addForm.time_slots_id"
                    value="2"
                  />
                  <div class="state">
                    <label>Afternoon</label>
                  </div>
                </div>
                <div class="pretty p-default p-round px-2" v-if='available_slots.includes(3) || available_slots.includes("3")'>
                  <input
                    type="radio"
                    name="slot_type"
                    v-model="addForm.time_slots_id"
                    value="3"
                  />
                  <div class="state">
                    <label>Evening</label>
                  </div>
                </div>
              </v-col>
            </v-col>
            <div id="showTimingSection" v-if="servicetime">
              <v-col cols="12" md="12" class="custom-col calendar-col pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label>Start Date</label>
                </v-col>
                <v-col sm="4" class="pt-0">
                  <v-menu
                    v-model="menu1"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="date"
                        label="Select Start Date"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        required
                        :rules="[v => !!v || 'Start date is required.']"
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="date" @input="menu1 = false" :min="minDate"></v-date-picker>
                  </v-menu>
                </v-col>
              </v-col>

              <v-col cols="12" class="pt-0 pb-0" v-if='customerNotHauler'>
                <v-col sm="2" class="label-align pt-0">
                  <label>Time</label>
                </v-col>
                <v-col sm="4" class="pt-0 pb-0">
                  <v-menu
                    ref="time"
                    v-model="timeMenu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="addForm.job_providing_time"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="addForm.job_providing_time"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        :rules="[(v) => !!v || 'Time is required.']"
                      ></v-text-field>
                    </template>
                    <v-time-picker
                      v-if="timeMenu"
                      v-model="addForm.job_providing_time"
                      full-width
                      format="24hr"
                      @click:minute="$refs.time.save(addForm.job_providing_time)"
                    ></v-time-picker>
                  </v-menu>
                </v-col>
              </v-col>

              <!-- <v-col cols="12" md="12" class="t-s-inner pt-0 pb-0">
                <div class="row">
                  <v-col sm="2" class="label-align pt-0 label_text label-full">
                    <label>Time Slots</label>
                  </v-col>
                  <v-col sm="10" class="pt-0">
                    <v-radio-group
                      row
                      v-model="addForm.time_slots_id"
                      :mandatory="false"
                      required
                      :rules="[v => !!v || 'Time slot is required.']"
                    >
                      <template v-for="(timeSlot, index) in servicetime">
                        <v-radio
                          :label="timeSlot.slot_start+'-'+timeSlot.slot_end"
                          :value="timeSlot.id"
                        ></v-radio>
                      </template>
                    </v-radio-group>
                  </v-col>
                </div>
              </v-col> -->

              <v-col class="pt-0 pb-0" cols="12" v-if="weightShow">
                <v-col sm="2" class="label-align pt-0">
                  <label>Weight</label>
                </v-col>
                <v-col sm="4" class="pt-0">
                  <v-select 
                    required
                    v-model="addForm.weight" 
                    :items=[5,10,15,20,25] 
                    :rules="[(v) => !!v || 'Weight is required.']"
                    hint="Tons"
                    persistent-hint
                  ></v-select>
                </v-col>
              </v-col>

              <v-col cols="12" class="textarea-parent pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label>Gate Number</label>
                </v-col>
                <v-col sm="4" class="pt-0">
                  <v-text-field
                    label="Enter Gate Number"
                    v-model="addForm.gate_no"
                    required
                    :rules="[v => !!v || 'Gate number is required.']"
                    placeholder
                  ></v-text-field>
                </v-col>
              </v-col>

              <v-col cols="12" md="12" class="pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0 image-upload-label">
                  <label class="label_text">Photos</label>
                </v-col>
                <v-col sm="4" class="pt-0">
                  <file-pond
                    name="uploadImage"
                    ref="pond"
                    label-idle="Drop or Browse your files"
                    allow-multiple="false"
                    v-bind:server="serverOptions"
                    v-bind:files="myFiles"
                    v-on:processfile="handleProcessFile"
                    allow-file-type-validation="true"
                    accepted-file-types="image/jpeg, image/png"
                  />
                </v-col>
              </v-col>

              <v-col cols="12" class="textarea-parent pt-0 pb-0">
                <v-col sm="2" class="label-align pt-0">
                  <label>Notes</label>
                </v-col>
                <v-col sm="4" class="pt-0">
                  <v-textarea
                    label="Enter Notes"
                    placeholder
                    rows="3"
                    auto-grow
                    v-model="addForm.notes"
                    :rules="[v => !!v || 'Notes is required.']"
                    required
                  ></v-textarea>
                </v-col>
              </v-col>

              <v-col class="pt-0 pb-0" cols="12">
                <v-col sm="2" class="label-align pt-0">
                  <label class="label_text label-check-half">Repeating</label>
                </v-col>
                <v-col sm="8" class="pt-0">
                  <v-switch v-model="addForm.is_repeating_job"></v-switch>
                  <v-row v-if="addForm.is_repeating_job" class="mb-3">
                    <v-col cols="12" sm="4" md="4" class="pt-0 pb-0" style="height: 29px;">
                      <v-checkbox color="success" v-model="addForm.repeating_days" label="Monday" value="monday"></v-checkbox>
                    </v-col>
                    <v-col cols="12" sm="4" md="4" class="pt-0 pb-0" style="height: 29px;">
                      <v-checkbox color="success" v-model="addForm.repeating_days" label="Tuesday" value="tuesday" hide-details></v-checkbox>
                    </v-col>
                    <v-col cols="12" sm="4" md="4" class="pt-0 pb-0" style="height: 29px;">
                      <v-checkbox color="success" v-model="addForm.repeating_days" label="Wednesday" value="wednesday" hide-details></v-checkbox>
                    </v-col>
                    <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                      <v-checkbox color="success" v-model="addForm.repeating_days" label="Thursday" value="thursday" hide-details></v-checkbox>
                    </v-col>
                    <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                      <v-checkbox color="success" v-model="addForm.repeating_days" label="Friday" value="friday" hide-details></v-checkbox>
                    </v-col>
                    <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                      <v-checkbox color="success" v-model="addForm.repeating_days" label="Saturday" value="saturday" hide-details></v-checkbox>
                    </v-col>
                    <v-col cols="12" sm="4" md="4" class="pt-0 pb-0">
                      <v-checkbox color="success" v-model="addForm.repeating_days" label="Sunday" value="sunday" hide-details></v-checkbox>
                    </v-col>
                  </v-row>
                </v-col>
              </v-col>
            </div>

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
                >Create Job</v-btn>
              </v-col>
            </v-col>
          </v-form>
        </div>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { router } from "../../../_helpers/router";
import { jobService } from "../../../_services/job.service";
import { environment } from "../../../config/test.env";
import { authenticationService } from "../../../_services/authentication.service";
import moment from 'moment';
export default {
  components: {},
  data() {
    return {
      minDate: moment().add(1, 'days').format("YYYY-MM-DD"),
      valid: true,
      setDate: new Date().toISOString().substr(0, 10),
      menu1: false,
      timeMenu: false,
      loading: false,
      weightShow: false,
      customerNotHauler: true,
      date: moment().add(1, 'days').format("YYYY-MM-DD"),
      job_providing_date: "",
      apiUrl: environment.apiUrl,
      customerName: [],
      managerName: [],
      serviceName: [],
      driversList: [],
      farmName: [],
      slot_select: false,
      available_slots: [],
      servicetime: "",
      customer_id: "",
      addForm: {
        customer_id: "",
        manager_id: "",
        service_id: "",
        notes: "",
        gate_no: "",
        farm_add: "",
        farm_id: "",
        images: [],
        repeating_days: [],
        time_slots_id: "",
        job_providing_date: "",
        job_providing_time: "",
        weight: "",
        amount: "",
        is_repeating_job: false,
      },
      killometerRules: [
        (v) => !!v || "Job weight is required.",
        //v => /^\d*$/.test(v) || "Enter valid weight",
      ],
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
  mounted() {
    this.getResults();
    this.getService();
  },
  methods: {
    handleProcessFile: function (error, file) {
      this.addForm.images.push(file.serverId);
    },
    getResults() {
      jobService.getCustomer().then((response) => {
        //handle response
        if (response.status) {
          this.customerName = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    getService() {
      jobService.listService().then((response) => {
        //handle response
        if (response.status) {
          this.serviceName = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    getCustomerFarm(val) {
      //checking is customer is Hauler
      var i;
      for (i = 0; i < this.customerName.length; i++) {
        if (
          val == this.customerName[i].id &&
          this.customerName[i].role_id == 6
        ) {
          this.customerNotHauler = true;
          this.getHaulerDrivers(this.customerName[i].id);
          return false;
         
        }else{
          this.customerNotHauler = false;
          
        }
      }

      this.customer_id = val;
      jobService.getFarm(val).then((response) => {
        //handle response
        if (response.status) {
          //console.log(response.data)
          this.farmName = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    getFarmManager(val) {
      this.addForm.farm_id = val;
      jobService.getFarmManager(val).then((response) => {
        //handle response
        if (response.status) {
          this.managerName = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    getHaulerDrivers(hid){
      jobService.getHaulerDrivers(hid).then((response) => {
        //handle response
        if (response.status) {
          this.driversList = response.data;
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    managerSelection(val) {
      this.addForm.manager_id = val;
    },
    serviceSelection(val) {
      this.addForm.service_id = val;
      this.serviceName.find((file) => {
        if (file.id == val) {
          this.addForm.amount = file.price;
          this.servicetime = true;
          if (file.service_type == 1) {
            this.weightShow = true;
          } else {
            this.addForm.weight = "";
            this.weightShow = false;
          }
          this.available_slots = [];
          
          this.slot_select = false;
          if(file.slot_type && file.slot_type.length > 0){
            this.slot_select = true;
            this.available_slots = file.slot_type;
          }
        }
      });


      // jobService.servicesTimeSlots(val).then((response) => {
      //   //handle response
      //   if (response.status) {
      //     console.log(response);
      //     this.servicetime = response.data;
      //   } else {
      //     this.$toast.open({
      //       message: response.message,
      //       type: "error",
      //       position: "top-right",
      //     });
      //   }
      // });
    },
    submit() {
      //start loading
      this.loading = true;
      this.addForm.job_providing_date = this.date;
      this.addForm.payment_mode = 0;
      // this.addForm.amount = 10;
      console.log(this.addForm);
      if (this.$refs.form.validate()) {
        jobService.createJob(this.addForm).then((response) => {
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
              router.push("/admin/jobs");
            } else {
              router.push("/manager/jobs");
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
    showTiming() {
      console.log("abcd");
    },
  },
};
</script>