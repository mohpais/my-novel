const authRoutes = [
    {
        path: '/auth',
        name: 'Login',
        redirect: { name: 'LoginPage' },
        children: [
            {
                path: 'login',
                name: 'LoginPage',
                component: () => import('@/modules/auth/pages/LoginPage.vue'),
                meta: {
                    layout: 'AuthLayout',
                    title: 'Login',
                    redirectIfLoggedIn: true
                },
            },
            {
                path: 'register',
                name: 'RegisterPage',
                component: () => import('@/modules/auth/pages/RegisterPage.vue'),
                meta: {
                    layout: 'AuthLayout',
                    title: 'Register',
                    redirectIfLoggedIn: true
                },
            },
        ]
    }
];

export default authRoutes;
