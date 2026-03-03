<template>
  <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <BackButton v-if="withBackButton" />
    <h2 class="text-lg font-semibold text-gray-700 dark:text-white/90">
      {{ pageTitle }}
    </h2>
    <div class="flex ms-auto">
      <slot name="actions">
        <!-- Default empty slot -->
         <nav>
            <ol class="flex items-center gap-1.5">
              <li>
                <router-link
                  class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                  to="/"
                >
                  Home
                  <svg
                    class="stroke-current"
                    width="17"
                    height="16"
                    viewBox="0 0 17 16"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                      stroke=""
                      stroke-width="1.2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg>
                </router-link>
              </li>
              <li class="text-sm text-gray-800 dark:text-white/90">
                {{ pageTitle }}
              </li>
            </ol>
          </nav>
      </slot>>
    </div>
  </div>
</template>

<script setup>
    import { defineAsyncComponent } from "vue";

    const BackButton = defineAsyncComponent(() =>
        import("@/components/commons/button/Back.vue"),
    );
    defineProps({
      pageTitle: {
        type: String,
        required: true
      },
      withBackButton: {
        type: Boolean,
        default: false
      }
    })
</script>
