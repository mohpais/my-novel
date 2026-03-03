<template>
  <label
    :for="id"
    class="flex items-center font-normal text-gray-700 cursor-pointer select-none dark:text-gray-400"
    :class="additionalClasses"
  >
    <input
      type="checkbox"
      :id="id"
      :name="name"
      :checked="modelValue"
      :disabled="disabled"
      :readonly="readonly"
      v-bind="$attrs"
      @change="onChange"
      @blur="onBlurInternal"
      class="sr-only"
    />
    <div
      :class="[
        'mr-2 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500',
        modelValue
          ? 'border-brand-500 bg-brand-500'
          : 'bg-transparent border-gray-300 dark:border-gray-700'
      ]"
    >
      <svg
        v-if="modelValue"
        width="14"
        height="14"
        viewBox="0 0 14 14"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M11.6666 3.5L5.24992 9.91667L2.33325 7"
          stroke="white"
          stroke-width="1.94437"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>
    </div>
    {{ labelName }}
  </label>
</template>

<script setup>
const props = defineProps({
  id: { type: String, required: true },
  name: { type: String, required: true },
  modelValue: { type: Boolean, default: false },
  labelName: { type: String, default: '' },
  disabled: { type: Boolean, default: false },
  readonly: { type: Boolean, default: false },
  additionalClasses: { type: [String, Array, Object], default: '' },
  handleChange: Function,
  handleBlur: Function,
});

const emit = defineEmits(['update:modelValue', 'change', 'blur']);

const onChange = (e) => {
  const newValue = e.target.checked;
  props.handleChange?.(newValue) ?? emit('update:modelValue', newValue);
  emit('change', newValue);
};

const onBlurInternal = () => {
  props.handleBlur?.(props.modelValue);
  emit('blur', props.modelValue);
};
</script>

<style scoped>
/* Tambahkan gaya jika perlu */
</style>
