const requestRoutes = [
    {
        path: '/request',
        name: 'Request',
        redirect: { name: 'RequestListPage' },
        component: () => import('@/modules/request/pages/IndexPage.vue'),
        meta: {
            layout: 'AdminLayout',
            requiresAuth: true,
            title: 'Request',
        },
        children: [
            {
                name: 'RequestListPage',
                path: 'list',
                component: () => import('@/modules/request/pages/ListPage.vue'),
            },
            {
                name: 'RequestWorklistPage',
                path: `worklist`,
                redirect: { name: 'RequestWorklistPendingPage' },
                component: () => import('@/modules/request/pages/WorklistPage.vue'),
                children: [
                    {
                        name: 'RequestWorklistPendingPage',
                        path: 'pending',
                        component: () => import('@/modules/request/pages/PendingTab.vue'),
                    },
                    {
                        name: 'RequestWorklistHistoryPage',
                        path: 'history',
                        component: () => import('@/modules/request/pages/HistoryTab.vue'),
                    }
                ]
            },
            {
                name: 'RequestDetailPage',
                path: 'detail/:request_code',
                component: () => import('@/modules/request/pages/DetailPage.vue'),
            },
            {
                name: 'RequestCreatePage',
                path: 'create',
                component: () => import('@/modules/request/pages/CreatePage.vue'),
            }
        ]
    }
];

export default requestRoutes;
