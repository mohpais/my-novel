<template>
    <svg
        aria-hidden="true"
        :class="iconClass"
        :fill="fillColor"
        :stroke="strokeColor"
        :viewBox="viewBox" xmlns="http://www.w3.org/2000/svg"
        :stroke-width="strokeWidth"
    >
        <template v-if="name === 'dashboard'">
            <path d="M2 3C2 2.44772 2.44772 2 3 2H8C8.55228 2 9 2.44772 9 3V8C9 8.55228 8.55228 9 8 9H3C2.44772 9 2 8.55228 2 8V3Z"/>
            <path d="M11 3C11 2.44772 11.4477 2 12 2H17C17.5523 2 18 2.44772 18 3V8C18 8.55228 17.5523 9 17 9H12C11.4477 9 11 8.55228 11 8V3Z"/>
            <path d="M11 12C11 11.4477 11.4477 11 12 11H17C17.5523 11 18 11.4477 18 12V17C18 17.5523 17.5523 18 17 18H12C11.4477 18 11 17.5523 11 17V12Z"/>
            <path d="M2 12C2 11.4477 2.44772 11 3 11H8C8.55228 11 9 11.4477 9 12V17C9 17.5523 8.55228 18 8 18H3C2.44772 18 2 17.5523 2 17V12Z"/>
        </template>
        <template v-if="name === 'overview'">
            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
        </template>
        <template v-if="name === 'calendar-week'">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M8 14h8m-4-7V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
        </template>
        <template v-if="name === 'arrow-up'">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13 4 4m-4-4-4 4"></path>
        </template>
        <template v-if="name === 'arrow-down'">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 14-4-4m4 4 4-4"></path>
        </template>
        <template v-if="name === 'outcome'">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4.5V19c0 .6.4 1 1 1h15M7 10l4 4 4-4 5 5m0 0h-3.2m3.2 0v-3.2"></path>
        </template>
        <template v-if="name === 'calendars'">
            <path fill-rule="evenodd" d="M6 5V4a1 1 0 1 1 2 0v1h3V4a1 1 0 1 1 2 0v1h3V4a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v2H3V7a2 2 0 0 1 2-2h1ZM3 19v-8h18v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm5-6a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2H8Z" clip-rule="evenodd"/>
        </template>
        <template v-if="name === 'building'">
            <path fill-rule="evenodd" d="M4 4a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2v14a1 1 0 1 1 0 2H5a1 1 0 1 1 0-2V5a1 1 0 0 1-1-1Zm5 2a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H9Zm5 0a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-1Zm-5 4a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1H9Zm5 0a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-1Zm-3 4a2 2 0 0 0-2 2v3h2v-3h2v3h2v-3a2 2 0 0 0-2-2h-2Z" clip-rule="evenodd"/>
        </template>
        <template v-else-if="name === 'pages'">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"></path>
        </template>
        <template v-else-if="name === 'dollar'">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17.3a5 5 0 0 0 2.6 1.7c2.2.6 4.5-.5 5-2.3.4-2-1.3-4-3.6-4.5-2.3-.6-4-2.7-3.5-4.5.5-1.9 2.7-3 5-2.3 1 .2 1.8.8 2.5 1.6m-3.9 12v2m0-18v2.2"></path>
        </template>
        <template v-else-if="name === 'users'">
            <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4c0 1.1.9 2 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.8-3.1a5.5 5.5 0 0 0-2.8-6.3c.6-.4 1.3-.6 2-.6a3.5 3.5 0 0 1 .8 6.9Zm2.2 7.1h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1l-.5.8c1.9 1 3.1 3 3.1 5.2ZM4 7.5a3.5 3.5 0 0 1 5.5-2.9A5.5 5.5 0 0 0 6.7 11 3.5 3.5 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4c0 1.1.9 2 2 2h.5a6 6 0 0 1 3-5.2l-.4-.8Z" clip-rule="evenodd"></path>
        </template>
        <template v-else-if="name === 'angle-down'">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"></path>
        </template>
        <template v-else-if="name === 'messages'">
            <path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path>
            <path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
        </template>
        <template v-else-if="name === 'bell'">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </template>
        <template v-else-if="name === 'box-include'">
            <path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path>
            <path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
        </template>
        <template v-else-if="name === 'user-add'">
            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
        </template>
        <template v-else-if="name === 'love'">
            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
        </template>
        <template v-else-if="name === 'comment'">
            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
        </template>
        <template v-else-if="name === 'video'">
            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
        </template>
        <template v-else-if="name === 'eye-open'">
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
        </template>
        <template v-else-if="name === 'moon'">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
        </template>
        <template v-else-if="name === 'sun'">
            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
        </template>
    </svg>
</template>

<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        name: {
            type: String,
            required: true
        },
        fillColor: {
            type: String,
            default: "currentColor"
        },
        strokeColor: {
            type: String,
            default: ""
        },
        strokeWidth: {
            type: String,
            default: "1"
        },
        viewBox: {
            type: String,
            default: "0 0 20 20"
        },
        width: {
            type: String, // Allow string (e.g., "24px") or number (e.g., 24)
            default: "6", // Default width
        },
        height: {
            type: String, // Allow string or number
            default: "6", // Default height
        },
        className: {
            type: String,
            default: '', // Default to an empty string
        },
    });

    const iconClass = computed(() => {
        // Combine base classes with the passed className prop
        return [
            `w-${props.width}`,
            `h-${props.height}`,
            props.className
        ];
    });
</script>
