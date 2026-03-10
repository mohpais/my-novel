import authRoutes from '../modules/auth/routes';
import dashboardRoutes from '../modules/dashboard/routes';
import userRoutes from '../modules/user/routes';
import novelRoutes from '../modules/novel/routes';
import chapterRoutes from '../modules/chapter/routes';

export default [
    {
        path: "/:pathMatch(.*)*",
        name: 'NotFound',
        meta: { layout: 'ErrorLayout' },
        component: () => import("@/modules/exception/pages/NotFoundPage.vue"),
    },
    {
        path: '/',
        redirect: '/dashboard',
    },
    ...authRoutes,
    ...dashboardRoutes,
    ...userRoutes,
    ...novelRoutes,
    ...chapterRoutes,
];
