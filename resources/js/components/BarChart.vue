<template>
  <Bar
    id="my-chart-id"
    :options="chartOptions"
    :data="chartData"
    :width="400"
    :height="300"
  />
</template>

<script>
import { Bar } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
);

export default {
  name: "BarChart",
  components: { Bar },
  props: {
    categoryWiseExpenses: {
      type: Object,
      required: true,
      default: () =>{}
    },
  },
  watch: {
    categoryWiseExpenses: {
      handler(newData) {
        console.log("data changed");
        this.updateChartData(newData);
      },
      immediate: true,
      deep: true,
    },
  },
  data() {
    return {
      chartData: {
        labels: [],
        datasets: [
          {
            label: "Expenses",
            data: [],
            backgroundColor: [],
          },
        ],
      },
      chartOptions: {
        responsive: false,
        maintainAspectRatio: false,
        scales: {
          x: { grid: { display: false } },
          y: { grid: { display: false } },
        },
      },
    };
  },
  methods:{
     updateChartData(data) {
    const labels = Object.keys(data);
    const amounts = Object.values(data);
    const colors = labels.map((_, index) => `hsl(${index * 60}, 70%, 50%)`);

    this.chartData.labels = labels;
    this.chartData.datasets[0].data = amounts;
    this.chartData.datasets[0].backgroundColor = colors;
  }
  },
  mounted(){
    console.log(this.$props);
    // this.categoryWiseExpenses = this.$props.categoryWiseExpenses;
    // this.updateChartData(this.categoryWiseExpenses);
  }
};
</script>

<style>
</style>