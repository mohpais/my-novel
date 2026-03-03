const financeRoutes = [
    {
        path: '/finance',
        name: 'Finance',
        component: () => import('@/modules/finance/pages/IndexPage.vue'),
        // redirect: {
        //     to: { name: 'FinanceDetail', params: { page: 'business-unit' } }
        // },
        meta: {
            layout: 'AdminLayout',
            requiresAuth: true,
            title: 'Finance',
        },
    }
];

export default financeRoutes;
