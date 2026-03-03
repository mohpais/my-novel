<template>
    <div class="flex flex-col flex-1 w-full lg:w-1/12">
        <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
            <div class="">
                <div class="mb-5 sm:mb-8">
                    <h1
                        class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md"
                    >
                        Your Best Work Starts Here!
                    </h1>
                </div>
                <div class="py-6">
                    <FormProvider
                        class="w-full max-w-md space-y-3 md:space-y-4 lg:max-w-xl"
                        :initial-values="registerForm"
                        @on-submit="handleRegist"
                    >
                        <template #touch-form>
                            <div class="space-y-5">
                                <FormField
                                    id="name"
                                    name="name"
                                    labelName="What should we call you?"
                                    placeholder="Enter your name"
                                    rules="required"
                                />
                                <FormField
                                    type="email"
                                    id="email"
                                    name="email"
                                    labelName="Email"
                                    placeholder="Enter email"
                                    rules="required|email"
                                />
                                <FormField
                                    type="password"
                                    id="password"
                                    name="password"
                                    labelName="Password"
                                    placeholder="Enter password"
                                    rules="required"
                                />
                                <div class="md:space-y-2">
                                    <div class="flex items-start">
                                        <FormField
                                            as="checkbox"
                                            id="agreeToTerms"
                                            name="agreeToTerms"
                                            checkboxName="Aggree Terms"
                                        />
                                    </div>
                                </div>
                                <Button
                                    type="submit"
                                    class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition duration-200"
                                    :loading="isLoading"
                                >
                                    Create an account
                                </Button>
                                <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                                    Already have an account? <router-link :to="{ name: 'LoginPage' }" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Login here</router-link>
                                </p>
                            </div>
                        </template>
                    </FormProvider>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { reactive, ref } from "vue";
    import { useAuthStore } from "@/stores/useAuthStore";

    const authStore = useAuthStore();
    const isLoading = ref(false);

    const registerForm = reactive({
        name: '',
        email: '',
        password: '',
        agreeToTerms: false,
    });

    const handleRegist = async ({ values, actions }) => {
        // isLoading.value = true
        // try {
        //     const res = await authService.doRegist(values);

        //     if (!res.success) throw new Error('Register failed');

        //     authStore.authenticated(res.data);
        // } catch (error) {
        //     window.$toast.show(error, 'error')
        // } finally {
        //     isLoading.value = false
        // }

    };
</script>
