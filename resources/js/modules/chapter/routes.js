const chapterRoutes = [
    {
        path: '/chapter',
        name: 'Chapter',
        redirect: { name: 'ChapterListPage' },
        meta: {
            layout: 'AdminLayout',
            requiresAuth: true,
            title: 'Chapter',
        },
        children: [
            {
                name: 'ChapterListPage',
                path: 'list',
                component: () => import('@/modules/chapter/pages/ListPage.vue'),
            },
            // {
            //     name: 'ChapterDetailPage',
            //     path: 'detail/:slug',
            //     component: () => import('@/modules/chapter/pages/DetailPage.vue'),
            // },
            {
                name: 'ChapterCreatePage',
                path: 'create',
                component: () => import('@/modules/chapter/pages/CreatePage.vue'),
            }
        ]
    }
];

export default chapterRoutes;
