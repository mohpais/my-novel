<template>
    <Modal
        :isVisible="showModal.reject"
        :hideHeader="true"
        @close="closeModal"
        size="sm"
        :closeOnClickOutside="false"
    >
        <template #body>
            <div class="flex flex-col items-center justify-center text-center">
                <span class="mb-4 inline-flex justify-center items-center size-15.5 rounded-full border-4 border-yellow-50 bg-yellow-100 text-yellow-500 dark:bg-yellow-700 dark:border-yellow-600 dark:text-yellow-100">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                    </svg>
                </span>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Reject Request
                </h3>
                <h4 class="text-base font-medium text-gray-800 dark:text-gray-100 mb-2">{{ showModal.selectedItem?.request_code }}</h4>
                <p class="text-base text-gray-500 dark:text-gray-400">
                    Are you sure you want to reject this request?
                </p>
                <div v-if="showModal.payload.hasOwnProperty('remarks')" class="w-3/4 my-2">
                    <FormField
                        v-model="showModal.payload.remarks"
                        as="textarea"
                        name="remarks"
                        id="remarks"
                        placeholder="Type remarks (additional) ...." />
                </div>
                <div class="flex w-full justify-center gap-2">
                    <button
                        @click="closeModal"
                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto"
                    >
                        Cancel
                    </button>
                    <Button
                        @click="btnRejectRequest"
                        class="bg-danger-500 hover:bg-danger-600"
                        size="sm"
                        :loading="isLoading.rejectRequest"
                    >
                        Yes, reject!
                    </Button>
                </div>
            </div>
        </template>
    </Modal>
</template>

<script setup>
    import { useRequestStore } from '../../store'

    const store = useRequestStore();
    const { showModal, isLoading, btnRejectRequest } = store;

    const closeModal = () => {
        showModal.reject = false;
    };
</script>