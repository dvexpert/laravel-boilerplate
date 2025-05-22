<script setup lang="ts"></script>

<template>
    <div v-for="i in 3" :key="`admin-user-list-skelton-loader-${i}`" class="flex animate-pulse items-center justify-between p-4">
        <div class="flex flex-col space-y-2">
            <div class="h-4 w-32 rounded bg-gray-200"></div>
            <div class="h-3 w-40 rounded bg-gray-200"></div>
            <div class="mt-1 flex items-center space-x-2">
                <div class="h-4 w-14 rounded bg-gray-200"></div>
                <div class="h-3 w-10 rounded bg-gray-200"></div>
            </div>
        </div>
        <div class="h-4 w-4 rounded bg-gray-200"></div>
    </div>
</template>
