<template>
    <div class="col-span-12">
        <div class="rounded-lg bg-white p-4 border dark:border-0 border-gray-200 shadow dark:bg-gray-700 md:p-6">
            <div class="divide-y-2 divide-gray-100 dark:divide-gray-600">
                <div class="flex felx-col UYOSZJ1_pv3B5nt1ujCP pb-4 md:flex-row md:items-start md:justify-between eVEHKvmQTgrcFfcnBoRJ">
                    <div>
                        <h2 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Booking Activity</h2>
                        <p class="text-gray-500 dark:text-gray-400">You can see the movements of</p>
                    </div>
                    <div class="flex items-center">
                        <ul class="flex flex-wrap text-center text-sm font-medium text-gray-900 dark:text-gray-400">
                            <li v-for="option in options" :key="option" class="mr-2 lg:mr-4">
                                <button
                                    type="button"
                                    :class="{
                                        'inline-block rounded-lg bg-blue-600 px-3 py-2 text-white dark:bg-blue-500': selectedView === option,
                                        'inline-flex items-center rounded-lg border border-gray-200 bg-gray-100 px-3 py-2 text-sm font-medium hover:bg-blue-100 hover:text-gray-400 focus:z-10 focus:outline-none focus:shadow focus:ring-gray-100 dark:border-gray-500 dark:bg-gray-700 shadow dark:hover:bg-gray-600 dark:hover:text-gray-300 dark:focus:ring-gray-700': selectedView !== option
                                    }"
                                    @click="setView(option)"
                                >
                                    {{ option }}
                                </button>
                            </li>
                            <!-- <li class="mr-2 lg:mr-4">
                                <button type="button" class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-100 px-3 py-2 text-sm font-medium text-gray-900 dark:text-gray-400 hover:bg-blue-100 hover:text-gray-400 focus:z-10 focus:outline-none focus:shadow focus:ring-gray-100 dark:border-gray-500 dark:bg-gray-700 shadow dark:hover:bg-gray-600 dark:hover:text-gray-300 dark:focus:ring-gray-700">Today</button>
                            </li>
                            <li class="mr-2 lg:mr-4">
                                <button type="button" class="active inline-block rounded-lg bg-blue-600 px-3 py-2 text-white dark:bg-blue-500">Weekly</button>
                            </li>
                            <li>
                                <button type="button" class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-100 px-3 py-2 text-sm font-medium text-gray-900 dark:text-gray-400 hover:bg-blue-100 hover:text-gray-400 focus:z-10 focus:outline-none focus:shadow focus:ring-gray-100 dark:border-gray-500 dark:bg-gray-700 shadow dark:hover:bg-gray-600 dark:hover:text-gray-300 dark:focus:ring-gray-700" aria-current="page">
                                    Monthly
                                </button>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Chart -->
            <div class="custom-scrollbar max-w-full overflow-x-auto">
                <apexchart
                    type="bar"
                    height="350"
                    :options="chartOptions"
                    :series="chartSeries"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from 'vue'

    const selectedView = ref('Today')
    const options = ['Today', 'Weekly', 'Monthly']

    const dummyData = {
        Today: {
            categories: ['09:00', '10:00', '11:00', '12:00', '13:00'],
            data: [5, 8, 4, 36, 3]
        },
        Weekly: {
            categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            data: [20, 25, 18, 22, 30, 15, 10]
        },
        Monthly: {
            categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            data: [60, 75, 55, 80]
        }
    }

    const setView = (view) => {
        selectedView.value = view
    }

    const chartSeries = computed(() => [
        {
            name: 'Bookings',
            data: dummyData[selectedView.value].data
        }
    ]);

    // Ambil maksimum & bulatkan ke atas ke kelipatan 10
    const maxY = computed(() => {
        const max = Math.max(...dummyData[selectedView.value].data)
        return Math.ceil(max / 10) * 10
    })

    const chartOptions = computed(() => ({
        chart: {
            type: 'bar',
            height: "350px",
            fontFamily: "Inter, sans-serif",
            toolbar: {
                show: false
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            style: {
                fontFamily: "Inter, sans-serif",
            },
        },
        xaxis: {
            floating: false,
            labels: {
                show: true,
                style: {
                    fontFamily: "Inter, sans-serif",
                    cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                }
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            categories: dummyData[selectedView.value].categories
        },
        yaxis: {
            min: 0,
            max: maxY.value,
            tickAmount: maxY.value / 10, // naik per 10
            // show: false,
            labels: {
                style: {
                    fontFamily: "Inter, sans-serif",
                    cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                }
            }
        },
        fill: {
            opacity: 1,
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "70%",
                borderRadiusApplication: "end",
                borderRadius: 8,
            }
        },
        states: {
            hover: {
                filter: {
                    type: "darken",
                    value: 1,
                },
            },
        },
        stroke: {
            show: true,
            width: 0,
            colors: ["transparent"],
        },
        grid: {
            // show: false,
            borderColor: 'rgba(107, 114, 128, 0.6)', // soft gray line
            strokeDashArray: 1,
            // padding: {
            //     left: 2,
            //     right: 2,
            //     top: -14
            // },
        },
        colors: ['#1A56DB']
    }))
</script>