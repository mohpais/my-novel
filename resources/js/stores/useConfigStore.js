// src/stores/useConfigStore.js
import { defineStore } from "pinia";
import FlagId from "@/assets/icon/indonesia-circle.png";
import FlagEn from "@/assets/icon/united-states-circle.png";
import { watchEffect } from "vue";

export const useConfigStore = defineStore("config", {
    state: () => ({
        currentLocale: localStorage.getItem("locale") || "id",
        isToggleMenu: localStorage.getItem("isToggleMenu") === "true",
        localesList: [
            {
                id: "id",
                country: "Indonesia",
                icon: FlagId,
            },
            {
                id: "en",
                country: "English",
                icon: FlagEn,
            },
        ],
        deferredPrompt: null,
        isCanInstallPwa: false,
    }),

    getters: {
        isInStandaloneMode: () => {
            return window.matchMedia('(display-mode: standalone)').matches ||
                window.navigator.standalone === true; // iOS Safari support
        },
    },

    actions: {

        // Sidebar toggle
        handleToggleMenu() {
            this.isToggleMenu = !this.isToggleMenu;
            localStorage.setItem("isToggleMenu", this.isToggleMenu);
        },

        // Locale
        changeLocale(locale, type = null) {
            if (typeof locale === "string" && locale.length > 0) {
                this.currentLocale = locale;
                localStorage.setItem("locale", locale);
                if (type === "dropdown") {
                window.location.reload();
                }
            } else {
                throw new Error("Invalid locale");
            }
        },

        // Modal settings
        clickConfiguration() {
            this.isModal.configuration = !this.isModal.configuration;
        },

        clickToggleFontSize() {
            this.isModal.fontSize = !this.isModal.fontSize;
        },

        popUpInstallPwa() {
            return window.addEventListener('beforeinstallprompt', (e) => {
                if (this.isInStandaloneMode) return;
                e.preventDefault(); // mencegah browser menampilkan otomatis
                this.deferredPrompt = e; // simpan event untuk nanti
                console.log('✅ beforeinstallprompt captured');

                this.isCanInstallPwa = true;
            });
        },

        async clickButtonInstallApp() {
            if (this.deferredPrompt) {
                this.deferredPrompt.prompt(); // munculkan banner install
                const {
                outcome
                } = await this.deferredPrompt.userChoice;
                console.log(`User response to install prompt: ${outcome}`);
                this.deferredPrompt = null; // reset
                this.isCanInstallPwa = false;
            }
        }
    },
});
