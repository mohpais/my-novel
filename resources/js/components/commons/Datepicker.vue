<template>
    <div class="relative" @click="handleDatepickerClick" v-click-outside="closeDropdownOnOutsideClick" :data-id="id" ref="rootComponentRef">
        <div
            class="flex items-center justify-between min-h-[38px] px-2.5 py-3 border rounded-md shadow-sm bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer"
            :class="[
                additionalClasses,
                { 'focus:outline-none focus:ring-primary focus:border-primary': !disabled && !readonly },
                { 'opacity-70 cursor-not-allowed bg-gray-100 dark:bg-gray-800': disabled || readonly },
                { 'border-primary ring-1 ring-primary': isOpen }
            ]"
            tabindex="0"
            @keydown.enter.prevent="toggleDropdown"
            @keydown.space.prevent="toggleDropdown"
            @blur="handleBlurInternal"
            role="combobox"
            :aria-expanded="isOpen"
            :aria-haspopup="true"
            :aria-labelledby="id ? `${id}-label` : undefined"
        >
            <svg
                class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-300 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <input
                type="text"
                :value="formattedDate"
                :placeholder="placeholder"
                class="flex-grow bg-transparent border-none !ring-0 focus:ring-0 focus:outline-none focus:border-none outline-none text-gray-800 dark:text-white cursor-pointer"
                :disabled="disabled || readonly"
                readonly
                :aria-activedescendant="highlightedDayId"
                @focus="openDropdown"
                @click.stop="openDropdown"
            />
        </div>

        <Transition name="slide-fade">
            <div
                v-if="isOpen"
                class="absolute z-10 mt-0 p-3 bg-white dark:bg-gray-700 shadow-lg rounded-md border border-gray-200 dark:border-gray-600 ring-1 ring-black ring-opacity-5 w-72 min-h-80"
            >
                <Transition name="slide-down-fast">
                    <div v-if="viewMode === 'month'" class="absolute inset-0 bg-white dark:bg-gray-700 flex flex-col gap-2 items-center rounded-md py-3 px-1 z-10">
                        <div class="grid grid-cols-3 grow gap-1 w-full">
                            <button
                                v-for="(month, index) in months" :key="month" :value="index"
                                class="px-2 font-medium rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300 text-base h-12"
                                :class="{ 'bg-gray-100 dark:bg-gray-600': currentMonth === index }"
                                @click.stop="selectMonth(index)"
                            >
                                {{ month.slice(0, 3) }}
                            </button>
                        </div>
                        <button
                            @click.stop="setViewMode('calendar')"
                            class="p-2 rounded-md flex items-center justify-center w-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                        >
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </Transition>

                <Transition name="slide-down-fast">
                    <div v-if="viewMode === 'year'" class="absolute inset-0 bg-white dark:bg-gray-700 flex flex-col gap-1 items-center rounded-md py-2 px-1 z-10">
                        <div ref="yearListRef" class="grid grid-cols-3 grow gap-2 w-full h-64 overflow-y-auto scroll-thin">
                            <button
                                v-for="year in yearOptions"
                                :key="year"
                                class="font-medium rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300 px-3 py-2 text-base"
                                :class="{ 'bg-gray-100 dark:bg-gray-600': currentYear === year }"
                                @mouseover="highlightedIndex = year - yearOptions[0]"
                                @click.stop="selectYear(year)"
                            >
                                {{ year }}
                            </button>
                        </div>
                        <button
                            @click.stop="setViewMode('calendar')"
                            class="p-2 rounded-md flex items-center justify-center w-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                        >
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </Transition>
                
                <Transition name="slide-down-fast">
                    <div v-if="viewMode === 'hours'" class="absolute inset-0 bg-white dark:bg-gray-700 flex flex-col gap-2 items-center rounded-md py-3 px-1 z-10">
                        <div class="grid grid-cols-4 grow gap-1 w-full overflow-y-auto scroll-thin">
                            <button
                                v-for="h in 24" :key="`hour-${h-1}`"
                                class="px-2 font-medium rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300 text-base h-12"
                                :class="{ 'bg-gray-100 dark:bg-gray-600': hour === (h-1) }"
                                @click.stop="selectHourValue(h-1)"
                            >
                                {{ String(h-1).padStart(2, '0') }}
                            </button>
                        </div>
                        <button
                            @click.stop="setViewMode('time')"
                            class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                        >
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </button>
                    </div>
                </Transition>

                <Transition name="slide-down-fast">
                    <div v-if="viewMode === 'minutes'" class="absolute inset-0 bg-white dark:bg-gray-700 flex flex-col gap-2 items-center rounded-md py-3 px-1 z-10">
                        <div class="grid grid-cols-4 grow gap-1 w-full overflow-y-auto scroll-thin">
                            <button
                                v-for="m in minuteOptions" :key="`minute-${m}`"
                                class="px-2 font-medium rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300 text-base h-12"
                                :class="{ 'bg-gray-100 dark:bg-gray-600': minute === m }"
                                @click.stop="selectMinuteValue(m)"
                            >
                                {{ String(m).padStart(2, '0') }}
                            </button>
                        </div>
                        <button
                            @click.stop="setViewMode('time')"
                            class="p-2 rounded-md flex items-center justify-center w-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                        >
                            
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </button>
                    </div>
                </Transition>
                
                <Transition name="slide-down-fast">
                    <div v-if="viewMode === 'time'" class="absolute inset-0 time-picker bg-white dark:bg-gray-700 flex flex-col gap-1 items-center rounded-md py-2 px-1 z-10">
                        <div class="flex items-center h-64">
                            <div class="grid grid-flow-col grid-rows-4 gap-4 w-full h-24">
                                <div class="col-span-2 flex items-center justify-center">
                                    <button @click.stop="incrementHour" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 15 7-7 7 7"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="col-span-2 row-span-2 flex items-center justify-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors py-2 px-1 rounded-sm"
                                    @click.stop="setViewMode('hours')">
                                    <span class="text-gray-600 dark:text-gray-300 text-2xl font-semibold">
                                        {{ String(hour).padStart(2, '0') }}
                                    </span>
                                </div>
                                <div class="col-span-2 flex items-center justify-center">
                                    <button @click.stop="decrementHour" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="row-span-4 flex items-center justify-center w-6">:</div>
                                <div class="col-span-2 flex items-center justify-center">
                                    <button @click.stop="incrementMinute" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 15 7-7 7 7"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="col-span-2 row-span-2 flex items-center justify-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors py-2 px-1 rounded-sm"
                                    @click.stop="setViewMode('minutes')">
                                    <span class="text-gray-600 dark:text-gray-300 text-2xl font-semibold">
                                        {{ String(minute).padStart(2, '0') }}
                                    </span>
                                </div>
                                <div class="col-span-2 flex items-center justify-center">
                                    <button @click.stop="decrementMinute" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button
                            @click.stop="setViewMode('calendar')"
                            class="p-2 rounded-md flex items-center justify-center w-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                        >
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </Transition>
    
                <div v-if="viewMode === 'calendar'">
                    <div class="flex items-center justify-between mb-1">
                        <button
                            @click.stop="previousMonth"
                            class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            :disabled="isPreviousMonthDisabled"
                        >
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>

                        <div class="flex items-center gap-2">
                            <button @click.stop="setViewMode('month')" class="p-2 rounded-md w-20 text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors flex items-center justify-center text-gray-600 dark:text-gray-300">
                                {{ months[currentMonth].slice(0, 3) }}
                            </button>
                            <button @click.stop="setViewMode('year')" class="p-2 rounded-md w-20 text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors flex items-center justify-center text-gray-600 dark:text-gray-300">
                                {{ currentYear }}
                            </button>
                        </div>

                        <button
                            @click.stop="nextMonth"
                            class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            :disabled="isNextMonthDisabled"
                        >
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-7 text-center text-sm font-medium text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-600 pb-2 mb-2">
                        <div v-for="day in weekdays" :key="day">{{ day }}</div>
                    </div>

                    <div class="grid grid-cols-7 gap-1">
                        <div
                            v-for="(day, index) in calendarDays"
                            :key="day.dateString"
                            :id="`${id}-day-${index}`"
                            class="h-8 w-8 flex items-center justify-center text-sm rounded-full cursor-pointer transition-colors"
                            :class="{
                                'text-gray-400 dark:text-gray-500 pointer-events-none': day.isDisabled,
                                'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-50 font-semibold': isSelectedDay(day.date) && day.isCurrentMonth,
                                'hover:bg-gray-100 dark:hover:bg-gray-600': !day.isDisabled && !isSelectedDay(day.date),
                                'text-gray-800 dark:text-white': !day.isDisabled && !isSelectedDay(day.date) && day.isCurrentMonth,
                                'text-gray-500 dark:text-gray-400 opacity-60': !day.isDisabled && !day.isCurrentMonth,
                                'border border-gray-200 dark:border-gray-500': isToday(day.date) && !isSelectedDay(day.date),
                                'focus:ring-1 focus:ring-primary-lighter': highlightedIndex === index
                            }"
                            @click.stop="selectDay(day.date)"
                            @mouseover="highlightedIndex = index"
                            @keydown.self.stop.enter="selectDay(day.date)"
                            @keydown.self.stop.space="selectDay(day.date)"
                            role="gridcell"
                            :aria-selected="isSelectedDay(day.date)"
                            :aria-disabled="day.isDisabled"
                            :tabindex="day.isDisabled ? -1 : 0"
                        >
                            {{ day.date.getDate() }}
                        </div>
                    </div>

                    <div v-if="showTime" class="flex justify-center items-center w-full mt-2">
                        <button
                            @click.stop="setViewMode('time')" 
                            class="w-full py-2 text-center bg-transparent rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                        >
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="w-full flex justify-between items-center py-2 mt-1 border-t border-gray-200 dark:border-gray-600 pt-1">
                        <div class="text-left text-gray-700 dark:text-gray-200">{{ formattedDate }}</div>
                        <div class="flex gap-1">
                            <button
                                @click.stop="closeDropdown"
                                class="px-2 py-1 text-sm text-gray-700 dark:text-gray-300 bg-transparent rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
                            >
                                close
                            </button>
                            <button
                                @click.stop="handleSelectButtonClick"
                                class="px-2 py-1 text-sm text-white bg-primary dark:bg-primary-dark rounded-md hover:bg-primary-light dark:hover:bg-primary-light transition-colors"
                            >
                                select
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
    import { ref, computed, defineEmits, watch, nextTick } from 'vue';

    const props = defineProps({
        id: { type: String, required: true },
        name: { type: String, required: true },
        modelValue: { type: String, default: '' },
        placeholder: { type: String, default: 'Pilih Tanggal' },
        disabled: { type: Boolean, default: false },
        readonly: { type: Boolean, default: false },
        additionalClasses: { type: [String, Array, Object], default: '' },
        handleChange: { type: Function, default: undefined }, // For VeeValidate
        handleBlur: { type: Function, default: undefined }, // For VeeValidate
        minDate: { type: String, default: '' },
        maxDate: { type: String, default: '' },
        displayFormat: { type: String, default: 'YYYY-MM-DD HH:mm' },
        yearRange: { type: Number, default: 10 },
        showTime: { type: Boolean, default: true },
    });

    const emit = defineEmits(['update:modelValue', 'change', 'blur']);

    const isOpen = ref(false);
    const viewMode = ref('calendar'); // 'calendar', 'month', 'year', 'time', 'hours', 'minutes'

    const internalSelectedDate = ref(null);
    const currentMonth = ref(new Date().getMonth());
    const currentYear = ref(new Date().getFullYear());
    const highlightedIndex = ref(-1);

    const yearListRef = ref(null);
    const rootComponentRef = ref(null); // Deklarasi ref untuk elemen root komponen

    // --- Constants ---
    const weekdays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // --- Time Picker State and Methods (Moved to its own section for clarity) ---
    const hour = ref(0);
    const minute = ref(0);

    // --- Computed Properties ---
    const yearOptions = computed(() => {
        const current = new Date().getFullYear();
        const startYear = current - props.yearRange;
        const endYear = current + props.yearRange;
        const years = [];
        for (let i = startYear; i <= endYear; i++) {
            years.push(i);
        }
        return years;
    });

    const minuteOptions = computed(() => {
        const minutes = [];
        for (let i = 0; i < 60; i += 5) {
            minutes.push(i);
        }
        return minutes;
    });

    const formattedDate = computed(() => {
        if (!internalSelectedDate.value) return '';
        return formatDate(internalSelectedDate.value, props.displayFormat);
    });

    const minAllowedDate = computed(() => parseDate(props.minDate));
    const maxAllowedDate = computed(() => parseDate(props.maxDate));

    const calendarDays = computed(() => {
        const days = [];
        const firstDayOfMonth = new Date(currentYear.value, currentMonth.value, 1);
        const startWeekday = firstDayOfMonth.getDay(); // 0 for Sunday, 1 for Monday, etc.

        // Days from previous month
        const prevMonthLastDay = new Date(currentYear.value, currentMonth.value, 0);
        for (let i = startWeekday; i > 0; i--) {
            const date = new Date(prevMonthLastDay.getFullYear(), prevMonthLastDay.getMonth(), prevMonthLastDay.getDate() - i + 1);
            const isDisabled = (minAllowedDate.value && date < minAllowedDate.value) ||
                             (maxAllowedDate.value && date > maxAllowedDate.value);
            days.push({
                date: date,
                dateString: formatDate(date, 'YYYY-MM-DD'),
                isDisabled: isDisabled,
                isCurrentMonth: false,
            });
        }

        // Days of current month
        let date = new Date(currentYear.value, currentMonth.value, 1);
        while (date.getMonth() === currentMonth.value) {
            const tempDate = new Date(date);
            const isDisabled = (minAllowedDate.value && tempDate < minAllowedDate.value) ||
                             (maxAllowedDate.value && tempDate > maxAllowedDate.value);
            days.push({
                date: tempDate,
                dateString: formatDate(tempDate, 'YYYY-MM-DD'),
                isDisabled: isDisabled,
                isCurrentMonth: true,
            });
            date.setDate(date.getDate() + 1);
        }

        // Days from next month
        const totalCells = 42; // Max number of cells for 6 weeks (7 days * 6 rows)
        let dayCounter = 1;
        while (days.length < totalCells) {
            const date = new Date(currentYear.value, currentMonth.value + 1, dayCounter);
            const isDisabled = (minAllowedDate.value && date < minAllowedDate.value) ||
                             (maxAllowedDate.value && date > maxAllowedDate.value);
            days.push({
                date: date,
                dateString: formatDate(date, 'YYYY-MM-DD'),
                isDisabled: isDisabled,
                isCurrentMonth: false,
            });
            dayCounter++;
        }
        return days;
    });

    const isPreviousMonthDisabled = computed(() => {
        if (!minAllowedDate.value) return false;
        const previousMonthFirstDay = new Date(currentYear.value, currentMonth.value - 1, 1);
        return previousMonthFirstDay < new Date(minAllowedDate.value.getFullYear(), minAllowedDate.value.getMonth(), 1);
    });

    const isNextMonthDisabled = computed(() => {
        if (!maxAllowedDate.value) return false;
        const nextMonthFirstDay = new Date(currentYear.value, currentMonth.value + 1, 1);
        return nextMonthFirstDay > new Date(maxAllowedDate.value.getFullYear(), maxAllowedDate.value.getMonth(), 1);
    });

    const highlightedDayId = computed(() => {
        if (highlightedIndex.value === -1) return undefined;
        return `${props.id}-day-${highlightedIndex.value}`;
    });

    // --- Watchers ---
    watch(() => props.modelValue, (newVal) => {
        if (newVal) {
            const date = new Date(newVal);
            if (!isNaN(date.getTime())) {
                internalSelectedDate.value = date;
                currentMonth.value = date.getMonth();
                currentYear.value = date.getFullYear();
            } else {
                // If modelValue is provided but invalid, set to current date as default
                const now = new Date();
                internalSelectedDate.value = now;
                currentMonth.value = now.getMonth();
                currentYear.value = now.getFullYear();
                hour.value = now.getHours(); // Also set current time
                minute.value = now.getMinutes();
            }
        } else {
            // If modelValue is empty/null, set to current date
            const now = new Date();
            internalSelectedDate.value = now;
            currentMonth.value = now.getMonth();
            currentYear.value = now.getFullYear();
            hour.value = now.getHours(); // Also set current time
            minute.value = now.getMinutes();
        }
    }, { immediate: true });

    watch(internalSelectedDate, (date) => {
        if (date) {
            hour.value = date.getHours();
            minute.value = date.getMinutes();
        }
    });

    watch(viewMode, (newMode) => {
        if (newMode === 'year') {
            nextTick(() => {
                const container = yearListRef.value;
                if (!container) return;
                const activeBtn = container.querySelector(
                    'button.bg-gray-100, button.dark\\:bg-gray-600'
                );
                if (activeBtn) {
                    const offset = activeBtn.offsetTop - container.clientHeight / 2 + activeBtn.clientHeight / 2;
                    container.scrollTo({ top: offset, behavior: 'auto' });
                }
            });
        }
    });

    watch([() => props.disabled, () => props.readonly], ([newDisabled, newReadonly]) => {
        if (newDisabled || newReadonly) {
            closeDropdown();
        }
    });

    const incrementHour = () => { hour.value = (hour.value + 1) % 24; updateTime(); };
    const decrementHour = () => { hour.value = (hour.value + 23) % 24; updateTime(); };
    const incrementMinute = () => { minute.value = (minute.value + 1) % 60; updateTime(); };
    const decrementMinute = () => { minute.value = (minute.value + 59) % 60; updateTime(); };

    const updateTime = () => {
        if (!internalSelectedDate.value) {
            // If no date is selected yet, create one based on current calendar view, then set time
            internalSelectedDate.value = new Date(currentYear.value, currentMonth.value, new Date().getDate()); // Default to current day in visible month
        }
        internalSelectedDate.value.setHours(hour.value, minute.value);
        emitValue(internalSelectedDate.value); // Keep emitting on time change
    };

    // --- New Selection Functions for Hours/Minutes ---
    const selectHourValue = (h) => {
        hour.value = h;
        updateTime(); // Update internalSelectedDate and emit
        setViewMode('time'); // Return to time view
    };

    const selectMinuteValue = (m) => {
        minute.value = m;
        updateTime(); // Update internalSelectedDate and emit
        setViewMode('time'); // Return to time view
    };

    // --- Utility Functions ---
    const formatDate = (date, format) => {
        if (!date || isNaN(date.getTime())) return '';
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const seconds = date.getSeconds().toString().padStart(2, '0'); // Add seconds

        let formatted = format.replace(/YYYY/g, year)
                              .replace(/MM/g, month)
                              .replace(/DD/g, day)
                              .replace(/HH/g, hours)
                              .replace(/mm/g, minutes)
                              .replace(/ss/g, seconds); // Add replacement for seconds
        
        if (!props.showTime) {
            formatted = formatted.split(' ')[0]; // Remove time part if not shown
        }
        return formatted;
    };

    const parseDate = (dateString) => {
        if (!dateString) return null;
        const date = new Date(dateString);
        return isNaN(date.getTime()) ? null : date;
    };

    const isSelectedDay = (date) => {
        return internalSelectedDate.value &&
               date.getDate() === internalSelectedDate.value.getDate() &&
               date.getMonth() === internalSelectedDate.value.getMonth() &&
               date.getFullYear() === internalSelectedDate.value.getFullYear();
    };

    const isToday = (date) => {
        const today = new Date();
        return date.getDate() === today.getDate() &&
               date.getMonth() === today.getMonth() &&
               date.getFullYear() === today.getFullYear();
    };

    const emitValue = (date) => {
        const dateToEmit = date ? new Date(date) : null; // Ensure valid date
        if (!dateToEmit || isNaN(dateToEmit.getTime())) {
            // If null/invalid date, emit empty string
            props.handleChange?.('') ?? emit('update:modelValue', '');
            emit('change', '');
            return;
        }

        const formattedDateString = formatDate(dateToEmit, 'YYYY-MM-DD HH:mm:ss'); // Always emit full date-time string internally for consistency
        props.handleChange?.(formattedDateString) ?? emit('update:modelValue', formattedDateString);
        emit('change', formattedDateString);
    };

    // --- Calendar Navigation ---
    const previousMonth = () => {
        if (currentMonth.value === 0) {
            currentMonth.value = 11;
            currentYear.value--;
        } else {
            currentMonth.value--;
        }
        highlightedIndex.value = -1;
    };

    const nextMonth = () => {
        if (currentMonth.value === 11) {
            currentMonth.value = 0;
            currentYear.value++;
        } else {
            currentMonth.value++;
        }
        highlightedIndex.value = -1;
    };

    // --- Dropdown and View Mode Control ---
    const setViewMode = (mode) => {
        viewMode.value = mode;
        if (mode === 'time' && !internalSelectedDate.value) {
            // Initialize internalSelectedDate with current date and time if it's null when entering time picker
            const now = new Date();
            internalSelectedDate.value = new Date(currentYear.value, currentMonth.value, now.getDate(), now.getHours(), now.getMinutes());
            hour.value = now.getHours();
            minute.value = now.getMinutes();
            emitValue(internalSelectedDate.value); // Emit this initial selection
        } else if (mode === 'time' && internalSelectedDate.value) {
            // Update hour and minute refs based on internalSelectedDate
            hour.value = internalSelectedDate.value.getHours();
            minute.value = internalSelectedDate.value.getMinutes();
        }
    };

    const handleDatepickerClick = () => {
        if (props.disabled || props.readonly) return;
        // Hanya membuka dropdown jika saat ini tertutup
        if (!isOpen.value) {
            openDropdown();
        }
        // Jika sudah terbuka, klik di dalam div utama tidak akan menutupnya
    };

    const openDropdown = () => {
        if (props.disabled || props.readonly) return;
        isOpen.value = true;
        viewMode.value = 'calendar'; // Always start with calendar view
        if (internalSelectedDate.value) {
            currentMonth.value = internalSelectedDate.value.getMonth();
            currentYear.value = internalSelectedDate.value.getFullYear();
            nextTick(() => {
                const selectedDayElement = document.querySelector('.bg-primary-light.dark\\:bg-primary-dark');
                if (selectedDayElement) {
                    selectedDayElement.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                }
            });
        }
    };

    const closeDropdown = () => {
        isOpen.value = false;
        viewMode.value = 'calendar'; // Reset view mode
        highlightedIndex.value = -1; // Reset highlight
    };

    // New function for handling outside clicks to control dropdown
    const closeDropdownOnOutsideClick = () => {
        // Ini dipanggil oleh directive v-click-outside HANYA jika klik memang di luar komponen.
        // Sekarang akan selalu menutup jika dropdown terbuka, terlepas dari viewMode.
        if (isOpen.value) { 
            closeDropdown();
        }
    };

    // --- Selection Handlers ---
    const selectMonth = (monthIndex) => {
        currentMonth.value = monthIndex;
        setViewMode('calendar'); // Go back to calendar view after month selection
        highlightedIndex.value = -1;
    };

    const selectYear = (year) => {
        currentYear.value = year;
        setViewMode('calendar'); // Go back to calendar view after year selection
        highlightedIndex.value = -1;
    };

    const selectDay = (date) => {
        if (props.disabled || props.readonly) return;

        const isPastMin = minAllowedDate.value && date < new Date(minAllowedDate.value.getFullYear(), minAllowedDate.value.getMonth(), minAllowedDate.value.getDate());
        const isAfterMax = maxAllowedDate.value && date > new Date(maxAllowedDate.value.getFullYear(), maxAllowedDate.value.getMonth(), maxAllowedDate.value.getDate());

        if (isPastMin || isAfterMax) {
            return;
        }

        // Preserve current time if a date was already selected, otherwise use 00:00
        const newDate = new Date(date);
        if (internalSelectedDate.value) {
            newDate.setHours(internalSelectedDate.value.getHours());
            newDate.setMinutes(internalSelectedDate.value.getMinutes());
            newDate.setSeconds(internalSelectedDate.value.getSeconds());
            newDate.setMilliseconds(internalSelectedDate.value.getMilliseconds());
        } else {
             // If no date was selected yet, initialize time to 00:00 (or current time if desired)
             newDate.setHours(hour.value, minute.value, 0, 0); // Use current hour/minute from time picker if set
        }

        internalSelectedDate.value = newDate;
        // Tidak lagi emit/close langsung saat pemilihan hari, hanya saat tombol 'select' diklik
    };

    // New function for the 'select' button
    const handleSelectButtonClick = () => {
        if (internalSelectedDate.value) {
            emitValue(internalSelectedDate.value);
        }
        closeDropdown(); // Close after 'select' button is clicked
    };

    // --- Event Handlers ---
    const handleBlurInternal = (e) => {
        // Capture the root element reference NOW, before setTimeout
        const currentRootElement = rootComponentRef.value; 

        // Delay the execution slightly to allow Vue to process other DOM updates or focus changes
        setTimeout(() => {
            const target = e.relatedTarget;
            
            // Check if the relatedTarget (the element gaining focus) is outside the entire datepicker component
            // Use the captured root element reference
            if (currentRootElement && !currentRootElement.contains(target) && isOpen.value) {
                closeDropdown();
                props.handleBlur?.(props.modelValue);
                emit('blur', props.modelValue);
            }
        }, 0); // Small delay
    };
    
    // --- Custom Directive ---
    const vClickOutside = {
        beforeMount(el, binding) {
            el.clickOutsideEvent = (event) => {
                // Check if the clicked element is outside 'el' (the datepicker component's root div)
                // AND it's not an element that is part of an active transition (e.g., year/month/time overlay fading in/out)
                const isClickInsideComponentRoot = el.contains(event.target);
                const isTransitionElement = event.target.closest(
                    '.slide-down-fast-enter-active, .slide-down-fast-leave-active, ' +
                    '.slide-fade-enter-active, .slide-fade-leave-active'
                );
                
                if (!isClickInsideComponentRoot && !isTransitionElement) {
                    // Call the function provided by the directive binding (closeDropdownOnOutsideClick)
                    // The function itself will decide whether to close.
                    if (typeof binding.value === 'function') {
                        binding.value(); 
                    }
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
    /* Your existing styles remain here */
    .hover\:bg-primary-light {
        background-color: theme('colors.primary.100', '#ebf5ff');
    }
    .dark\:hover\:bg-primary-dark {
        background-color: theme('colors.primary.900', '#1a202c');
    }
    .text-primary-text {
        color: theme('colors.primary.800', '#2a4365');
    }
    .dark\:text-primary-text-dark {
        color: theme('colors.primary.200', '#e2e8f0');
    }
    .bg-primary-light {
        background-color: theme('colors.primary.200', '#c6f6d5');
    }
    .dark\:bg-primary-dark {
        background-color: theme('colors.primary.600', '#2c5282');
    }
    .border-primary {
        border-color: theme('colors.primary.500', '#63b3ed');
    }
    .ring-primary {
        --tw-ring-color: theme('colors.primary.500', '#63b3ed');
    }
    .focus\:ring-primary-lighter {
        --tw-ring-color: theme('colors.primary.300', '#90cdf4');
    }

    /* Transitions */
    .slide-fade-enter-active,
    .slide-fade-leave-active {
        transition: all 0.1s ease-out;
    }

    .slide-fade-enter-from,
    .slide-fade-leave-to {
        transform: translateY(-10px);
        opacity: 0;
    }

    .slide-down-fast-enter-active,
    .slide-down-fast-leave-active {
        transition: transform 0.2s ease-out, opacity 0.2s ease-out;
    }

    .slide-down-fast-enter-from {
        transform: translateY(-10px);
        opacity: 0;
    }

    .slide-down-fast-enter-to {
        transform: translateY(0);
        opacity: 1;
    }

    .slide-down-fast-leave-from {
        transform: translateY(0);
        opacity: 1;
    }

    .slide-down-fast-leave-to {
        transform: translateY(-10px);
        opacity: 0;
    }
</style>