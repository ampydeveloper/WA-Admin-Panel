<template>
  <v-container id="dashboard" fluid tag="section">
    <v-row>
      <h4 class="main-title">Overview</h4>
      <v-col cols="12" lg="12">
        <div class="dashboard">
          <!-- <v-row>
            <v-col cols="12" sm="6" lg="3">
              <v-card>
                <h5>
                  Total Jobs
                  <span>100</span>
                </h5>
              </v-card>
            </v-col>

            <v-col cols="12" sm="6" lg="3">
              <v-card>
                <h5>
                  Total Trucks
                  <span>100</span>
                </h5>
              </v-card>
            </v-col>

            <v-col cols="12" sm="6" lg="3">
              <v-card>
                <h5>
                  Total Drivers
                  <span>100</span>
                </h5>
              </v-card>
            </v-col>

            <v-col cols="12" sm="6" lg="3">
              <v-card>
                <h5>
                  Successful Jobs
                  <span>100</span>
                </h5>
              </v-card>
            </v-col>
          </v-row> -->
          <v-row>
            <v-col cols="12" sm="6" lg="6">
              <canvas id="planet-chart"></canvas>
            </v-col>
            <v-col cols="12" sm="6" lg="6">
              <canvas id="pie-chart"></canvas>
            </v-col>
          </v-row>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import Chart from "chart.js";
import planetChartData from "./chart/chart-data.js";
import pieChartData from "./chart/pie-chart-data.js";

export default {
  data() {
    return {
      planetChartData: planetChartData,
      pieChartData: pieChartData
    };
  },
  mounted() {
    this.createChart("planet-chart", this.planetChartData);
    this.createChart("pie-chart", this.pieChartData);
  },
  methods: {
    createChart(chartId, chartData) {
      const ctx = document.getElementById(chartId);
      const myChart = new Chart(ctx, {
        type: chartData.type,
        data: chartData.data,
        options: chartData.options
      });
    }
  },
  created() {}
};
</script>
