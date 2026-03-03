<template>
    <aside
        :class="[
            'fixed mt-16 flex flex-col lg:mt-0 top-0 px-5 left-0 bg-brand-600 dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-r border-gray-200',
            {
                'lg:w-[290px]': isExpanded || isMobileOpen || isHovered,
                'lg:w-[90px]': !isExpanded && !isHovered,
                'translate-x-0 w-[290px]': isMobileOpen,
                '-translate-x-full': !isMobileOpen,
                'lg:translate-x-0': true,
            },
        ]"
        @mouseenter="!isExpanded && (isHovered = true)"
        @mouseleave="isHovered = false"
    >
        <div
            :class="[
                'pb-12 pt-4 flex',
                !isExpanded && !isHovered ? 'lg:justify-center' : 'justify-start',
            ]"
        >
            <router-link to="/">
                <img
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="dark:hidden"
                    :src="logoLight"
                    alt="Logo"
                    width="172"
                />
                <img
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="hidden dark:block"
                    :src="logoDark"
                    alt="Logo"
                    width="172"
                />
                <img
                    v-else
                    :src="logoIcon"
                    alt="Logo"
                    width="40"
                    height="40"
                />
            </router-link>
        </div>
        <div
            class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar"
        >
            <nav class="mb-6">
                <div class="flex flex-col gap-4">
                    <div v-for="(menu, groupIndex) in menuStore.menuList" :key="groupIndex" class="flex flex-col">
                        <Button
                            @click="handleLink(menu.path)"
                            :class="{
                                'w-full !py-2': isExpanded || isHovered || isMobileOpen,
                                '!p-2 !w-8 h-8 !mx-auto': !isExpanded && !isHovered && !isMobileOpen
                            }" size="sm" v-if="menu.type === 'button'">
                            <template v-if="isExpanded || isHovered || isMobileOpen">
                                {{ menu.title }}
                            </template>
                            <PlusIcon v-else />
                        </Button>
                        <h2
                            v-else
                            :class="[
                                'mb-4 text-xs uppercase flex leading-[20px] text-gray-100 tracking-wider',
                                !isExpanded && !isHovered
                                ? 'lg:justify-center'
                                : 'justify-start',
                            ]"
                        >
                            <template v-if="isExpanded || isHovered || isMobileOpen">
                                {{ menu.title }}
                            </template>
                            <HorizontalDots v-else />
                        </h2>
                        <ul class="flex flex-col gap-4">
                            <li v-for="(item, index) in menu.items" :key="item.name">
                                <button
                                    v-if="item.subItems"
                                    @click="toggleSubmenu(groupIndex, index)"
                                    :class="[
                                        'menu-item group w-full',
                                        {
                                            'menu-item-active': isSubmenuOpen(groupIndex, index),
                                            'menu-item-inactive': !isSubmenuOpen(groupIndex, index),
                                        },
                                        !isExpanded && !isHovered
                                            ? 'lg:justify-center'
                                            : 'lg:justify-start',
                                    ]"
                                >
                                    <span
                                        :class="[
                                            isSubmenuOpen(groupIndex, index)
                                                ? 'menu-item-icon-active'
                                                : 'menu-item-icon-inactive',
                                        ]"
                                    >
                                        <component :is="item.icon" />
                                    </span>
                                    <span
                                        v-if="isExpanded || isHovered || isMobileOpen"
                                        class="menu-item-text"
                                        >{{ item.name }}</span
                                    >
                                    <ChevronDownIcon
                                        v-if="isExpanded || isHovered || isMobileOpen"
                                        :class="[
                                            'ml-auto w-5 h-5 transition-transform duration-200',
                                            {
                                                'rotate-180 text-brand-500': isSubmenuOpen(
                                                    groupIndex,
                                                    index
                                                ),
                                            },
                                        ]"
                                    />
                                </button>
                                <router-link
                                    v-else-if="item.path"
                                    :to="item.path"
                                    :class="[
                                        'menu-item group',
                                        {
                                            'menu-item-active': isActive(item.path),
                                            'menu-item-inactive': !isActive(item.path),
                                        },
                                    ]"
                                >
                                    <span
                                        :class="[
                                            isActive(item.path)
                                                ? 'menu-item-icon-active'
                                                : 'menu-item-icon-inactive',
                                        ]"
                                    >
                                        <template v-if="item.iconType === 'html'">
                                            <i :class="[item.icon, 'text-xl w-5 h-5']" />
                                        </template>
                                        <template v-else>
                                            <component :is="item.icon" />
                                        </template>
                                    </span>
                                    <span
                                        v-if="isExpanded || isHovered || isMobileOpen"
                                        class="menu-item-text"
                                        >{{ item.name }}</span
                                    >
                                </router-link>
                                <transition
                                    @enter="startTransition"
                                    @after-enter="endTransition"
                                    @before-leave="startTransition"
                                    @after-leave="endTransition"
                                >
                                    <div
                                        v-show="
                                            isSubmenuOpen(groupIndex, index) &&
                                            (isExpanded || isHovered || isMobileOpen)
                                        "
                                    >
                                        <ul class="mt-2 space-y-1 ml-9">
                                            <li v-for="subItem in item.subItems" :key="subItem.name">
                                                <router-link
                                                    :to="subItem.path"
                                                    :class="[
                                                        'menu-dropdown-item',
                                                        {
                                                            'menu-dropdown-item-active': isActive(
                                                                subItem.path
                                                            ),
                                                            'menu-dropdown-item-inactive': !isActive(
                                                                subItem.path
                                                            ),
                                                        },
                                                    ]"
                                                >
                                                    {{ subItem.name }}
                                                    <span class="flex items-center gap-1 ml-auto">
                                                        <span
                                                            v-if="subItem.new"
                                                            :class="[
                                                                'menu-dropdown-badge',
                                                                {
                                                                    'menu-dropdown-badge-active': isActive(
                                                                        subItem.path
                                                                    ),
                                                                    'menu-dropdown-badge-inactive': !isActive(
                                                                        subItem.path
                                                                    ),
                                                                },
                                                            ]"
                                                        >
                                                            new
                                                        </span>
                                                        <span
                                                            v-if="subItem.pro"
                                                            :class="[
                                                                'menu-dropdown-badge',
                                                                {
                                                                    'menu-dropdown-badge-active': isActive(
                                                                        subItem.path
                                                                    ),
                                                                    'menu-dropdown-badge-inactive': !isActive(
                                                                        subItem.path
                                                                    ),
                                                                },
                                                            ]"
                                                        >
                                                            pro
                                                        </span>
                                                    </span>
                                                </router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </transition>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- <SidebarWidget v-if="isExpanded || isHovered || isMobileOpen" /> -->
        </div>
    </aside>
</template>

<script setup>
    import { computed, onMounted } from "vue";
    import { useRoute, useRouter } from "vue-router";
    import { useMenuStore } from '@/stores/useMenuStore';
    import {
        HorizontalDots, ChevronDownIcon, PlusIcon
    } from "../../icons";

    import { useSidebar } from "@/composables/useSidebar";

    import logoLight from "@/assets/logos/logo.svg";
    import logoDark from "@/assets/logos/logo-dark.svg";
    import logoIcon from "@/assets/logos/logo-icon.svg";

    const route = useRoute();
    const router = useRouter();
    const menuStore = useMenuStore();
    const { isExpanded, isMobileOpen, isHovered, openSubmenu, menus } = useSidebar();

    const isActive = (path) => {
        return route.path === path
    }

    const toggleSubmenu = (groupIndex, itemIndex) => {
        const key = `${groupIndex}-${itemIndex}`;
        openSubmenu.value = openSubmenu.value === key ? null : key;
    };
    
    const isAnySubmenuRouteActive = computed(() => {
        return menuStore.menuList.some((group) =>
            group.items.some((item) => {
                item.subItems && item.subItems.some((subItem) => isActive(subItem.path))
            })
        );
    });

    const isSubmenuOpen = (groupIndex, itemIndex) => {
        const key = `${groupIndex}-${itemIndex}`;
        return (
            openSubmenu.value === key ||
            (isAnySubmenuRouteActive.value &&
            menus[groupIndex].items[itemIndex].subItems?.some((subItem) =>
                isActive(subItem.path)
            ))
        );
    };

    const handleLink = (path) => {
        router.push(path);
    }

    const startTransition = (el) => {
        el.style.height = "auto";
        const height = el.scrollHeight;
        el.style.height = "0px";
        el.offsetHeight; // force reflow
        el.style.height = height + "px";
    };

    const endTransition = (el) => {
        el.style.height = "";
    };

    onMounted(() => {
        menuStore.getMenus();
    });
</script>
