<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="w-16 h-16 bg-amber-500 rounded-3xl flex items-center justify-center font-black text-white text-3xl shadow-xl shadow-amber-500/20 transform -rotate-3">B</div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                {{ $t('ui.auth.register_title') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                {{ $t('ui.auth.already_account') }}
                <Link :href="route('login')" class="font-bold text-amber-600 hover:text-amber-500">
                    {{ $t('ui.auth.login') }}
                </Link>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
            <div class="bg-white dark:bg-gray-800 py-8 px-6 shadow-xl shadow-gray-200/50 dark:shadow-none sm:rounded-3xl border border-gray-100 dark:border-gray-700">
                <form class="space-y-6" @submit.prevent="submit">
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1 mb-2">{{ $t('ui.profile.avatar') }}</label>
                        <input v-model="form.name" id="name" name="name" type="text" autocomplete="name" required
                            class="block w-full px-4 py-4 rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" />
                        <div v-if="form.errors.name" class="mt-2 text-sm text-rose-500 font-medium ml-1">{{ form.errors.name }}</div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1 mb-2">{{ $t('ui.auth.email') }}</label>
                        <input v-model="form.email" id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full px-4 py-4 rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" />
                        <div v-if="form.errors.email" class="mt-2 text-sm text-rose-500 font-medium ml-1">{{ form.errors.email }}</div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1 mb-2">{{ $t('ui.auth.password') }}</label>
                        <input v-model="form.password" id="password" name="password" type="password" autocomplete="new-password" required
                            class="block w-full px-4 py-4 rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" />
                        <div v-if="form.errors.password" class="mt-2 text-sm text-rose-500 font-medium ml-1">{{ form.errors.password }}</div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1 mb-2">{{ $t('ui.auth.confirm_password') }}</label>
                        <input v-model="form.password_confirmation" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                            class="block w-full px-4 py-4 rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all" />
                        <div v-if="form.errors.password_confirmation" class="mt-2 text-sm text-rose-500 font-medium ml-1">{{ form.errors.password_confirmation }}</div>
                    </div>

                    <div>
                        <button type="submit" :disabled="form.processing"
                            class="w-full flex justify-center py-4 px-4 rounded-2xl shadow-lg shadow-amber-500/20 text-sm font-black uppercase tracking-widest text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all transform active:scale-[0.98] disabled:opacity-50">
                            {{ form.processing ? '...' : $t('ui.auth.register') }}
                        </button>
                    </div>
                </form>

                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-100 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white dark:bg-gray-800 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                {{ $t('ui.auth.social_login') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <a :href="route('social.redirect', { provider: 'google' })"
                            class="w-full inline-flex justify-center py-4 px-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-sm font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <span class="sr-only">Sign in with Google</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.908 3.152-1.928 4.176-1.228 1.216-3.144 2.568-6.912 2.568-5.94 0-10.56-4.82-10.56-10.8s4.62-10.8 10.56-10.8c3.216 0 5.512 1.272 7.256 2.92l2.304-2.304c-2.408-2.272-5.584-3.616-9.56-3.616-6.624 0-12 5.376-12 12s5.376 12 12 12c3.584 0 6.288-1.184 8.4-3.344 2.136-2.136 2.856-5.144 2.856-7.664 0-.712-.064-1.392-.176-2.048h-11.08z" />
                            </svg>
                        </a>

                        <a :href="route('social.redirect', { provider: 'facebook' })"
                            class="w-full inline-flex justify-center py-4 px-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-sm font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <span class="sr-only">Sign in with Facebook</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
