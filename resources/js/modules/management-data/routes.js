const managementDataRoutes = [
    {
        path: '/management-data',
        name: 'ManagementData',
        redirect: { name: 'NovelListPage' },
        component: () => import('@/modules/request/pages/IndexPage.vue'),
        meta: {
            layout: 'AdminLayout',
            requiresAuth: true,
            title: 'Management Data',
        },
        children: [
            {
                name: 'NovelListPage',
                path: 'list',
                component: () => import('@/modules/novel/pages/ListPage.vue'),
            },
        ]
    }
];

export default managementDataRoutes;
