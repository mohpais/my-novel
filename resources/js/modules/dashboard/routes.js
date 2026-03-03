const dashboardRoutes = [
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('@/modules/dashboard/pages/IndexPage.vue'),
        meta: {
            layout: 'AdminLayout',
            requiresAuth: true,
            title: 'Dashboard',
        },
    }
];

export default dashboardRoutes;
