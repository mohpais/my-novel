<template>
    <button
        :type="type"
        aria-label="Button"
        class="font-medium overflow-hidden transition transform duration-100 active:scale-95"
        :disabled="disabled"
        :class="{
            'rounded text-sm': isDefault,
            'px-2 py-1.5 rounded-md text-xs': size === 'xss',
            'px-3 py-2 text-xs': size === 'xs',
            'px-3 py-2 text-sm': size === 'sm',
            'px-5 py-2.5 text-sm': size === 'md',
            'px-5 py-3 text-base': size === 'lg',
            'px-6 py-3.5 text-base': size === 'xl',
            'opacity-50 cursor-not-allowed': disabled,
            'hover:bg-opacity-90': !disabled,
        }"
        @click="handleClick($event)"
    >
        <slot v-if="!loading"></slot>
        <!-- <Loading v-else :size="loadingSize" /> -->
        <template v-else>
            <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
            </svg>
            Loading...
        </template>
    </button>
</template>

<script setup>
    import Loading from './Loading.vue';
    const props = defineProps({
        isDefault: {
            type: Boolean,
            default: true,
        },
        type: {
            type: String,
            default: "button",
        },
        size: {
            type: String,
            default: "xs", // xs, sm, md, lg, xl
        },
        loadingSize: {
            type: String,
            default: "sm", // xs, sm, md, lg, xl
        },
        isRipple: {
            type: Boolean,
            default: true,
        },
        loading: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    });

    const handleRipple = (element, binding, ev) => {
        const rippleElement = document.createElement("span");
        let currentDiameter = 1;
        let currentOpacity = 0.65;
        let animationHandler = setInterval(animateRippleSpread, 8);
        applyRippleStyle();

        function applyRippleStyle() {
            const elementCoordinates = element.getBoundingClientRect();
            const offsetY = ev.clientY - elementCoordinates.y;
            const offsetX = ev.clientX - elementCoordinates.x;

            rippleElement.style.position = "absolute";
            rippleElement.style.height = "5px";
            rippleElement.style.width = "5px";
            rippleElement.style.borderRadius = "100%";
            rippleElement.style.backgroundColor = "#f2f2f2";
            rippleElement.style.left = `${offsetX}px`;
            rippleElement.style.top = `${offsetY}px`;
            ev.target.appendChild(rippleElement);
        }

        function animateRippleSpread() {
            const maximalDiameter = +binding.value || 50;
            if (currentDiameter <= maximalDiameter) {
                currentDiameter++;
                currentOpacity -= 0.9 / maximalDiameter;
                rippleElement.style.transform = `scale(${currentDiameter})`;
                rippleElement.style.opacity = `${currentOpacity}`;
            } else {
                rippleElement.remove();
                clearInterval(animationHandler);
            }
        }
    };

    const handleClick = (event) => {
        if (props.disabled) {
            return false;
        }
        if (props.isRipple) {
            event.target.style.position = "relative";
            handleRipple(event.target, 0, event);

            // Check if the clicked element is the <i> tag
            if (event.target && event.target.tagName === "I") {
                // You clicked on the <i> tag (icon)
            } else {
                // You clicked on the button text or other elements inside the button
                event.target.style.overflow = "hidden";
            }
        }
    };
</script>

<style scoped>
    /* Add the "group" class to enable group-hover styles */
    .group:hover span.scale-105 {
        transform: scale(1.05);
        transition: transform 0.3s;
    }
</style>
