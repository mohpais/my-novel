<!-- <template><div class="mb-4 rounded-lg bg-white p-4 border dark:border-0 border-gray-200 shadow dark:bg-gray-700 md:p-6">
        <div class="divide-y-2 divide-gray-100 dark:divide-gray-600">
            <div class="flex felx-col UYOSZJ1_pv3B5nt1ujCP pb-4 md:flex-row md:items-start md:justify-between eVEHKvmQTgrcFfcnBoRJ">
                <div>
                    <h2 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Booking Activity</h2>
                    <p class="text-gray-500 dark:text-gray-400">You can see the movements of</p>
                </div>
            </div>
        </div>
        <div class="max-w-full overflow-x-auto">
            <l-map style="height: 400px;" :zoom="5" :center="[-2.5, 118]" @ready="onMapReady">
                <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" />
                <l-geo-json
                    :geojson="geoData"
                    @click="handleClick"
                />
            </l-map>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import L from 'leaflet';
    import { LMap, LTileLayer, LGeoJson } from '@vue-leaflet/vue-leaflet'

    // GeoJSON kamu
    import geoJson from '@/assets/indonesia-province.geo.json'

    // STATE
    const geoData = ref(geoJson) // Ini harus ada, karena template pakai :geojson="geoData"
    const mapObject = ref(null)

    function onMapReady(e) {
        mapObject.value = e.mapObject
        console.log('Map is ready!', mapObject.value)
    }

    function handleClick(e) {
        const province = e.target.feature.properties.name
        console.log('Klik pada provinsi:', province)
    }
</script> -->