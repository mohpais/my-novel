<script setup>
import { markRaw, provide, ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { usePageTransition } from '@/composables/usePageTransition'
import { useLayoutManager } from '@/composables/useLayoutManager'
import { useConfigStore } from '@/stores/useConfigStore'
import { ToastifyContainer } from 'vue3-toastify'

import DefaultLayout from '@/layouts/DefaultLayout.vue'
import LoadingOverlay from '@/components/ui/LoadingOverlay.vue'
import ThemeProvider from '@/components/ui/ThemeProvider.vue'

const route = useRoute()
const configStore = useConfigStore()
const { start, stop } = usePageTransition()
const { markLayoutReady, isLayoutReady } = useLayoutManager()

const layout = ref(null)

provide('currentTheme', configStore.currentTheme)

onMounted(markLayoutReady) // initial ready

watch(
  () => route.meta?.layout,
  async (metaLayout) => {
    start()

    if (metaLayout) {
      try {
        const mod = await import(`@/layouts/${metaLayout}.vue`)
        layout.value = markRaw(mod.default || DefaultLayout)
      } catch (err) {
        console.error(`Failed to load layout: ${metaLayout}, falling back`, err)
      }
    }

    stop() // delay & flush handled inside stop()
  },
  { immediate: true }
)
</script>

<template>
  <div id="app_main" class="relative">
    <LoadingOverlay>
      <template #default>
        <div class="flex flex-col items-center space-y-2 bg-gray-200 dark:bg-gray-700 h-full w-full">
          <div class="m-auto">
            <div class="triangle1"></div>
            <div class="triangle2"></div>
            <p class="text">Please Wait</p>
          </div>
        </div>
      </template>
    </LoadingOverlay>
    <ToastifyContainer />
    <ThemeProvider>
      <transition name="fade-layout" mode="out-in" appear>
        <component
          v-if="layout && isLayoutReady"
          :is="layout"
          :key="layout?.name"
        >
          <router-view v-slot="{ Component }">
            <component :is="Component" :key="$route.fullPath" />
          </router-view>
        </component>
      </transition>
    </ThemeProvider>
  </div>
</template>

<style>
  /* From Uiverse.io by Cksunandh */
.triangle1 {
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 50px 50px 0 0;
  border-color: #8086e0 transparent transparent transparent;
  margin: 0 auto;
  animation: shk1 1s ease-in-out infinite normal;
}

.triangle2 {
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 0 0 50px 50px;
  border-color: transparent transparent #6554b388 transparent;
  margin: -50px auto 0;
  animation: shk2 1s ease-in-out infinite alternate;
}
@keyframes shk1 {
  0% {
    transform: rotate(-360deg);
  }

  100% {
  }
}

@keyframes shk2 {
  0% {
    transform: rotate(360deg);
  }
  100% {
  }
}

.text {
  color: #949494;
  margin: 30px auto;
  text-align: center;
  font-weight: 500;
  letter-spacing: 4px;
}

</style>
