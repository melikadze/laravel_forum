<template>
    <div>
        <Link v-if="likeable.can.like" :href="route('likes.store', [type, likeable.id])" preserve-scroll="true" method="post">
        <span class="text-pink-500 font-bold hover:text-pink-700 mr-4 cursor-pointer"><i class="ri-heart-2-line"></i> {{
            likeable.likes_count }} Likes</span>
        </Link>

        <Link v-if="likeable.can.unlike" :href="route('likes.store', [type, likeable.id])" preserve-scroll="true" method="delete">
        <span class="text-pink-500 font-bold hover:text-pink-700 mr-4 cursor-pointer"><i class="ri-heart-2-fill"></i> {{
            likeable.likes_count }} Likes</span>
        </Link>

        <span v-if="! $page.props.auth.user" class="text-pink-500 font-bold hover:text-pink-700 mr-4 cursor-pointer"><i class="ri-heart-2-line"></i> {{
            likeable.likes_count }} Likes</span>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps(['likeable', 'type']);

</script>
