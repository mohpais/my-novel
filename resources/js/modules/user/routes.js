const userRoutes = [
    {
        path: '/user',
        name: 'User',
        redirect: { name: 'UserListPage' },
        meta: {
            layout: 'AdminLayout',
            requiresAuth: true,
            title: 'User',
        },
        children: [
            {
                name: 'UserListPage',
                path: 'list',
                component: () => import('@/modules/user/pages/ListPage.vue'),
            },
            {
                name: 'UserProfilePage',
                path: 'profile',
                component: () => import('@/modules/user/pages/ProfilePage.vue'),
            }
        ]
    }
];

export default userRoutes;
