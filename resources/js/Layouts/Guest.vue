<script setup>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <div class="min-h-screen sm:justify-center items-center pt-6 sm:pt-0 bg-gray-200" :class="{ 'flex flex-col': route().current() !== 'register' }">
            <slot />
    </div>
</template>
