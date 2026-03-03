<template>
    <div class="relative" @click="toggleDropdown" v-click-outside="handleClickOutside">
        <div
            class="flex items-center cursor-pointer dark:bg-dark-900 shadow-theme-xs focus:border-brand-00 focus:shadow-focus-ring focus:ring-0 focus:ring-brand-500/20 focus:outline-none dark:focus:border-brand-800 dark:focus:ring-brand-800/30 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-gray-800 placeholder:text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
            :class="[
                additionalClasses,
                { '': !disabled && !readonly },
                { 'opacity-70 cursor-not-allowed bg-zinc-100 dark:bg-zinc-800': disabled || readonly },
                { '': isOpen }
            ]"
            tabindex="0"
            @keydown.down.prevent="navigateOptions('down')"
            @keydown.up.prevent="navigateOptions('up')"
            @keydown.enter.prevent="selectHighlighted"
            @keydown.space.prevent="toggleDropdown"
            @blur="handleBlurInternal"
            role="combobox"
            :aria-expanded="isOpen"
            :aria-haspopup="true"
            :aria-labelledby="id ? `${id}-label` : undefined"
        >
            <input
                v-if="searchable"
                type="text"
                ref="searchInputRef"
                v-model="searchText"
                @input="filteredOptions"
                @focus="openDropdown"
                @click.stop="openDropdown"
                :placeholder="selectedDisplayValue || placeholder || 'Type to search ...'"
                class="flex-grow bg-transparent cursor-pointer focus:outline-none text-zinc-800 dark:text-white"
                :disabled="disabled || readonly"
                :aria-autocomplete="searchable ? 'list' : 'none'"
                :aria-controls="isOpen ? `${id}-listbox` : undefined"
                :aria-activedescendant="highlightedOptionId"
            />
            <div
                v-else
                class="flex-grow overflow-hidden whitespace-nowrap text-ellipsis"
                :class="{
                    'text-gray-400 dark:text-gray-500': !selectedDisplayValue && !placeholder
                }"
            >
                {{ selectedDisplayValue || placeholder || "Select One" }}
            </div>

            <svg
                class="w-4 h-4 ml-2 text-zinc-500 dark:text-zinc-300 transition-transform"
                :class="{ 'rotate-180': isOpen }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>

        <Transition name="slide-fade">
            <ul
                v-if="isOpen"
                :id="`${id}-listbox`"
                class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-900 shadow-lg rounded-md border border-zinc-200 dark:border-zinc-600 max-h-60 overflow-y-auto ring-1 ring-black ring-opacity-5 focus:outline-none"
                role="listbox"
                :aria-multiselectable="multiple"
            >
                <!-- <li value="" class="text-zinc-500 dark:text-zinc-400 text-sm py-2 px-3" disabled>Select One</li> -->
                <li v-if="isLoading" class="px-3 py-2 text-zinc-500 dark:text-zinc-400">
                    <div class="flex items-center justify-center gap-2 rounded-lg h-20 font-medium text-gray-700 shadow-theme-xs hover:text-gray-800 dark:text-gray-400">
                        <span class="text-gray-200 animate-spin stroke-brand-500 dark:text-gray-800">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="8.75" stroke="currentColor" stroke-width="2.5"></circle><mask id="path-2-inside-1_3755_26477" fill="white"><path d="M18.2372 12.9506C18.8873 13.1835 19.6113 12.846 19.7613 12.1719C20.0138 11.0369 20.0672 9.86319 19.9156 8.70384C19.7099 7.12996 19.1325 5.62766 18.2311 4.32117C17.3297 3.01467 16.1303 1.94151 14.7319 1.19042C13.7019 0.637155 12.5858 0.270357 11.435 0.103491C10.7516 0.00440265 10.179 0.561473 10.1659 1.25187C10.1528 1.94226 10.7059 2.50202 11.3845 2.6295C12.1384 2.77112 12.8686 3.02803 13.5487 3.39333C14.5973 3.95661 15.4968 4.76141 16.1728 5.74121C16.8488 6.721 17.2819 7.84764 17.4361 9.02796C17.5362 9.79345 17.5172 10.5673 17.3819 11.3223C17.2602 12.002 17.5871 12.7178 18.2372 12.9506Z"></path></mask><path d="M18.2372 12.9506C18.8873 13.1835 19.6113 12.846 19.7613 12.1719C20.0138 11.0369 20.0672 9.86319 19.9156 8.70384C19.7099 7.12996 19.1325 5.62766 18.2311 4.32117C17.3297 3.01467 16.1303 1.94151 14.7319 1.19042C13.7019 0.637155 12.5858 0.270357 11.435 0.103491C10.7516 0.00440265 10.179 0.561473 10.1659 1.25187C10.1528 1.94226 10.7059 2.50202 11.3845 2.6295C12.1384 2.77112 12.8686 3.02803 13.5487 3.39333C14.5973 3.95661 15.4968 4.76141 16.1728 5.74121C16.8488 6.721 17.2819 7.84764 17.4361 9.02796C17.5362 9.79345 17.5172 10.5673 17.3819 11.3223C17.2602 12.002 17.5871 12.7178 18.2372 12.9506Z" stroke="currentStroke" stroke-width="4" mask="url(#path-2-inside-1_3755_26477)"></path>
                            </svg>
                        </span>
                        Loading...
                    </div>
                </li>
                <li v-if="filteredOptions.length === 0" class="px-3 py-2 text-zinc-500 dark:text-zinc-400">
                    No options found.
                </li>
                <li
                    v-for="(option, index) in filteredOptions"
                    :key="option.value || option.id || option"
                    :id="`${id}-option-${index}`"
                    class="px-3 py-2 cursor-pointer hover:bg-brand-500 hover:text-white dark:hover:bg-brand-700 text-gray-800 dark:text-white"
                    :class="{
                        'bg-brand-500 dark:bg-brand-700 text-white': isOptionSelected(option),
                        'bg-brand-500 dark:bg-brand-700 text-white': index === highlightedIndex, // Highlighted with keyboard
                    }"
                    @click.stop="selectOption(option)"
                    @mouseover="highlightedIndex = index"
                    role="option"
                    :aria-selected="isOptionSelected(option)"
                >
                    {{ option.text || option.name || option }}
                </li>
            </ul>
        </Transition>
    </div>
</template>

<script setup>
    import { ref, computed, watch, nextTick } from 'vue';

    const props = defineProps({
    // ID unik untuk elemen select
    id: {
        type: String,
        required: true,
    },
    // Nama field untuk form submission (penting untuk VeeValidate)
    name: {
        type: String,
        required: true,
    },
    // Nilai v-model. String/Number untuk single, Array untuk multiple.
    modelValue: {
        type: [String, Number, Array, Object],
        default: undefined,
    },
    // Daftar pilihan select.
    // Format: [{ value: 'id1', text: 'Option 1' }, { value: 'id2', text: 'Option 2' }, 'id3', ...]
    options: {
        type: Array,
        required: true,
    },
    // Placeholder teks saat tidak ada yang dipilih
    placeholder: {
        type: String,
        default: 'Pilih...',
    },
    // Aktifkan mode multiple selection
    multiple: {
        type: Boolean,
        default: false,
    },
    // Aktifkan fitur search/filter pada opsi
    searchable: {
        type: Boolean,
        default: false,
    },
    // Status disabled
    disabled: {
        type: Boolean,
        default: false,
    },
    // Status readonly
    readonly: {
        type: Boolean,
        default: false,
    },
    // Kelas CSS tambahan untuk elemen pembungkus (div utama)
    additionalClasses: {
        type: [String, Array, Object],
        default: '',
    },
    // Prop yang diteruskan oleh FormField untuk penanganan nilai VeeValidate
    handleChange: {
        type: Function,
        default: undefined,
    },
    // Prop yang diteruskan oleh FormField untuk penanganan blur VeeValidate
    handleBlur: {
        type: Function,
        default: undefined,
    },
    // Status loading (untuk menampilkan indikator loading)
    isLoading: {
        type: Boolean,
        default: false,
    },
    });

    const emit = defineEmits(['update:modelValue', 'change', 'blur']);

    const isOpen = ref(false);
    const searchText = ref('');
    const highlightedIndex = ref(-1); // Index dari opsi yang di-highlight untuk navigasi keyboard
    const searchInputRef = ref(null); // Ref untuk elemen input search

    // Computed property untuk opsi yang difilter saat mode searchable aktif
    const filteredOptions = computed(() => {
    if (!props.searchable || !searchText.value) {
        return props.options;
    }
    const searchTerm = searchText.value.toLowerCase();
    return props.options.filter(option => {
        const text = (option.text || option.name || option).toLowerCase();
        return text.includes(searchTerm);
    });
    });

    // Computed property untuk menampilkan nilai yang dipilih
    const selectedDisplayValue = computed(() => {
        // debugger;
        if (props.multiple) {
            if (!Array.isArray(props.modelValue) || props.modelValue.length === 0) {
                return '';
            }
            const selectedTexts = props.modelValue.map(selectedValue => {
                const option = props.options.find(
                    opt => (opt.value || opt.id || opt) === selectedValue
                );
                return option ? (option.text || option.name || option) : selectedValue;
            });
            return selectedTexts.join(', ');
        } else {
            if (props.modelValue === undefined || props.modelValue === null || props.modelValue === '') {
                return '';
            }
            const option = props.options.find(
                opt => (opt.value || opt.id || opt) === props.modelValue
            );
            return option ? (option.text || option.name || option) : props.modelValue;
        }
    });

    // Computed property untuk ID opsi yang di-highlight (untuk aria-activedescendant)
    const highlightedOptionId = computed(() => {
    if (highlightedIndex.value !== -1 && filteredOptions.value[highlightedIndex.value]) {
        const option = filteredOptions.value[highlightedIndex.value];
        return `${props.id}-option-${filteredOptions.value.indexOf(option)}`;
    }
    return undefined;
    });

    // Cek apakah opsi saat ini terpilih
    const isOptionSelected = computed(() => (option) => {
        const optionValue = option.value || option.id || option;
        if (props.multiple) {
            return Array.isArray(props.modelValue) && props.modelValue.includes(optionValue);
        } else {
            return props.modelValue === optionValue;
        }
    });

    // Membuka atau menutup dropdown
    const toggleDropdown = () => {
    if (props.disabled || props.readonly) return;
    isOpen.value = !isOpen.value;
    if (isOpen.value && props.searchable) {
        nextTick(() => {
        searchInputRef.value?.focus();
        });
    }
    if (!isOpen.value) {
        // Clear search text when closing
        searchText.value = '';
        highlightedIndex.value = -1;
    } else {
        // Highlight currently selected option when opening
        if (!props.multiple && props.modelValue !== undefined) {
            const currentIndex = filteredOptions.value.findIndex(opt => (opt.value || opt.id || opt) === props.modelValue);
            if (currentIndex !== -1) {
                highlightedIndex.value = currentIndex;
            }
        }
    }
    };

    // Membuka dropdown
    const openDropdown = () => {
        if (props.disabled || props.readonly) return;
        isOpen.value = true;
        if (props.searchable) {
            nextTick(() => searchInputRef.value?.focus());
        }
        // Highlight selected when opening
        if (!props.multiple && props.modelValue !== undefined) {
            const currentIndex = filteredOptions.value.findIndex(opt => (opt.value || opt.id || opt) === props.modelValue);
            if (currentIndex !== -1) {
                highlightedIndex.value = currentIndex;
            }
        }
    };

    // Menutup dropdown
    const closeDropdown = () => {
        isOpen.value = false;
        searchText.value = ''; // Clear search text on close
        highlightedIndex.value = -1; // Reset highlighted index
    };

    // Handle klik di luar komponen
    const handleClickOutside = () => {
    if (isOpen.value) {
        closeDropdown();
    }
    };

    // Memilih opsi dari daftar
    const selectOption = (option) => {
    const optionValue = option.value || option.id || option;
    let newValue;

    if (props.multiple) {
        let currentValues = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
        if (currentValues.includes(optionValue)) {
        newValue = currentValues.filter(val => val !== optionValue); // Hapus jika sudah ada
        } else {
        newValue = [...currentValues, optionValue]; // Tambahkan jika belum ada
        }
    } else {
        newValue = optionValue;
        closeDropdown(); // Tutup dropdown setelah memilih untuk single select
    }

    // Panggil handleChange dari FormField jika ada, atau emit update:modelValue secara mandiri
    if (props.handleChange) {
        props.handleChange(newValue);
    } else {
        emit('update:modelValue', newValue);
    }
    emit('change', newValue); // Emit event change juga
    // Fokuskan kembali ke input setelah memilih opsi (untuk single select)
    if (!props.multiple && searchInputRef.value) {
        searchInputRef.value.blur(); // Trigger blur event for VeeValidate
    }
    };

    // Navigasi opsi dengan keyboard
    const navigateOptions = (direction) => {
    if (!isOpen.value) {
        openDropdown();
        return;
    }

    let newIndex = highlightedIndex.value;
    if (direction === 'down') {
        newIndex = (newIndex + 1) % filteredOptions.value.length;
    } else if (direction === 'up') {
        newIndex = (newIndex - 1 + filteredOptions.value.length) % filteredOptions.value.length;
    }
    highlightedIndex.value = newIndex;

    // Scroll into view
    nextTick(() => {
        const highlightedElement = document.getElementById(`${props.id}-option-${highlightedIndex.value}`);
        if (highlightedElement) {
        highlightedElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });
    };

    // Memilih opsi yang di-highlight dengan Enter
    const selectHighlighted = () => {
    if (isOpen.value && highlightedIndex.value !== -1) {
        selectOption(filteredOptions.value[highlightedIndex.value]);
    } else {
        toggleDropdown(); // Toggle if no option highlighted or closed
    }
    };

    // Handler internal untuk blur yang akan memanggil handleBlur dari FormField jika ada
    const handleBlurInternal = (event) => {
    // Jika blur terjadi karena mengklik dropdown, jangan trigger VeeValidate blur
    // Atau jika blur terjadi karena pindah fokus ke dalam dropdown (misal, search input)
    // Periksa apakah fokus masih di dalam komponen select ini
    const isInsideSelect = event.relatedTarget && event.currentTarget.contains(event.relatedTarget);
    if (!isInsideSelect && !isOpen.value) { // Hanya trigger jika bukan karena klik internal dan dropdown tertutup
        if (props.handleBlur) {
        props.handleBlur(props.modelValue);
        }
        emit('blur', props.modelValue);
    }
    };

    // Watcher untuk mereset highlightedIndex saat opsi difilter (search text berubah)
    watch(searchText, () => {
    highlightedIndex.value = -1;
    // Jika search text tidak kosong dan ada hasil, highlight yang pertama
    if (searchText.value && filteredOptions.value.length > 0) {
        highlightedIndex.value = 0;
    }
    });

    // Watcher untuk memastikan dropdown tertutup saat disabled/readonly berubah
    watch([() => props.disabled, () => props.readonly], ([newDisabled, newReadonly]) => {
    if (newDisabled || newReadonly) {
        closeDropdown();
    }
    });

    // Custom directive untuk click-outside
    const vClickOutside = {
    beforeMount(el, binding) {
        el.clickOutsideEvent = (event) => {
        if (!(el === event.target || el.contains(event.target))) {
            binding.value(event);
        }
        };
        document.addEventListener('click', el.clickOutsideEvent);
    },
    unmounted(el) {
        document.removeEventListener('click', el.clickOutsideEvent);
    },
    };
</script>

<style scoped>
/* Anda bisa menambahkan gaya kustom di sini */
/* Warna primer (primary) dan sekundernya (secondary) harus didefinisikan di konfigurasi Tailwind Anda */
.hover\:bg-primary-light {
  background-color: theme('colors.primary.100', '#ebf5ff'); /* Contoh warna ringan untuk hover */
}
.dark\:hover\:bg-primary-dark {
  background-color: theme('colors.primary.900', '#1a202c'); /* Contoh warna gelap untuk hover */
}
.text-primary-text {
  color: theme('colors.primary.800', '#2a4365'); /* Contoh warna teks untuk opsi terpilih */
}
.dark\:text-primary-text-dark {
  color: theme('colors.primary.200', '#e2e8f0'); /* Contoh warna teks gelap untuk opsi terpilih */
}
.bg-primary-light {
  background-color: theme('colors.primary.200', '#c6f6d5');
}
.dark\:bg-primary-dark {
  background-color: theme('colors.primary.600', '#2c5282');
}

/* Transisi untuk dropdown */
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease-out;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}
</style>
