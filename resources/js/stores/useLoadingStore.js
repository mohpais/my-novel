// src/stores/useLoadingStore.js
import { defineStore } from 'pinia'

export const useLoadingStore = defineStore('loading', {
    state: () => ({
        activeRequests: 0,
        isLoading: false
    }),
    getters: {
        isLoadingRequest: state => state.activeRequests > 0
    },
    actions: {
        start(hasRequest = false) {
            if (hasRequest) {
                this.activeRequests++;
            }
            this.isLoading = true
        },
        stop(hasRequest = false) {
            if (hasRequest) {
                this.activeRequests = Math.max(0, this.activeRequests - 1)
            }
            this.isLoading = false
        }
    }
})
