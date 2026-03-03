import { useLoadingStore } from '@/stores/useLoadingStore'
import { useLayoutManager } from './useLayoutManager'
import { flushToastQueue } from './useToastAfterLayout'
import { nextTick } from 'vue'

export function usePageTransition() {
  const loadingStore = useLoadingStore()
  const { markLayoutStart, markLayoutReady } = useLayoutManager()

  function start() {
    loadingStore.start()
    markLayoutStart()
  }

  function stop(delay = 300) {
    nextTick(() => {
      setTimeout(() => {
        markLayoutReady()
        flushToastQueue()
        loadingStore.stop()
      }, delay)
    })
  }

  return { start, stop, isLoading: loadingStore.isLoading }
}
