<template>
    <div class="bg-white mx-auto border px-6 py-4 rounded-lg">
        <div class="flex items-center mb-6">
            <img :src="comment.user.profile_photo_url" alt="Avatar" class="w-12 h-12 rounded-full mr-4">
            <div>
                <div class="text-lg font-medium text-gray-800">{{ comment.user.name }}</div>
                <div class="text-gray-500">{{ relativeDate(comment.created_at) }}</div>
            </div>
        </div>
        <div class="mt-1 prose prose-sm max-w-none" v-html="comment.html"></div>
        <div class="flex justify-between items-center ">
            <div>
                <a href="#" class="text-gray-500 hover:text-gray-700 mr-4"><i class="far fa-thumbs-up"></i> Like</a>
                <a href="#" class="text-gray-500 hover:text-gray-700"><i class="far fa-comment-alt"></i> Reply</a>
            </div>
            <div class="flex items-center">
                <a href="#" class="text-gray-500 hover:text-gray-700 mr-4 sr-only"><i class="far fa-flag"></i>
                    Report</a>


                <a href="#" class="text-gray-500 hover:text-gray-700 mr-4 sr-only"><i class="far fa-flag"></i>
                    Delete</a>

                <form v-if="comment.can?.update" @submit.prevent="$emit('edit', comment.id)">

                    <button class="text-gray-500 hover:text-gray-700 mr-4">Edit</button>

                </form>

                <form v-if="comment.can?.delete" @submit.prevent="$emit('delete', comment.id)">

                    <button class="text-red-500 hover:text-red-700 mr-4">Delete</button>

                </form>
            </div>
        </div>
    </div>
</template>

<script setup>

import { relativeDate } from '@/Utilities/date';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps(['comment']);


const emit = defineEmits(['delete', 'edit']);


</script>
