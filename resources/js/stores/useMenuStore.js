import { ref, markRaw, watch, computed } from "vue";
import { defineStore } from "pinia";
import { useAuthStore } from "@/stores/useAuthStore";
import { useTranslation } from '@/composables/useTranslation';
import {
    BuildingFillDown,
    GridIcon,
    UserCircleIcon,
    DocsIcon,
    FormsIcon,
    DollarCircleIcon,
} from "@/components/icons";

export const useMenuStore = defineStore("menu", () => {
    const authStore = useAuthStore();
    const { i18n } = useTranslation();
    const { t } = i18n.global;

    let menuList = ref([]);

    const _handleShowMenu = (requiredRoles) => {
        if (!requiredRoles) return true;
        if (!authStore.user) {
            return false;
        }
        const role = authStore.user?.role;
        return requiredRoles.includes(role.code);
    };

    // PENTING: Ubah allMenus menjadi computed property
    const filteredMenus = computed(() => {
        const rawMenus = [
            // {
            //     title: "Create Request",
            //     type: "button",
            //     items: [],
            //     path: "/request/create",
            //     show: true,
            // },
            // {
            //     title: "Menu",
            //     items: [
            //         {
            //             icon: markRaw(GridIcon),
            //             name: "Dashboard",
            //             path: "/dashboard",
            //             show: _handleShowMenu(),
            //         },
            //         {
            //             icon: markRaw(BuildingFillDown),
            //             name: "Assets",
            //             path: "/asset/list",
            //             show: _handleShowMenu(),
            //         },
            //     ],
            // },
            {
                // title: t("App.Workspace"),
                title: "Menu",
                items: [
                    {
                        icon: markRaw(GridIcon),
                        name: "Dashboard",
                        path: "/dashboard",
                        show: _handleShowMenu(),
                    },
                    {
                        // icon: 'fa-solid fa-file-lines',
                        // iconType: 'html',
                        icon: markRaw(FormsIcon),
                        name: "Novel List",
                        path: "/novel/list",
                        show: _handleShowMenu(),
                    },
                ],
            },
            {
                title: t("App.Data_Management"),
                items: [
                    {
                        icon: markRaw(UserCircleIcon),
                        name: t("App.User"),
                        path: "/user",
                        show: _handleShowMenu(['superadmin']),
                    },
                    {
                        icon: markRaw(DollarCircleIcon),
                        name: t("App.Finance"),
                        path: "/finance",
                        show: _handleShowMenu(['superadmin', 'admin']),
                    },
                ],
                show: _handleShowMenu(['superadmin', 'admin']),
            },
        ];

        return rawMenus
            .filter(section => section.show === undefined || section.show)
            .map(section => ({
                ...section,
                items: section.items.filter(item => item.show)
            }))
            .filter(section => section.type !== "button" ? section.items.length > 0 : true)
    });

    const getMenus = () => {
      // Cukup set menuList ke nilai computed
      menuList.value = filteredMenus.value;
    };

    return {
        menuList,
        getMenus,
    };
});
