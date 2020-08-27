<template>
  <v-container id="dashboard" fluid tag="section">
    <v-row class="dashboard-columns">
      <div class="col">
        <div class="services">
          <div class="image-bg-color service-bg-color"></div>
          <user-icon size="2x" class="custom-class icons_custom dashboard-icons service-icon"></user-icon>
          <h2>92.6k</h2>
          <span class="employees">Services</span>
        </div>
      </div>
      <div class="col">
        <div class="services">
          <div class="image-bg-color employee-bg-color"></div>
          <user-icon size="2x" class="custom-class icons_custom dashboard-icons employee-icon"></user-icon>
          <h2>352</h2>
          <span class="employees">Employees</span>
        </div>
      </div>
      <div class="col">
        <div class="services">
          <div class="image-bg-color driver-bg-color"></div>
          <user-icon size="2x" class="custom-class icons_custom dashboard-icons driver-icon"></user-icon>
          <h2>400</h2>
          <span class="employees">Drivers</span>
        </div>
      </div>
      <div class="col">
        <div class="services">
          <div class="image-bg-color fleet-bg-color"></div>
          <user-icon size="2x" class="custom-class icons_custom dashboard-icons fleet-icon"></user-icon>
          <h2>150</h2>
          <span class="employees">Fleets</span>
        </div>
      </div>
      <div class="col">
        <div class="services">
          <div class="image-bg-color skid-bg-color"></div>
          <user-icon size="2x" class="custom-class icons_custom dashboard-icons skid-icon"></user-icon>
          <h2>100</h2>
          <span class="employees">Skid steers</span>
        </div>
      </div>
    </v-row>
    <v-row class="dashboard-graps">
      <v-col cols="12" md="7">
        <div class="grap">
            <div class="customer-graph-headings">
                <h4 class="active">Customer</h4>
                <h4 class="inactive">Haulers</h4>
                <h4 class="inactive">Jobs</h4>
            </div>

            <div class="graph-setting">
                <user-icon size="1.5x" class="setting-icon"></user-icon>
                <div class="setting-icon-drop-down">
                    <p>Weekly</p>
                    <p>Monthly</p>
                    <p>Yearly</p>
                </div>
            </div>
            
            
            <v-row class="user-image">
            <v-col class="user-image-inner" cols="12" md="12">
        <v-row class="float-right graph-setting" justify="space-around">
          <v-menu bottom origin="center center" transition="scale-transition">
            <template v-slot:activator="{ on }">
              <user-icon size="1.5x" class="setting-icon"></user-icon>
            </template>
            <v-list class="header-right-menu">
              <v-list-item>
                <v-list-item-title>
                  Weekly
                </v-list-item-title>
                <v-list-item-title>
                  Monthly
                </v-list-item-title>
                <v-list-item-title>
                  Yearly
                </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-row>

        </v-col>
          </v-row>
          <canvas id="planet-chart"></canvas>
        </div>
      </v-col>
      <v-col cols="12" md="5">
        <div class="grap">
            <div class="customer-graph-headings">
            <h4 class="active">Invoices</h4>
          </div>
          <div class="graph-setting">
                <user-icon size="1.5x" class="setting-icon"></user-icon>
                <div class="setting-icon-drop-down">
                    <p>Weekly</p>
                    <p>Monthly</p>
                    <p>Yearly</p>
                </div>
            </div>
          <canvas id="pie-chart"></canvas>
        </div>
      </v-col>
    </v-row>

    <v-row class="dashboard-graps">
      <v-col cols="12" md="7">
        <div class="grap">
            <div class="customer-graph-headings">
                <h4 class="active">Customer</h4>
                <h4 class="inactive">Haulers</h4>
                <h4 class="inactive">Jobs</h4>
            </div>

            <div class="graph-setting">
                <user-icon size="1.5x" class="setting-icon"></user-icon>
                <div class="setting-icon-drop-down">
                    <p>Weekly</p>
                    <p>Monthly</p>
                    <p>Yearly</p>
                </div>
            </div>
            
            
            <v-row class="user-image">
            <v-col class="user-image-inner" cols="12" md="12">
        <v-row class="float-right graph-setting" justify="space-around">
          <v-menu bottom origin="center center" transition="scale-transition">
            <template v-slot:activator="{ on }">
              <user-icon size="1.5x" class="setting-icon"></user-icon>
            </template>
            <v-list class="header-right-menu">
              <v-list-item>
                <v-list-item-title>
                  Weekly
                </v-list-item-title>
                <v-list-item-title>
                  Monthly
                </v-list-item-title>
                <v-list-item-title>
                  Yearly
                </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-row>

        </v-col>
          </v-row>
          <canvas id="planet-chart"></canvas>
        </div>
      </v-col>
      <v-col cols="12" md="5">
        <div class="grap">
            <div class="customer-graph-headings">
            <h4 class="active">Invoices</h4>
          </div>
          <div class="graph-setting">
                <user-icon size="1.5x" class="setting-icon"></user-icon>
                <div class="setting-icon-drop-down">
                    <p>Weekly</p>
                    <p>Monthly</p>
                    <p>Yearly</p>
                </div>
            </div>
          <canvas id="pie-chart"></canvas>
        </div>
      </v-col>
    </v-row>
    <v-row class="dashboard-graps">
        <v-col cols="12" md="4">
        <div class="grap">
            <div class="customer-graph-headings">
            <h4 class="active">Sessions By Device</h4>
          </div>
          <div class="graph-setting">
                <user-icon size="1.5x" class="setting-icon"></user-icon>
                <div class="setting-icon-drop-down">
                    <p>Weekly</p>
                    <p>Monthly</p>
                    <p>Yearly</p>
                </div>
            </div>
        </div>
      </v-col>
      <v-col cols="12" md="4">
        <div class="grap">
            <div class="customer-graph-headings">
            <h4 class="active">Chats</h4>
          </div>
          <div class="graph-setting">
                <user-icon size="1.5x" class="setting-icon"></user-icon>
                <div class="setting-icon-drop-down">
                    <p>Weekly</p>
                    <p>Monthly</p>
                    <p>Yearly</p>
                </div>
            </div>
        </div>
      </v-col>
      <v-col cols="12" md="4">
        <div class="grap">
            <div class="customer-graph-headings">
            <h4 class="active">Customers</h4>
          </div>
          <div class="graph-setting">
                <user-icon size="1.5x" class="setting-icon"></user-icon>
                <div class="setting-icon-drop-down">
                    <p>Weekly</p>
                    <p>Monthly</p>
                    <p>Yearly</p>
                </div>
            </div>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import Chart from "chart.js";
import planetChartData from "./chart/chart-data.js";
import pieChartData from "./chart/pie-chart-data.js";
import { UserIcon, LockIcon } from "vue-feather-icons";

export default {
  components: {
    UserIcon,
    LockIcon,
  },
  data() {
    return {
      planetChartData: planetChartData,
      pieChartData: pieChartData,
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
        options: chartData.options,
      });
    },
  },
  created() {},
};
</script>




<style>
#dashboard {
  padding-right: 0px !important;
  padding-left: 24px;
  margin: 0 !important;
}
#dashboard .dashboard-columns .services {
  background-color: #fff;
  border-radius: 7px;
  padding: 25px 15px;
  text-align: center;
  /* box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05); */
  box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
}
.image-bg-color {
  height: 45px;
  border-radius: 24px;
  position: absolute;
  left: 50%;
  margin-left: -23px;
  width: 45px;
}
.image-bg-color.service-bg-color {
  background: #dff7e9;
}
.image-bg-color.employee-bg-color {
  background: #dff7e9;
}
.image-bg-color.driver-bg-color {
  background: #dff7e9;
}
.image-bg-color.fleet-bg-color {
  background: #dff7e9;
}
.image-bg-color.skid-bg-color {
  background: #dff7e9;
}
.dashboard-icons {
  position: relative;
  top: 7px;
}
.dashboard-icons.service-icon {
  color: #11b276;
}
.dashboard-icons.employee-icon {
  color: #11b276;
}
.dashboard-icons.driver-icon {
  color: #11b276;
}
.dashboard-icons.fleet-icon {
  color: #11b276;
}
.dashboard-icons.skid-icon {
  color: #11b276;
}
#dashboard .dashboard-columns .services h2 {
  margin-top: 30px;
  font-weight: bold;
  margin-bottom: 3px;
  color: #2c2c2c;
}
#dashboard .dashboard-columns .services .employees {
  color: #626262;
  font-weight: 300;
}

#dashboard .dashboard-graps .grap {
  background-color: #fff;
  border-radius: 7px;
  box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
  padding: 20px;
    min-height: 370px;
  
}
#dashboard .dashboard-graps .grap .customer-graph-headings {
    display: inline-flex;
}
#dashboard .dashboard-graps .grap h4 {
    font-size: 18.48px;
    color: #2c2c2c;
    margin-bottom: 30px;
    margin-right: 20px;
  }

#dashboard .dashboard-graps .grap .inactive {
    color: #626262;
    font-size: 15.48px;
    padding-top: 2px;
  }

#dashboard .dashboard-graps .graph-setting .setting-icon {
  position: relative;
  display: inline-block;
    margin-right: 20px;
}

#dashboard .dashboard-graps .graph-setting .setting-icon-drop-down {
    display: none;
    position: absolute;
    background-color: #fff;
    box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
    padding: 12px 16px 0px;
    z-index: 1;
    right: 20px;
    top: 60px;
    border-radius: 7px;
}

#dashboard .dashboard-graps .graph-setting:hover .setting-icon-drop-down {
  display: block;
}

</style>


