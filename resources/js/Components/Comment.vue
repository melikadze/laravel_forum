<template>
    <div class="bg-white mx-auto border px-6 py-4 rounded-lg">
        <div class="flex items-center mb-6">
            <img :src="comment.user.profile_photo_url" alt="Avatar" class="w-12 h-12 rounded-full mr-4">
            <div>
                <div class="text-lg font-medium text-gray-800">{{ comment.user.name }}</div>
                <div class="text-gray-500">{{ relativeDate(comment.created_at) }} ago</div>
            </div>
        </div>
        <p class="text-lg leading-relaxed mb-6">{{ comment.body }}</p>
        <div class="flex justify-between items-center hidden">
            <div>
                <a href="#" class="text-gray-500 hover:text-gray-700 mr-4"><i class="far fa-thumbs-up"></i> Like</a>
                <a href="#" class="text-gray-500 hover:text-gray-700"><i class="far fa-comment-alt"></i> Reply</a>
            </div>
            <div class="flex items-center">
                <a href="#" class="text-gray-500 hover:text-gray-700 mr-4"><i class="far fa-flag"></i> Report</a>
                <a href="#" class="text-gray-500 hover:text-gray-700"><i class="far fa-share-square"></i> Share</a>
            </div>
        </div>
    </div>
</template>

<script setup>

import {relativeDate} from '@/Utilities/date';

defineProps(['comment']);

</script>
