const novelRoutes = [
    {
        path: '/novel',
        name: 'Novel',
        redirect: { name: 'NovelListPage' },
        meta: {
            layout: 'AdminLayout',
            requiresAuth: true,
            title: 'Novel',
        },
        children: [
            {
                name: 'NovelListPage',
                path: 'list',
                component: () => import('@/modules/novel/pages/ListPage.vue'),
            },
            // {
            //     name: 'NovelDetailPage',
            //     path: 'detail/:slug',
            //     component: () => import('@/modules/novel/pages/DetailPage.vue'),
            // },
            // {
            //     name: 'NovelCreatePage',
            //     path: 'create',
            //     component: () => import('@/modules/novel/pages/CreatePage.vue'),
            // }
        ]
    }
];

export default novelRoutes;
