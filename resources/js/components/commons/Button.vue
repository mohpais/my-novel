<template>
  <button
    :class="buttonClasses"
    @click="onClick"
    :disabled="disabled || loading"
  >
    <span v-if="loading" class="flex items-center gap-1">
      <svg
        class="w-4 h-4 animate-spin text-current"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        />
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8v8z"
        />
      </svg>
      Loading...
    </span>
    <template v-else>
      <span v-if="startIcon" class="flex items-center">
        <component :is="startIcon" />
      </span>
      <slot></slot>
      <span v-if="endIcon" class="flex items-center">
        <component :is="endIcon" />
      </span>
    </template>
  </button>
</template>

<script setup lang="js">
import { computed } from 'vue'

const props = defineProps({
  size: {
    type: String,
    default: 'sm',
    validator: (v) => ['xs', 'sm', 'md', 'lg'].includes(v),
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (v) => ['primary', 'outline'].includes(v),
  },
  startIcon: {
    type: Object,
    default: null,
  },
  endIcon: {
    type: Object,
    default: null,
  },
  onClick: {
    type: Function,
    default: null,
  },
  className: {
    type: [String, Array, Object],
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
})

const sizeClasses = {
  xs: 'px-3 py-2 text-xs',
  sm: 'px-4 py-3 text-sm',
  md: 'px-5 py-3.5 text-sm',
}

const variantClasses = {
  primary: 'bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300',
  outline:
    'bg-white text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] dark:hover:text-gray-300',
}

const buttonClasses = computed(() => [
  'inline-flex items-center justify-center font-medium gap-2 rounded-lg transition',
  sizeClasses[props.size],
  variantClasses[props.variant],
  props.className,
  { 'cursor-not-allowed opacity-50': props.disabled || props.loading },
])

const onClick = () => {
  if (!props.disabled && !props.loading && props.onClick) {
    props.onClick()
  }
}
</script>
