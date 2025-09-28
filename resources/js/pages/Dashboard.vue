<script setup lang="ts">
import BarChart from '@/components/chartcomponents/BarChart.vue';
import PieChart from '@/components/chartcomponents/PieChart.vue';
import DoughnutChart from '@/components/chartcomponents/DoughnutChart.vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/autoplay';
import { Autoplay } from 'swiper/modules';

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';
import location from '@/routes/location';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];

const totals = ref<any>({
  total_assets: 0,
  total_categories: 0,
  total_manufacturers: 0,
  total_locations: 0,
  total_users: 0,
});

// animated totals (displayed values)
const animatedTotals = ref<any>({
  total_assets: 0,
  total_categories: 0,
  total_manufacturers: 0,
  total_locations: 0,
  total_users: 0,
});

const charts = ref({} as any);
const loading = ref(true);

const fetchStats = async () => {
  try {
    const response = await axios.get('/dashboard/stats');
    totals.value = response.data.totals;
    charts.value = response.data.charts;
  } catch (error) {
    console.error('Error fetching dashboard stats:', error);
  }
};

const generateRandomColor = () => {
  const randomColor = `#${Math.floor(Math.random() * 16777215).toString(16)}`;
  return randomColor.padEnd(7, '0');
};

// ✅ Count-up animation function
const animateValue = (key: string, start: number, end: number, duration = 50000) => {
  const startTimestamp = performance.now();

  const step = (currentTime: number) => {
    const progress = Math.min((currentTime - startTimestamp) / duration, 1);
    animatedTotals.value[key] = Math.floor(progress * (end - start) + start);
    if (progress < 1) {
      requestAnimationFrame(step);
    }
  };

  requestAnimationFrame(step);
};

// ✅ Watch for totals change and trigger animation
watch(
  () => totals.value,
  (newTotals, oldTotals) => {
    Object.keys(newTotals).forEach((key) => {
      animateValue(
        key,
        oldTotals ? oldTotals[key] || 0 : 0,
        newTotals[key],
        2000 // slower animation (2s)
      );
    });
  },
  { deep: true }
);

let chartData: any;
let chartOptions: any;

let piechartData: any;
let piechartOptions: any;

let doughnutData: any;
let doughnutOptions: any;

let chart2Data: any;
let chart2Options: any;

let assignedUserPieData: any;
let assignedUserPieOptions: any;

const rendercharts = () => {
  // Asset by Status Bar Chart
  chartData = {
    labels: charts.value.assets_by_status
      ? charts.value.assets_by_status.map((item: any) => item.status)
      : [],
    datasets: [
      {
        label: 'Assets by Status',
        backgroundColor: charts.value.assets_by_status
          ? charts.value.assets_by_status.map(() => generateRandomColor())
          : [],
        data: charts.value.assets_by_status
          ? charts.value.assets_by_status.map((item: any) => item.total)
          : [],
      },
    ],
  };
  chartOptions = {
    responsive: true,
    plugins: {
      legend: {
        display: true,
        position: 'top',
      },
      title: {
        display: true,
        text: 'Total Assets by Status',
      },
      datalabels: {
        display: true,
        color: '#000000',
        font: {
          size: 14,
          weight: 'bold',
        },
        formatter: (value: any) => {
          return `${value}`;
        },
      },
    },
  };

  // Manufacturer Bar Chart
  chart2Data = {
    labels: charts.value.assets_by_manufacturer
      ? charts.value.assets_by_manufacturer.map((item: any) => item.manufacturer.name)
      : [],
    datasets: [
      {
        label: 'Assets by Manufacturer',
        backgroundColor: charts.value.assets_by_manufacturer
          ? charts.value.assets_by_manufacturer.map(() => generateRandomColor())
          : [],
        data: charts.value.assets_by_manufacturer
          ? charts.value.assets_by_manufacturer.map((item: any) => item.total)
          : [],
      },
    ],
  };
  chart2Options = {
    responsive: true,
    plugins: {
      legend: {
        display: true,
        position: 'top',
      },
      title: {
        display: true,
        text: 'Total Assets by Manufacturer',
      },
      datalabels: {
        display: true,
        color: '#000000',
        font: {
          size: 14,
          weight: 'bold',
        },
      },
    },
  };

  // Pie Chart Asset by Category
  piechartData = {
    labels: charts.value.assets_by_category
      ? charts.value.assets_by_category.map((item: any) => item.category.name)
      : [],
    datasets: [
      {
        label: 'Assets by Category',
        backgroundColor: charts.value.assets_by_category
          ? charts.value.assets_by_category.map(() => generateRandomColor())
          : [],
        data: charts.value.assets_by_category
          ? charts.value.assets_by_category.map((item: any) => item.total)
          : [],
      },
    ],
  };
  piechartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: true,
        position: 'left',
      },
      title: {
        display: true,
        text: 'Total Assets by Category',
      },
      datalabels: {
        display: true,
        color: '#000000',
      },
    },
  };

  // Doughnut Chart Asset by Location
  doughnutData = {
    labels: charts.value.assets_by_location
      ? charts.value.assets_by_location.map((item: any) => item.location.name)
      : [],
    datasets: [
      {
        label: 'Assets by Location',
        backgroundColor: charts.value.assets_by_location
          ? charts.value.assets_by_location.map(() => generateRandomColor())
          : [],
        data: charts.value.assets_by_location
          ? charts.value.assets_by_location.map((item: any) => item.total)
          : [],
      },
    ],
  };
  doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: true,
        position: 'left',
      },
      title: {
        display: true,
        text: 'Total Assets by Location',
      },
      datalabels: {
        display: true,
        color: '#000000',
      },
    },
  };

  // Assigned User Pie Chart
  assignedUserPieData = {
    labels: charts.value.assets_by_assigned_user
      ? charts.value.assets_by_assigned_user.map((item: any) =>
        item.assigned_to ? item.assigned_to.name : 'Unassigned'
      )
      : [],
    datasets: [
      {
        label: 'Assets by Assigned User',
        backgroundColor: charts.value.assets_by_assigned_user
          ? charts.value.assets_by_assigned_user.map(() => generateRandomColor())
          : [],
        data: charts.value.assets_by_assigned_user
          ? charts.value.assets_by_assigned_user.map((item: any) => item.total)
          : [],
      },
    ],
  };
  assignedUserPieOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: true,
        position: 'left',
      },
      title: {
        display: true,
        text: 'Total Assets by Assigned User',
      },
      datalabels: {
        display: true,
        color: '#000000',
      },
    },
  };
};

// ✅ Scrollable small card for Assets by Category
const scrollingItems = ref(
  charts.value.assets_by_category
    ? charts.value.assets_by_category.map((item: any) => ({
        name: item.category.name,
        count: item.total,
      }))
    : []
);

const currentScrollIndex = ref(0);

const scrollInterval = () => {
  setInterval(() => {
    if (scrollingItems.value.length === 0) return;
    currentScrollIndex.value =
      (currentScrollIndex.value + 1) % scrollingItems.value.length;
  }, 3000); // rotate every 3 seconds
};

onMounted(async () => {
  loading.value = true;
  await fetchStats();
  rendercharts();
  // initialize scrolling items after charts are loaded
  scrollingItems.value = charts.value.assets_by_category
    ? charts.value.assets_by_category.map((item: any) => ({
        name: item.category.name,
        count: item.total,
      }))
    : [];
  scrollInterval();
  loading.value = false;
});
</script>

<template>

  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <!-- Totals -->
      <div class="grid auto-rows-min gap-4 md:grid-cols-4">
        <div class="rounded-xl bg-green-500 border bg-card text-card-foreground shadow">
          <div class="flex flex-row items-center justify-between space-y-0 gap-y-1.5 p-6 pb-2">
            <h3 class="text-sm font-medium tracking-tight">Total Assets:<svg viewBox="0 0 24 24" width="24" height="24"
                stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                class="css-i6dzq1">
                <path
                  d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                </path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
              </svg></h3>
          </div>
          <div class="p-6 pt-0">
            <!-- ✅ animated count -->
            <div class="text-2xl font-bold text-center">{{ animatedTotals.total_assets }}</div>
          </div>
        </div>
        <div class="rounded-xl dark:bg-blue-500 border bg-card text-card-foreground shadow">
          <div class="flex flex-row items-center justify-between space-y-0 gap-y-1.5 p-6 pb-2">
            <h3 class="text-sm font-medium tracking-tight">Total Users:<svg viewBox="0 0 24 24" width="24" height="24"
                stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                class="css-i6dzq1">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg></h3>
          </div>
          <div class="p-6 pt-0">
            <div class="text-2xl font-bold text-center">{{ animatedTotals.total_users }}</div>
          </div>
        </div>
        <div class="rounded-xl bg-purple-500 border bg-card text-card-foreground shadow">
          <div class="flex flex-row items-center justify-between space-y-0 gap-y-1.5 p-6 pb-2">
            <h3 class="text-sm font-medium tracking-tight">Total Categories:<svg viewBox="0 0 24 24" width="24"
                height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                stroke-linejoin="round" class="css-i6dzq1">
                <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"></polygon>
                <line x1="12" y1="22" x2="12" y2="15.5"></line>
                <polyline points="22 8.5 12 15.5 2 8.5"></polyline>
                <polyline points="2 15.5 12 8.5 22 15.5"></polyline>
                <line x1="12" y1="2" x2="12" y2="8.5"></line>
              </svg></h3>
          </div>
          <div class="p-6 pt-0">
            <div class="text-2xl font-bold text-center">{{ animatedTotals.total_categories }}</div>
          </div>
        </div>
        <div class="rounded-xl bg-orange-500 border bg-card text-card-foreground shadow">
          <div class="flex flex-row items-center justify-between space-y-0 gap-y-1.5 p-6 pb-2">
            <h3 class="text-sm font-medium tracking-tight">Total Manufacturers:<svg xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2-icon lucide-building-2">
                <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z" />
                <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" />
                <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2" />
                <path d="M10 6h4" />
                <path d="M10 10h4" />
                <path d="M10 14h4" />
                <path d="M10 18h4" />
              </svg></h3>
          </div>
          <div class="p-6 pt-0">
            <div class="text-2xl font-bold text-center">{{ animatedTotals.total_manufacturers }}</div>
          </div>
        </div>
      </div>


      <!-- Charts -->
      <div
        class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
          <!-- Status & Manufacturer Carousel -->
          <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div v-if="loading" class="flex h-full items-center justify-center">
              <div class="spinner-border inline-block h-8 w-8 animate-spin rounded-full border-4" role="status"></div>
            </div>
            <div v-else>
              <Swiper :modules="[Autoplay]" :autoplay="{ delay: 5000, disableOnInteraction: false }" :loop="true"
                :spaceBetween="30" :slidesPerView="1">
                <SwiperSlide>
                  <BarChart :chart-data="chartData" :chart-options="chartOptions" />
                </SwiperSlide>
                <SwiperSlide>
                  <BarChart :chart-data="chart2Data" :chart-options="chart2Options" />
                </SwiperSlide>
              </Swiper>
            </div>
          </div>

          <!-- Category, Location & Assigned User Carousel -->
          <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div v-if="loading" class="flex h-full items-center justify-center">
              <div class="spinner-border inline-block h-8 w-8 animate-spin rounded-full border-4" role="status"></div>
            </div>
            <div v-else>
              <Swiper :modules="[Autoplay]" :autoplay="{ delay: 5000, disableOnInteraction: false }" :loop="true"
                :spaceBetween="30" :slidesPerView="1">
                <SwiperSlide>
                  <PieChart :chart-data="piechartData" :chart-options="piechartOptions" />
                </SwiperSlide>
                <SwiperSlide>
                  <DoughnutChart :chart-data="doughnutData" :chart-options="doughnutOptions" />
                </SwiperSlide>
                <SwiperSlide>
                  <PieChart :chart-data="assignedUserPieData" :chart-options="assignedUserPieOptions" />
                </SwiperSlide>
              </Swiper>
            </div>
          </div>
                <!-- ✅ Small scrolling card for Assets by Category -->
      <div class="mt-4 rounded-xl border border-gray-700 bg-gray-500 p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <h3 class="mb-2 text-lg font-medium">Assets by Category</h3>
        <transition name="fade" mode="out-in">
          <div
            v-if="scrollingItems.length"
            :key="currentScrollIndex"
            class="flex flex-col items-center justify-center p-4 bg-gray-500 rounded-lg"
          >
            <p class="text-md font-semibold">{{ scrollingItems[currentScrollIndex].name }}</p>
            <p class="text-sm text-white">Total: {{ scrollingItems[currentScrollIndex].count }}</p>
           
          </div>
          <div v-else class="p-4 text-center text-gray-500">No data available</div>
        </transition>
      </div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
