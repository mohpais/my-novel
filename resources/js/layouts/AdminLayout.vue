<script setup>
import SidebarProvider from '@/components/ui/SidebarProvider.vue'
import Backdrop from '@/components/ui/Backdrop.vue'
import HeaderApp from '@/components/ui/header/HeaderApp.vue'
import SidebarApp from '@/components/ui/sidebar/SidebarApp.vue'

import { useSidebar } from '@/composables/useSidebar'
</script>

<template>
    <div class="admin-layout">
        <SidebarProvider>
            <div class="admin-container min-h-screen 2xl:flex">
                <!-- Sidebar -->
                <SidebarApp />
                <Backdrop />

                <!-- 👇 Inline SidebarContent -->
                <SidebarContent />
            </div>
        </SidebarProvider>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import HeaderApp from '@/components/ui/header/HeaderApp.vue'

export const SidebarContent = defineComponent({
  name: 'SidebarContent',
  setup() {
    const { isExpanded, isHovered } = useSidebar()

    return {
      isExpanded,
      isHovered,
    }
  },
  components: {
    HeaderApp,
  },
  template: `
    <div
      class="flex-1 transition-all duration-300 ease-in-out"
      :class="[isExpanded || isHovered ? 'lg:ml-[290px]' : 'lg:ml-[90px]']"
    >
      <HeaderApp />
      <main
        class="p-4 md:p-6 w-full">
        <router-view />
      </main>
    </div>
  `,
})
</script>

<style scoped></style>
