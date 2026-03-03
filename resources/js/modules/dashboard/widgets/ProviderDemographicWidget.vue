<template>
    <div class="col-span-12 xl:col-span-5">
        <div class="mb-4 rounded-lg bg-white p-4 border dark:border-0 border-gray-200 shadow dark:bg-gray-700 md:p-6">
            <div class="divide-y-2 divide-gray-100 dark:divide-gray-600">
                <div class="flex felx-col pb-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <h2 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Providers Demographic</h2>
                        <p class="text-gray-500 dark:text-gray-400">You can number of providers based on country</p>
                    </div>
                </div>
            </div>
            <div class="border-gray-200 mb-6 overflow-hidden rounded-2xl border bg-gray-50 px-4 pb-6 dark:border-gray-800 dark:bg-gray-900 sm:px-6">
                <v-chart :option="chartOptions" style="height: 250px; width: 100%;" autoresize />
            </div>
            <div class="space-y-5">
                <div v-for="provinsi in dataPerProvinsi" :key="provinsi.key" class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                {{ provinsi.name }}
                            </p>
                            <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                                {{ formatNumber(provinsi.value) }} Providers
                            </span>
                        </div>
                    </div>

                    <div class="flex w-full max-w-[140px] items-center gap-3">
                        <div class="relative block h-2 w-full max-w-[100px] rounded-sm bg-gray-200 dark:bg-gray-800">
                            <div class="absolute left-0 top-0 flex h-full w-[79%] items-center justify-center rounded-sm bg-blue-500 text-xs font-medium text-white"></div>
                        </div>
                        <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                            79%
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { use } from 'echarts/core'
    import { MapChart } from 'echarts/charts'
    import { CanvasRenderer } from 'echarts/renderers'
    import { TooltipComponent, VisualMapComponent } from 'echarts/components'
    import VChart from 'vue-echarts'
    import * as echarts from 'echarts';
    import { formatNumber } from '@/utils'

    // Import peta Indonesia (GeoJSON)
    import indonesiaMap from '@/assets/indonesia-province.geo.json'

    // Registrasi elemen yang dipakai
    use([
        MapChart,
        TooltipComponent,
        VisualMapComponent,
        CanvasRenderer
    ])

    // Registrasi peta
    echarts.registerMap('indonesia', indonesiaMap)

    // Dummy data per provinsi
    const dataPerProvinsi = [
        { name: 'DI. ACEH', value: 120 },
        { name: 'BALI', value: 300 },
        { name: 'BANTEN', value: 450 },
        { name: 'DKI JAKARTA', value: 1230 },
        { name: 'JAWA BARAT', value: 950 },
        { name: 'JAWA TENGAH', value: 700 },
        { name: 'JAWA TIMUR', value: 880 },
        { name: 'YOGYAKARTA', value: 260 },
        { name: 'KALIMANTAN TIMUR', value: 150 },
        { name: 'SULAWESI SELATAN', value: 400 },
        { name: 'SUMATERA UTARA', value: 320 }
        // TAMBAH SESUAI KEBUTUHAN
    ]

    // Konfigurasi chart
    const chartOptions = {
        tooltip: {
            trigger: 'item',
        },
        visualMap: {
            min: 0,
            max: 1000,
            left: 'left',
            bottom: '5%',
            text: ['Munch', 'Less'],
            textStyle: {
                color: '#fff'
            },
            inRange: {
                color: ['#a2c0fa', '#4279e3']
            },
            calculable: true
        },
        series: [
            {
                name: 'Total Booking',
                type: 'map',
                nameProperty: 'Propinsi', // penting agar tooltip cocok
                map: 'indonesia',
                roam: true,
                label: {
                    show: false // sembunyikan label di peta
                },
                itemStyle: {
                    areaColor: '#697387',
                    borderColor: '#22252b',
                    borderWidth: 0.5
                },
                emphasis: {
                    label: {
                        show: false
                    }
                },
                data: dataPerProvinsi
            }
        ]
    }
</script>