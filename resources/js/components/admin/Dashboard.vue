<template>
  <v-container id="dashboard" fluid tag="section">
    <v-row class="dashboard-columns">
      <div class="col-sm-6 col-md-6 col-lg-3">
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
      <div class="col-sm-6 col-md-6 col-lg-3">
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
      <div class="col-sm-6 col-md-6 col-lg-3">
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
      <div class="col-sm-6 col-md-6 col-lg-3">
        <div class="services">
          <div class="image-bg-color fleet-bg-color"></div>
          <truck-icon
            size="2x"
            class="custom-class icons_custom dashboard-icons fleet-icon"
          ></truck-icon>

          <h2>
            {{
              typeof count.trucks != undefined
                ? count.trucks + count.skidsteers
                : "0"
            }}
          </h2>
          <span class="employees">Fleet</span>
          <div class="services-chart-outer">
            <canvas id="services-chart4"></canvas>
          </div>
        </div>
      </div>
    </v-row>

    <v-row class="dashboard-graps">
      <v-col cols="12" md="7">
        <div class="grap customer-g-outer">
          <div class="customer-graph-headings">
            <h4 class="active">Customer</h4>

            <div class="graph-select">
              <v-select
                :items="prefixs"
                v-model="prefixSelectedCustomer"
                class="graph-select-sl"
                :menu-props="{ contentClass: 'graph-select-options' }"
                @change="updateCustomerGraph"
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
                v-model="prefixSelectedInvoice"
                class="graph-select-sl"
                :menu-props="{ contentClass: 'graph-select-options' }"
                @change="updateInvoiceGraph"
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
      <v-col cols="12" md="7">
        <div class="grap customer-g-outer">
          <div class="customer-graph-headings">
            <h4 class="active">Hauler</h4>

            <div class="graph-select">
              <v-select
                :items="prefixs"
                v-model="prefixSelectedCustomer"
                class="graph-select-sl"
                :menu-props="{ contentClass: 'graph-select-options' }"
                @change="updateCustomerGraph"
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
                    >{{ weather.min_temp_val }} <sup class="text-2xl">o</sup
                    ><br />
                    <small>MIN</small></span
                  >
                  <span
                    >{{ weather.max_temp_val }} <sup class="text-2xl">o</sup
                    ><br />
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
    </v-row>

    <v-row class="dashboard-graps">
      <v-col cols="12" md="7">
        <div class="grap customer-g-outer">
          <div class="customer-graph-headings">
            <h4 class="active">Jobs</h4>

            <div class="graph-select">
              <v-select
                :items="prefixs"
                v-model="prefixSelectedCustomer"
                class="graph-select-sl"
                :menu-props="{ contentClass: 'graph-select-options' }"
                @change="updateCustomerGraph"
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
        <div class="grap timeline-grap">
          <div class="customer-graph-headings">
            <h4 class="active">Notifications</h4>
          </div>
          <div class="vx-card__collapsible-content">
            <div class="vx-card__body">
              <ul class="vx-timeline">
                <li>
                  <div class="timeline-icon">
                    <span class>
                      <message-square-icon
                        size="1.5x"
                        class="custom-class"
                      ></message-square-icon>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">PICKUP100656</p>
                    <span class="activity-desc">Pickup has been started.</span>
                  </div>
                  <small class="text-grey activity-e-time">25 mins Ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
                      <message-square-icon
                        size="1.5x"
                        class="custom-class"
                      ></message-square-icon>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">PICKUP100656</p>
                    <span class="activity-desc"
                      >Payment has been initated and invoice has been
                      created.</span
                    >
                  </div>
                  <small class="text-grey activity-e-time">15 Days Ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
                      <message-square-icon
                        size="1.5x"
                        class="custom-class"
                      ></message-square-icon>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">PICKUP100656</p>
                    <span class="activity-desc"
                      >Pickup has been cancelled.</span
                    >
                  </div>
                  <small class="text-grey activity-e-time">20 days ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
                      <message-square-icon
                        size="1.5x"
                        class="custom-class"
                      ></message-square-icon>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">PICKUP100656</p>
                    <span class="activity-desc"
                      >Pickup has been rescheduled.</span
                    >
                  </div>
                  <small class="text-grey activity-e-time">28 days ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
                      <message-square-icon
                        size="1.5x"
                        class="custom-class"
                      ></message-square-icon>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">PICKUP100656</p>
                    <span class="activity-desc">Pickup has been declined.</span>
                  </div>
                  <small class="text-grey activity-e-time">28 days ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
                      <message-square-icon
                        size="1.5x"
                        class="custom-class"
                      ></message-square-icon>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">PICKUP100656</p>
                    <span class="activity-desc">Pickup has been declined.</span>
                  </div>
                  <small class="text-grey activity-e-time">28 days ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
                      <message-square-icon
                        size="1.5x"
                        class="custom-class"
                      ></message-square-icon>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">PICKUP100656</p>
                    <span class="activity-desc">Pickup has been declined.</span>
                  </div>
                  <small class="text-grey activity-e-time">28 days ago</small>
                </li>
                <li>
                  <div class="timeline-icon">
                    <span class>
                      <alert-circle-icon
                        size="1.5x"
                        class="custom-class"
                      ></alert-circle-icon>
                    </span>
                  </div>
                  <div class="timeline-info">
                    <p class="font-semibold">Dispatch Issue</p>
                    <span class="activity-desc"
                      >Shortage of trucks or drivers.</span
                    >
                  </div>
                  <small class="text-grey activity-e-time">28 days ago</small>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>

    <v-row class="dashboard-graps">
      <v-col cols="12" md="12">
        <div class="grap" style="height: 594px">
          <div class="customer-graph-headings">
            <h4 class="active">Dispatched Fleet</h4>

            <div class="graph-select">
              <v-select
                :items="mapDates"
                v-model="prefixSelectedMap"
                class="graph-select-sl"
                :menu-props="{ contentClass: 'graph-select-options' }"
                @change="dashboardJobs"
              ></v-select>
            </div>

            <div id="map" class="contain"></div>
          </div>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import Chart from "chart.js";
import moment from "moment";
// import Mapbox from "mapbox-gl-vue";
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
  AlertCircleIcon,
  MessageSquareIcon,
} from "vue-feather-icons";

export default {
  components: {
    UserIcon,
    LockIcon,
    // Mapbox,
    GridIcon,
    UserCheckIcon,
    TruckIcon,
    UsersIcon,
    AlertCircleIcon,
    MessageSquareIcon,
  },
  data() {
    return {
      prefixSelectedCustomer: ["Last 7 Days"],
      prefixSelectedInvoice: ["Last 7 Days"],
      prefixs: ["Last 7 Days", "Last Month", "Last Year"],
      prefixSelectedMap: ["This week"],
      mapDates: ["This week", "This month", "This year"],
      count: {
        drivers: 0,
        managers: 0,
        services: 0,
        skidsteers: 0,
        trucks: 0,
      },
      graphs: "",
      invoiceGraphs: "",
      weather: "",
      gradient: null,
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
    this.dashboardJobs();

    this.gradient = null;
    this.createChart("services-chart", this.servicesData, this.gradient);
    this.createChart("services-chart2", this.servicesData4, this.gradient);
    this.createChart("services-chart3", this.servicesData2, this.gradient);
    this.createChart("services-chart4", this.servicesData3, this.gradient);
    
    var wellOffice = "26.695145,-80.244859";
    var icons = {
      start: new google.maps.MarkerImage(
        "http://wa.customer.leagueofclicks.com/img/car-marker2.png",
        new google.maps.Size(72, 100),
        // The origin point (x,y)
        new google.maps.Point(0, 0),
        // The anchor point (x,y)
        new google.maps.Point(22, 32)
      ),
      end: new google.maps.MarkerImage(
        "http://wa.customer.leagueofclicks.com/img/map-icon2.png",
        new google.maps.Size(55, 55),
        // The origin point (x,y)
        new google.maps.Point(0, 0),
        // The anchor point (x,y)
        new google.maps.Point(22, 32)
      ),
    };
    var map;
    function initMap() {
      const directionsService = new google.maps.DirectionsService();
      const directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
      });
      map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: { lat: 41.85, lng: -87.65 },
        mapTypeControl: false,
        // draggable: false,
        scaleControl: false,
        scrollwheel: false,
        navigationControl: false,
        streetViewControl: false,
        styles: [
          {
            featureType: "landscape.natural",
            elementType: "all",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "landscape.natural",
            elementType: "labels",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels.text",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels.icon",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "all",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "labels",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "all",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels.text.fill",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels.text.stroke",
            stylers: [
              {
                visibility: "on",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels.icon",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit",
            elementType: "all",
            stylers: [
              {
                visibility: "simplified",
              },
            ],
          },
          {
            featureType: "transit.station.airport",
            elementType: "all",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.airport",
            elementType: "geometry.fill",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.airport",
            elementType: "labels.text.fill",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.bus",
            elementType: "all",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "transit.station.rail",
            elementType: "all",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "water",
            elementType: "geometry.fill",
            stylers: [
              {
                color: "#e2f6fe",
              },
            ],
          },
        ],
      });
      directionsRenderer.setMap(map);

      // calculateAndDisplayRoute(directionsService, directionsRenderer);
    }

    function calculateAndDisplayRoute(directionsService, directionsRenderer) {
      directionsService.route(
        {
          origin: {
            query: wellOffice,
          },
          destination: {
            query: '26.654155,-80.248645',
          },
          travelMode: google.maps.TravelMode.DRIVING,
        },
        (response, status) => {
          if (status === "OK") {
            directionsRenderer.setDirections(response);
            var leg = response.routes[0].legs[0];
            console.log(leg);
            makeMarker(leg.start_location, icons.start, "Wellington Office");
            makeMarker(leg.end_location, icons.end, "Farm");
          } else {
            window.alert("Directions request failed due to " + status);
          }
        }
      );
    }
    function makeMarker(position, title) {
      new google.maps.Marker({
        position: position,
        map: map,
        // icon: icon,
        title: title,
      });
    }

     const myLatLng = { lat: -25.363, lng: 131.044 };
  // const map = new google.maps.Map(document.getElementById("map"), {
  //   zoom: 4,
  //   center: myLatLng,
  // });
  new google.maps.Marker({
    position: myLatLng,
    map,
    title: "Hello World!",
  });

// makeMarker({ lat: 26.654155, lng: -80.248645 }, "Farm");
    // adminService
    //     .dashboardJobs({ range:'This year' })
    //     .then((response) => {
    //       if (response.status) {
    //         // this.invoiceGraphs = response.data;
    //         makeMarker({ lat: 26.654155, lng: -80.248645 }, 'sds', "Farm");
    //       }
    //     });

    setTimeout(function () {
      initMap();
    }, 6000);
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
    processCustomerGraphData(newCustomerGraph, newHaulerGraph) {
      let customerData = {},
        haulerData = {},
        planetChartLabels = [],
        planetChartCustomerVals = [],
        planetChartHaulerVals = [];
      [...newCustomerGraph].forEach((stat) => {
        customerData[stat.date] = stat.no;
        planetChartLabels.push(stat.date);
      });
      [...newHaulerGraph].forEach((stat) => {
        haulerData[stat.date] = stat.no;
        planetChartLabels.push(stat.date);
      });
      planetChartLabels = [...new Set(planetChartLabels)];
      planetChartLabels.sort(
        (a, b) => moment(a, "DD-MMM-YY") - moment("DD-MMM-YY")
      );
      [...planetChartLabels].forEach((dt) => {
        let val = 0;
        if (Object.keys(customerData).includes(dt)) {
          val = customerData[dt];
        }
        planetChartCustomerVals.push(val);
        val = 0;
        if (Object.keys(haulerData).includes(dt)) {
          val = haulerData[dt];
        }
        planetChartHaulerVals.push(val);
      });
      return [
        planetChartCustomerVals,
        planetChartHaulerVals,
        planetChartLabels,
      ];
    },
    dashboardData() {
      adminService.dashboardData().then((response) => {
        if (response.status) {
          this.count = response.data.count;
          this.graphs = response.data.graphs;
          this.invoiceGraphs = response.data.invoiceGraphs;
          this.weather = response.data.weather;
          this.weather.main_temp = response.data.weather.the_temp.toFixed(2);
          this.weather.min_temp_val = response.data.weather.min_temp.toFixed(2);
          this.weather.max_temp_val = parseFloat(
            response.data.weather.max_temp
          ).toFixed(2);

          // Customer Graph
          // let customerLabels = [];
          let processed = this.processCustomerGraphData(
            response.data.graphs.newCustomerGraph,
            response.data.graphs.newHaulerGraph
          );
          let planetChartCustomerVals = processed[0];
          let planetChartHaulerVals = processed[1];
          let planetChartLabels = processed[2];

          this.planetChartData.data.datasets[0].data = planetChartCustomerVals;
          this.planetChartData.data.datasets[1].data = planetChartHaulerVals;
          this.planetChartData.data.labels = planetChartLabels;
          this.createChart("planet-chart", this.planetChartData, this.gradient);

          // Invoice Pie-Chart
          const invoiceVals = response.data.invoiceGraphs;
          this.pieChartData.data.datasets[0].data = [
            invoiceVals.customerInvoice,
            invoiceVals.haulerInvoice,
            invoiceVals.outstandingInvoices,
          ];
          this.createChart("pie-chart", this.pieChartData, this.gradient);
          //  this.createChart("pie-chart", this.pieChartData, this.gradient);
        } else {
          this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right",
          });
        }
      });
    },
    updateCustomerGraph() {
      adminService
        .dashboardDataFilters({
          filter_for: "graphs",
          filter_time: this.prefixSelectedCustomer,
        })
        .then((response) => {
          if (response.status) {
            this.count.newCustomersCount =
              response.data.graphs.newCustomersCount;
            this.count.newHaulersCount = response.data.graphs.newHaulersCount;
            this.graphs.revenueGeneratedByNewCustomers =
              response.data.graphs.revenueGeneratedByNewCustomers;
            // planetChartCustomerVals, planetChartHaulerVals, planetChartLabels = this.processCustomerGraphData(response.data.graphs.newCustomerGraph, response.data.graphs.newHaulerGraph);
            let processed = this.processCustomerGraphData(
              response.data.graphs.newCustomerGraph,
              response.data.graphs.newHaulerGraph
            );
            let planetChartCustomerVals = processed[0];
            let planetChartHaulerVals = processed[1];
            let planetChartLabels = processed[2];
            this.planetChartData.data.datasets[0].data = planetChartCustomerVals;
            this.planetChartData.data.datasets[1].data = planetChartHaulerVals;
            this.planetChartData.data.labels = planetChartLabels;
            this.createChart(
              "planet-chart",
              this.planetChartData,
              this.gradient
            );
          }
        });
    },
    updateInvoiceGraph() {
      adminService
        .dashboardDataFilters({
          filter_for: "invoices",
          filter_time: this.prefixSelectedInvoice,
        })
        .then((response) => {
          if (response.status) {
            const invoiceVals = response.data.invoiceGraphs;
            this.invoiceGraphs = response.data.invoiceGraphs;
            this.pieChartData.data.datasets[0].data = [
              invoiceVals.customerInvoices,
              invoiceVals.haulerInvoices,
              invoiceVals.outstandingInvoices,
            ];
            this.createChart("pie-chart", this.pieChartData, this.gradient);
          }
        });
    },
    dashboardJobs() {
      // adminService
      //   .dashboardJobs({ range: this.prefixSelectedMap })
      //   .then((response) => {
      //     if (response.status) {
      //       // this.invoiceGraphs = response.data;
      //       makeMarker('26.654155,-80.248645', 'sds', "Farm");
      //       directionsRenderer.setMap(map);
      //     }
      //   });
    },
  },

  created() {},
};
</script>