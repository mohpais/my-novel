// src/composables/useLayoutManager.js
import { ref } from 'vue'

const isLayoutReady = ref(false)
let resolveQueue = []

export function useLayoutManager() {
    function waitForLayout() {
        return new Promise(resolve => {
            if (isLayoutReady.value) {
                resolve()
            } else {
                resolveQueue.push(resolve)
            }
        })
    }

    function markLayoutStart() {
        isLayoutReady.value = false
    }

    function markLayoutReady() {
        isLayoutReady.value = true
        resolveQueue.forEach(resolve => resolve())
        resolveQueue = []
    }

    return {
        waitForLayout,
        markLayoutStart,
        markLayoutReady,
        isLayoutReady
    }
}
