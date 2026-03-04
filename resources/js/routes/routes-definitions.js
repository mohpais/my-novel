import authRoutes from '../modules/auth/routes';
import dashboardRoutes from '../modules/dashboard/routes';
import userRoutes from '../modules/user/routes';
import novelRoutes from '../modules/novel/routes';

export default [
    {
        path: "/:pathMatch(.*)*",
        name: 'NotFound',
        meta: { layout: 'ErrorLayout' },
        component: () => import("@/modules/exception/pages/NotFoundPage.vue"),
    },
    {
        path: '/',
        redirect: '/request/list',
    },
    ...authRoutes,
    ...dashboardRoutes,
    ...userRoutes,
    ...novelRoutes,
];
