<script setup>
    import { onMounted, ref } from 'vue';
    import { useAuthStore } from '@/stores/useAuthStore';
    import { useRouter } from 'vue-router';

    import BookingActivitiyWidget from '../widgets/BookingActivitiyWidget.vue';
    import ProviderDemographicWidget from '../widgets/ProviderDemographicWidget.vue';
    import HighlightWidget from '../widgets/HighlightWidget.vue';
    import MonthlyTarget from '../widgets/MonthlyTarget.vue';
    import EcommerceMetrics from '../widgets/EcommerceMetrics.vue';
    import MonthlySale from '../widgets/MonthlySale.vue';
    import StatisticsChart from '../widgets/StatisticsChart.vue';
    import CustomerDemographic from '../widgets/CustomerDemographic.vue';
    import RecentOrders from '../widgets/RecentOrders.vue';

    const authStore = useAuthStore();

    const isRequester = ref(authStore.isRequester);
    const router = useRouter();

    onMounted(() => {
        // console.log('user:', authStore.user);
        console.log('isRequester:', isRequester.value);
        if (isRequester.value) {
            router.push({ name: 'RequestListPage' }); // Redirect to the request creation page
        }
    });
</script>

<template>
    <div class="grid grid-cols-12 gap-4 md:gap-6">
      <div class="col-span-12 space-y-6 xl:col-span-7">
        <ecommerce-metrics />
        <monthly-sale />
      </div>
      <div class="col-span-12 xl:col-span-5">
        <monthly-target />
      </div>

      <div class="col-span-12">
        <statistics-chart />
      </div>

      <div class="col-span-12 xl:col-span-5">
        <customer-demographic />
      </div>

      <div class="col-span-12 xl:col-span-7">
        <recent-orders />
      </div>
    </div>
</template>
