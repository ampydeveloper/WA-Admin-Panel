<template>
  <v-container id="dashboard" fluid tag="section">
    <v-row class="dashboard-columns">
      <div class="col-3">
        <div class="services">
          <div class="image-bg-color service-bg-color"></div>

          <grid-icon
            size="2x"
            class="custom-class icons_custom dashboard-icons service-icon"
          ></grid-icon>
          <h2>{{ count.services }}</h2>
          <span class="employees">Services</span>
          <div class="services-chart-outer">
            <canvas id="services-chart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="services">
          <div class="image-bg-color employee-bg-color"></div>

          <user-check-icon
            size="2x"
            class="custom-class icons_custom dashboard-icons employee-icon"
          ></user-check-icon>
          <h2>{{ count.managers }}</h2>
          <span class="employees">Managers</span>
          <div class="services-chart-outer">
            <canvas id="services-chart2"></canvas>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="services">
          <div class="image-bg-color driver-bg-color"></div>
          <users-icon
            size="2x"
            class="custom-class icons_custom dashboard-icons driver-icon"
          ></users-icon>
          <h2>{{ count.drivers }}</h2>
          <span class="employees">Drivers</span>
          <div class="services-chart-outer">
            <canvas id="services-chart3"></canvas>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="services">
          <div class="image-bg-color fleet-bg-color"></div>

          <truck-icon
            size="2x"
            class="custom-class icons_custom dashboard-icons fleet-icon"
          ></truck-icon>

          <h2>{{ count.trucks + count.skidsteers }}</h2>
          <span class="employees">Fleet</span>
          <div class="services-chart-outer">
            <canvas id="services-chart4"></canvas>
          </div>
        </div>
      </div>
    </v-row>

    <v-row class="dashboard-graps">
      <v-col cols="12" md="7">
        <div class="grap">
          <div class="customer-graph-headings">
            <h4 class="active">Customer</h4>

            <div class="graph-select">
              <v-select
                :items="prefixs"
                v-model="prefixSelected"
                class="graph-select-sl"
                :menu-props="{ contentClass: 'graph-select-options' }"
              ></v-select>
            </div>
          </div>
          <div class="customer-graph-details-outer">
            <div class="customer-graph-details">
              <h5>New</h5>
              <p class="green-text">{{ graphs.newCustomersCount }}</p>
            </div>
            <div class="customer-graph-details">
              <h5>All</h5>
              <p>{{ graphs.allCustomersCount }}</p>
            </div>

            <div class="customer-graph-para">
              <p>
                Your have
                <span>{{ graphs.newCustomersCount }}</span> new customers
                generating
                <span>${{ graphs.revenueGeneratedByNewCustomers }}</span> this
                week.
              </p>
            </div>
          </div>
          <canvas id="planet-chart"></canvas>
        </div>
      </v-col>
      <v-col cols="12" md="5">
        <div class="grap invoice-outer">
          <div class="customer-graph-headings">
            <h4 class="active">Invoices</h4>

            <div class="graph-select">
              <v-select
                :items="prefixs"
                v-model="prefixSelected"
                class="graph-select-sl"
                :menu-props="{ contentClass: 'graph-select-options' }"
              ></v-select>
            </div>
          </div>
          <div class="pie-chart-outer">
            <canvas id="pie-chart"></canvas>
          </div>

          <div class="customer-graph-details-outer pie-data-outer row">
            <div class="customer-graph-details col-sm-4">
              <h5>Customers</h5>
              <p><span>$</span> {{ invoiceGraphs.customerInvoices }}</p>
            </div>
            <div class="customer-graph-details col-sm-4">
              <h5>Haulers</h5>
              <p><span>$</span>{{ invoiceGraphs.haulerInvoices }}</p>
            </div>
            <div class="customer-graph-details col-sm-4">
              <h5>Outstanding</h5>
              <p class="green-text">
                <span>$</span>{{ invoiceGraphs.outstandingInvoices }}
              </p>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>

    <v-row class="dashboard-graps">
      <v-col cols="12" md="12">
        <div class="grap" style="    height: 594px;">
          <div class="customer-graph-headings">
            <h4 class="active">Dispatches</h4>
            <mapbox
              access-token="pk.eyJ1IjoibG9jb25lIiwiYSI6ImNrYmZkMzNzbDB1ZzUyenM3empmbXE3ODQifQ.SiBnr9-6jpC1Wa8OTAmgVA"
              :map-options="{
                style: 'mapbox://styles/mapbox/dark-v9',
                center: [-80.2853179, 26.6094155],
                zoom: 7,
              }"
              :geolocate-control="{
                show: true,
                position: 'top-left',
              }"
              :scale-control="{
                show: true,
                position: 'top-left',
              }"
              :fullscreen-control="{
                show: true,
                position: 'top-left',
              }"
            />
          </div>
        </div>
      </v-col>
    </v-row>

    <v-row class="dashboard-graps">
      <v-col cols="12" md="4">
        <div class="grap temp-grap">
          <div class="overlay"></div>
          <div class="card-overlay text-white">
            <div class="flex flex-col justify-between h-full">
              <div class="text-center mt-8 w-full">
                <h3 class="text-white mb-2 tracking-wide">Wellington</h3>
                <p class="mb-6">Florida</p>
                <div class="flex justify-around mb-12">
                  <span class="feather-icon">
                    <img :src="weather.weather_icon" alt="" width="90" />
                  </span>
                  <h2 class="text-white text-big">
                    {{ weather.main_temp }}
                    <sup class="text-2xl">o</sup>
                  </h2>
                </div>
              </div>
              <div class="text-center w-full">
                <div class="flex justify-between px-8 mb-8 text-xl">
                  <span
                    >{{ weather.min_temp }} <sup class="text-2xl">o</sup><br />
                    <small>MIN</small></span
                  >
                  <span
                    >{{ weather.max_temp }} <sup class="text-2xl">o</sup><br />
                    <small>MAX</small></span
                  >
                </div>
                <div class="flex justify-between px-8 mb-8 text-xl">
                  <span>Predictability</span>
                  <span>{{ weather.predictability }}</span>
                </div>
                <div class="flex justify-between px-8 mb-8 text-xl">
                  <span>Humidity</span>
                  <span>{{ weather.humidity }}</span>
                </div>
                <div class="flex justify-between px-8 mb-8 text-xl">
                  <span>Air Pressure</span>
                  <span>{{ weather.air_pressure }}</span>
                </div>
                <div class="flex justify-between px-8 mb-8 text-xl">
                  <span>Wind Speed</span>
                  <span>{{ weather.wind_speed }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </v-col>
      <v-col cols="12" md="4">
        <div class="grap timeline-grap">
          <div class="customer-graph-headings">
            <h4 class="active">Activity</h4>
          </div>
          <div class="vx-card__collapsible-content">
            <div class="vx-card__body">
              <ul class="vx-timeline">
                <li>
                  <div class="timeline-icon">
                    <span class>
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
                        class
                      >
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                      </svg>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">Client Meeting</p>
                    <span class="activity-desc"
                      >Bonbon macaroon jelly beans gummi bears jelly lollipop
                      apple</span
                    >
                  </div>
                  <small class="text-grey activity-e-time">25 mins Ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
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
                        class
                      >
                        <path
                          d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                        />
                        <polyline points="22,6 12,13 2,6" />
                      </svg>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">Email Newsletter</p>
                    <span class="activity-desc"
                      >Cupcake gummi bears soufflé caramels candy</span
                    >
                  </div>
                  <small class="text-grey activity-e-time">15 Days Ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
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
                        class
                      >
                        <path
                          d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                        />
                        <polyline points="22,6 12,13 2,6" />
                      </svg>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">Plan Webinar</p>
                    <span class="activity-desc"
                      >Candy ice cream cake. Halvah gummi bears</span
                    >
                  </div>
                  <small class="text-grey activity-e-time">20 days ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
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
                        class
                      >
                        <path
                          d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                        />
                        <polyline points="22,6 12,13 2,6" />
                      </svg>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">Marketing</p>
                    <span class="activity-desc"
                      >Candy ice cream cake. Halvah gummi bears Cupcake gummi
                      bears.</span
                    >
                  </div>
                  <small class="text-grey activity-e-time">28 days ago</small>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </v-col>
      <!-- <v-col cols="12" md="4">
        <div class="grap timeline-grap">
          <div class="customer-graph-headings">
            <h4 class="active">Dispatch Notifications</h4>
          </div>
          <div class="vx-card__collapsible-content">
            <div class="vx-card__body">
              <ul class="vx-timeline">
                <li>
                  <div class="timeline-icon">
                    <span class>
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
                        class
                      >
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                      </svg>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">Client Meeting</p>
                    <span
                      class="activity-desc"
                    >Bonbon macaroon jelly beans gummi bears jelly lollipop apple</span>
                  </div>
                  <small class="text-grey activity-e-time">25 mins Ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
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
                        class
                      >
                        <path
                          d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                        />
                        <polyline points="22,6 12,13 2,6" />
                      </svg>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">Email Newsletter</p>
                    <span class="activity-desc">Cupcake gummi bears soufflé caramels candy</span>
                  </div>
                  <small class="text-grey activity-e-time">15 Days Ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
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
                        class
                      >
                        <path
                          d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                        />
                        <polyline points="22,6 12,13 2,6" />
                      </svg>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">Plan Webinar</p>
                    <span class="activity-desc">Candy ice cream cake. Halvah gummi bears</span>
                  </div>
                  <small class="text-grey activity-e-time">20 days ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
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
                        class
                      >
                        <path
                          d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                        />
                        <polyline points="22,6 12,13 2,6" />
                      </svg>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">Marketing</p>
                    <span
                      class="activity-desc"
                    >Candy ice cream cake. Halvah gummi bears Cupcake gummi bears.</span>
                  </div>
                  <small class="text-grey activity-e-time">28 days ago</small>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </v-col>-->
    </v-row>
  </v-container>
</template>

<script>
import Chart from "chart.js";
import Mapbox from "mapbox-gl-vue";
import planetChartData from "./chart/chart-data.js";
import pieChartData from "./chart/pie-chart-data.js";
import servicesData from "./chart/services-data.js";
import servicesData2 from "./chart/services-data2.js";
import servicesData3 from "./chart/services-data3.js";
import servicesData4 from "./chart/services-data4.js";
import { adminService } from "../../_services/admin.service";

import {
  UserIcon,
  LockIcon,
  GridIcon,
  UserCheckIcon,
  TruckIcon,
  UsersIcon,
} from "vue-feather-icons";

export default {
  components: {
    UserIcon,
    LockIcon,
    Mapbox,
    GridIcon,
    UserCheckIcon,
    TruckIcon,
    UsersIcon,
  },
  data() {
    return {
      prefixSelected: ["Last 7 Days"],
      prefixs: ["Last 7 Days", "Last Month", "Last Year"],
            count: "",
      graphs: "",
      invoiceGraphs: "",
      weather: "",
      planetChartData: planetChartData,
      pieChartData: pieChartData,
      servicesData: servicesData,
      servicesData2: servicesData2,
      servicesData3: servicesData3,
      servicesData4: servicesData4,
    };
  },
  mounted() {
    this.dashboardData();
    // this.gradient = this.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450);
    this.gradient = null;

    // this.gradient.addColorStop(0, 'rgba(255, 0,0, 0.5)')
    // this.gradient.addColorStop(0.5, 'rgba(255, 0, 0, 0.25)');
    // this.gradient.addColorStop(1, 'rgba(255, 0, 0, 0)');
// setTimeout(function(){
this.createChart("planet-chart", this.planetChartData, this.gradient);
    this.createChart("pie-chart", this.pieChartData, this.gradient);
    this.createChart("services-chart", this.servicesData, this.gradient);
    this.createChart("services-chart2", this.servicesData4, this.gradient);
    this.createChart("services-chart3", this.servicesData2, this.gradient);
    this.createChart("services-chart4", this.servicesData3, this.gradient);
// }, 2000);
    
  },
  methods: {
    createChart(chartId, chartData, gradient) {
      const ctx = document.getElementById(chartId);
      // const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
      // gradient2.addColorStop(0, "rgba(17,178,118,1)");
      // gradient2.addColorStop(1, "rgba(17,178,118,0)");
      // chartData.data.datasets.backgroundColor = gradient;
      const myChart = new Chart(ctx, {
        type: chartData.type,
        data: chartData.data,
        options: chartData.options,
      });
    },
    dashboardData() {
      adminService.dashboardData().then((response) => {
        if (response.status) {
          this.count = response.data.count;
          this.graphs = response.data.graphs;
          this.invoiceGraphs = response.data.invoiceGraphs;
          this.weather = response.data.weather;
          this.weather.main_temp = response.data.weather.the_temp.toFixed(2);

           this.pieChartData.data.datasets.data = this.invoiceGraphs;
           this.createChart("pie-chart", this.pieChartData, this.gradient);
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
  },

  created() {},
};
</script>