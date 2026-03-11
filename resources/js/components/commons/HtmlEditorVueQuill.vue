<script setup>
import { ref, watch } from "vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const model = defineModel({ type: String, default: "" });

const props = defineProps({
    // Prop label tetap ada untuk fallback, tapi tidak dirender jika lewat FormField
    placeholder: { type: String, default: "Write something..." },
    height: { type: String, default: "250px" },
    disabled: {
        type: Boolean,
        default: false
    },
    readonly: {
        type: Boolean,
        default: false
    },
    hasError: { type: Boolean, default: false },
});

const content = ref(model.value);

const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    [{ header: 1 }, { header: 2 }],
    [{ list: 'ordered' }, { list: 'bullet' }],
    [{ script: 'sub' }, { script: 'super' }],
    [{ indent: '-1' }, { indent: '+1' }],
    [{ direction: 'rtl' }],
    [{ size: ['small', false, 'large', 'huge'] }],
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    [{ color: [] }, { background: [] }],
    [{ font: [] }],
    [{ align: [] }],
    ['link', 'blockquote', 'code-block', 'image'],
    ['clean']
];

watch(content, (val) => {
    model.value = val;
});

// Sinkronisasi balik jika model berubah dari luar (misal reset form)
watch(() => model.value, (newVal) => {
    if (newVal !== content.value) {
        content.value = newVal;
    }
});
</script>

<template>
    <div class="html-editor-wrapper":class="{ 'has-error': props.hasError }">
        <QuillEditor
            v-model:content="content"
            contentType="html"
            theme="snow"
            :toolbar="toolbarOptions"
            :placeholder="props.placeholder"
            :read-only="readonly || disabled"
            :style="{ height: props.height }"
        />
    </div>
</template>

<style>
/* 
  Pastikan Tailwind dapat memproses blok <style> ini secara global.
  Anda dapat menggunakan sintaks CSS biasa atau @apply.
*/

/* Menyesuaikan border agar lebih senada dengan input Tailwind lainnya */
:deep(.ql-toolbar.ql-snow) {
    @apply border-gray-300 dark:border-gray-700 rounded-t-lg bg-gray-50 dark:bg-gray-900;
}

:deep(.ql-container.ql-snow) {
    @apply border-gray-300 dark:border-gray-700 rounded-b-lg bg-white dark:bg-gray-900;
}

/* State Error: Border berubah merah */
.has-error :deep(.ql-toolbar.ql-snow) {
    @apply border-red-500 dark:border-red-500;
}

.has-error :deep(.ql-container.ql-snow) {
    @apply border-red-500 dark:border-red-500;
}

/* Opsional: Ubah warna toolbar saat error agar lebih terlihat */
.has-error :deep(.ql-toolbar.ql-snow) {
    @apply bg-red-50 dark:bg-red-900/10;
}

/* Light Mode (Default) Styles */
.ql-editor {
  @apply bg-white dark:bg-gray-900 text-gray-800;
}

.ql-toolbar.ql-snow {
  @apply bg-white border-gray-300 dark:bg-gray-900 dark:border-gray-700 rounded mt-1;
}

.ql-snow .ql-picker {
    @apply text-gray-600 dark:text-white/90;
}

.ql-snow .ql-stroke {
    @apply stroke-gray-600 dark:stroke-gray-400;
}

.ql-snow .ql-fill, .ql-snow .ql-stroke.ql-fill {
    @apply fill-gray-600 dark:fill-gray-400;
}

.ql-snow .ql-formats button {
    @apply text-gray-600 dark:text-gray-100;
}

.ql-container.ql-snow {
    @apply bg-gray-100 border-gray-300 dark:bg-gray-900 dark:border-gray-700 rounded mt-0;
}

.ql-container.ql-snow > .ql-editor {
    @apply text-gray-800 dark:text-white/90;
}

.ql-snow .ql-formats button {
  @apply text-gray-600;
}

.ql-editor.ql-blank::before {
  @apply text-gray-400;
}
</style>
