<script setup>
import { ref, watch, onBeforeUnmount } from "vue"
import { EditorContent, useEditor } from "@tiptap/vue-3"
import { Extension } from "@tiptap/core"

import StarterKit from "@tiptap/starter-kit"
import Placeholder from "@tiptap/extension-placeholder"
import CharacterCount from "@tiptap/extension-character-count"
import TextAlign from "@tiptap/extension-text-align"
import ImageResize from "tiptap-extension-resize-image"

import apiService from "@/utils/apiService"

const model = defineModel({ type: String, default: "" })

const props = defineProps({
  placeholder: { type: String, default: "Write something..." },
  height: { type: String, default: "300px" },
  disabled: { type: Boolean, default: false },
  readonly: { type: Boolean, default: false },
  hasError: { type: Boolean, default: false },
  uploadUrl: { type: String, default: "/upload-image" },
  uploadFolder: { type: String, default: "editor" },
  maxCharacters: { type: Number, default: 50000 }
})

const uploading = ref(false)

const ImageDelete = Extension.create({

  addKeyboardShortcuts() {
    return {
      Delete: () => this.editor.commands.deleteSelection(),
      Backspace: () => this.editor.commands.deleteSelection(),
    }
  }

})

/*
|--------------------------------------------------------------------------
| Editor Instance
|--------------------------------------------------------------------------
*/

const previousImages = ref([])

const extractImages = (html) => {
  const div = document.createElement("div")
  div.innerHTML = html

  return Array.from(div.querySelectorAll("img")).map(img => img.src)
}

const editor = useEditor({
  content: model.value,

  editable: !(props.readonly || props.disabled),

  extensions: [

    StarterKit.configure({
        history: true
    }),

    ImageResize.configure({
        inline: false,
        allowBase64: true
    }),

    ImageDelete,

    TextAlign.configure({
      types: ["heading", "paragraph"]
    }),

    Placeholder.configure({
      placeholder: props.placeholder
    }),

    CharacterCount.configure({
      limit: props.maxCharacters
    })

  ],

  onUpdate({ editor }) {

    const html = editor.getHTML()

    const newImages = extractImages(html)

    const deletedImages = previousImages.value.filter(
        img => !newImages.includes(img)
    )

    if (deletedImages.length) {
        deleteImagesFromServer(deletedImages)
    }

    previousImages.value = newImages

    if (html !== model.value) {
        model.value = html
    }

}

})

/*
|--------------------------------------------------------------------------
| Sync external v-model
|--------------------------------------------------------------------------
*/

watch(
  () => model.value,
  (val) => {

    if (!editor.value) return

    const html = editor.value.getHTML()

    if (html !== val) {
      editor.value.commands.setContent(val || "", false)
    }

  }
)

/*
|--------------------------------------------------------------------------
| Editable watcher
|--------------------------------------------------------------------------
*/

watch(
  () => props.readonly || props.disabled,
  (val) => {

    if (!editor.value) return

    editor.value.setEditable(!val)

  }
)

/*
|--------------------------------------------------------------------------
| Upload Image
|--------------------------------------------------------------------------
*/

const uploadImageFile = async (file) => {

  const formData = new FormData()

  formData.append("image", file)
  formData.append("folder", props.uploadFolder)

  uploading.value = true

  try {

    const res = await apiService.post(props.uploadUrl, formData)

    const url = res?.data?.url

    if (!url) throw new Error("Invalid upload response")

    editor.value
      ?.chain()
      .focus()
      .setImage({ src: url })
      .run();

    previousImages.value.push(url);

  } catch (err) {

    console.error("Image upload failed:", err)

  } finally {

    uploading.value = false

  }

}

/*
|--------------------------------------------------------------------------
| Delete Image
|--------------------------------------------------------------------------
*/

const deleteImagesFromServer = async (images) => {

  try {

    await apiService.post("/delete-image", {
      images
    })

  } catch (error) {

    console.error("Failed delete image:", error)

  }

}

/*
|--------------------------------------------------------------------------
| File Picker
|--------------------------------------------------------------------------
*/

const pickImage = () => {

  const input = document.createElement("input")

  input.type = "file"
  input.accept = "image/*"

  input.onchange = () => {

    const file = input.files?.[0]

    if (file) {
      uploadImageFile(file)
    }

  }

  input.click()

}

/*
|--------------------------------------------------------------------------
| Paste Image
|--------------------------------------------------------------------------
*/

const handlePaste = (event) => {

  const items = event.clipboardData?.items

  if (!items) return

  for (const item of items) {

    if (item.type.includes("image")) {

      const file = item.getAsFile()

      if (file) {

        event.preventDefault()

        uploadImageFile(file)

      }

    }

  }

}

/*
|--------------------------------------------------------------------------
| Drag & Drop Image
|--------------------------------------------------------------------------
*/

const handleDrop = (event) => {

  event.preventDefault()

  const files = event.dataTransfer?.files

  if (!files?.length) return

  for (const file of files) {

    if (file.type.startsWith("image/")) {
      uploadImageFile(file)
    }

  }

}

/*
|--------------------------------------------------------------------------
| Destroy
|--------------------------------------------------------------------------
*/

onBeforeUnmount(() => {

  if (editor.value) {
    editor.value.destroy()
  }

})

/*
|--------------------------------------------------------------------------
| Toolbar Helper
|--------------------------------------------------------------------------
*/

const btnClass = (active) => {

  return [
    "btn-toolbar-tool",
    active
      ? "bg-primary-50 text-primary-600 dark:bg-primary-500/20 dark:text-primary-400 border-primary-200 dark:border-primary-500/50"
      : ""
  ]

}
</script>

<template>

<div
  class="html-editor-wrapper border rounded-lg overflow-hidden"
  :class="{
    'border-red-500': hasError,
    'border-gray-300 dark:border-gray-700': !hasError
  }"
>

<!-- TOOLBAR -->

<div
  v-if="editor"
  class="flex flex-wrap items-center gap-1 p-2 border-b bg-gray-50 dark:bg-gray-800"
>

<button
  type="button"
  @click="editor.chain().focus().toggleBold().run()"
  :class="btnClass(editor.isActive('bold'))"
>
B
</button>

<button
  type="button"
  @click="editor.chain().focus().toggleItalic().run()"
  :class="btnClass(editor.isActive('italic'))"
>
I
</button>

<button
  type="button"
  @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
  :class="btnClass(editor.isActive('heading',{level:1}))"
>
H1
</button>

<button
  type="button"
  @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
  :class="btnClass(editor.isActive('heading',{level:2}))"
>
H2
</button>

<button
  type="button"
  @click="editor.chain().focus().toggleBulletList().run()"
  :class="btnClass(editor.isActive('bulletList'))"
>
<i class="fa-solid fa-list-ul"></i>
</button>

<button
  type="button"
  @click="pickImage"
  class="btn-toolbar-tool"
>
<i class="fa-solid fa-image"></i>
</button>

<button
@click="editor.chain().focus().setTextAlign('left').run()"
:class="btnClass(editor.isActive({ textAlign: 'left' }))"
>
<i class="fa-solid fa-align-left"></i>
</button>

<button
@click="editor.chain().focus().setTextAlign('center').run()"
:class="btnClass(editor.isActive({ textAlign: 'center' }))"
>
<i class="fa-solid fa-align-center"></i>
</button>

<button
@click="editor.chain().focus().setTextAlign('right').run()"
:class="btnClass(editor.isActive({ textAlign: 'right' }))"
>
<i class="fa-solid fa-align-right"></i>
</button>

<button
@click="editor.chain().focus().setTextAlign('justify').run()"
:class="btnClass(editor.isActive({ textAlign: 'justify' }))"
>
<i class="fa-solid fa-align-justify"></i>
</button>

<span
  v-if="uploading"
  class="text-xs text-gray-500 ml-2"
>
Uploading...
</span>

</div>

<!-- EDITOR -->

<div
  class="tiptap-container bg-white dark:bg-gray-900"
  @paste="handlePaste"
  @drop="handleDrop"
  @dragover.prevent
>

<EditorContent
  :editor="editor"
  :style="{ minHeight: height }"
/>

</div>

<!-- FOOTER -->

<div
  v-if="editor"
  class="flex justify-end text-xs text-gray-400 px-3 py-1 border-t"
>

{{ editor.storage.characterCount.characters() }} /
{{ maxCharacters }}

</div>

</div>

</template>

<style scoped>

.html-editor-wrapper {
  @apply shadow-sm transition-all;
}

.btn-toolbar-tool {
  @apply px-2 py-1 text-sm rounded border border-transparent
         text-gray-600 dark:text-gray-400
         hover:bg-gray-200 dark:hover:bg-gray-700
         transition;
}

:deep(.tiptap) {
  @apply p-4 outline-none text-gray-800 dark:text-gray-200 leading-relaxed;
}

:deep(.tiptap img) {
  @apply max-w-full rounded-lg mx-auto my-4;
}

:deep(.tiptap h1) {
  @apply text-2xl font-bold mb-4;
}

:deep(.tiptap h2) {
  @apply text-xl font-bold mb-3;
}

:deep(.tiptap ul) {
  @apply list-disc ml-5;
}

:deep(.resize-image) {
  position: relative;
  display: inline-block;
}

:deep(.resize-image img) {
  max-width: 100%;
  height: auto;
}

:deep(.resize-handle) {
  width: 10px;
  height: 10px;
  background: #3b82f6;
  border-radius: 50%;
  position: absolute;
}

</style>