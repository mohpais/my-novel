import authRoutes from '../modules/auth/routes';
import dashboardRoutes from '../modules/dashboard/routes';
import userRoutes from '../modules/user/routes';
import financeRoutes from '../modules/finance/routes';
import assetRoutes from '../modules/asset/routes';
import requestRoutes from '../modules/request/routes';

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
    ...financeRoutes,
    ...assetRoutes,
    ...requestRoutes,
];
