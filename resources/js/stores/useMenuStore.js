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
    BarChartIcon,
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
            {
                title: "Menu",
                items: [
                    {
                        icon: markRaw(GridIcon),
                        name: "Dashboard",
                        path: "/dashboard",
                        show: _handleShowMenu(),
                    },
                    {
                        icon: markRaw(FormsIcon),
                        name: "My Novel",
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
                        icon: markRaw(BarChartIcon),
                        name: "Management Data",
                        path: "/management/data",
                        show: _handleShowMenu(['superadmin', 'admin']),
                    },
                ],
                show: _handleShowMenu(['superadmin', 'admin']),
            },
            {
                title: "AI Assistant",
                items: [
                    {
                        icon: markRaw(UserCircleIcon),
                        name: "Improve Narrative",
                        path: "/user",
                        show: _handleShowMenu(['superadmin']),
                    },
                    {
                        icon: markRaw(UserCircleIcon),
                        name: "Generate Dialogue",
                        path: "/user",
                        show: _handleShowMenu(['superadmin']),
                    },
                    {
                        icon: markRaw(UserCircleIcon),
                        name: "Character Helper",
                        path: "/user",
                        show: _handleShowMenu(['superadmin']),
                    },
                    {
                        icon: markRaw(UserCircleIcon),
                        name: "Story Planner",
                        path: "/user",
                        show: _handleShowMenu(['superadmin']),
                    },
                    {
                        icon: markRaw(UserCircleIcon),
                        name: "Worldbuilding Helper",
                        path: "/user",
                        show: _handleShowMenu(['superadmin']),
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
