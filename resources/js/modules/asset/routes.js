const assetRoutes = [
    {
        path: '/asset',
        name: 'Asset',
        redirect: { name: 'AssetListPage' },
        meta: {
            layout: 'AdminLayout',
            requiresAuth: true,
            title: 'Asset',
        },
        children: [
            {
                name: 'AssetListPage',
                path: 'list',
                component: () => import('@/modules/asset/pages/IndexPage.vue'),
            },
            // {
            //     name: 'AssetCreatePage',
            //     path: 'create',
            //     component: () => import('@/modules/asset/pages/CreatePage.vue'),
            // }
        ]
    }
];

export default assetRoutes;
