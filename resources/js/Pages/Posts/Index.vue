<template>
    <AppLayout>
        <Container>
            <div>
                <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All Posts'"></PageHeading>
                <p v-if="selectedTopic" class="mt-1 text-gray-600 text-sm">{{ selectedTopic.description }}</p>

                <menu class="flex space-x-1 mt-4 overflow-x-auto pb-4 pt-1 mt-3">
                    <li><Phill :filled="!selectedTopic" :href="route('posts.index')">All Posts</Phill></li>
                    <li v-for="topic in topics" :key="topic.id">
                        <Phill :filled="topic.id === selectedTopic?.id" :href="route('posts.index', { topic: topic.slug })">
                        {{ topic.name }}
                        </Phill>
                    </li>
                </menu>
            </div>
            <ul class="divide-y mt-4">
                <li v-for="post in posts.data" :key="post.id"
                    class="flex justify-between items-baseline flex-col md:flex-row mb-2">
                    <Link :href="post.routes.show" class="group px-2 py-4 block">
                    <span class="font-bold text-lg group-hover:text-indigo-500">{{ post.title }}</span>
                    <span class="block pt-1 text-sm text-gray-600">{{ formattedDate(post) }} ago by {{ post.user.name
                        }}</span>
                    </Link>

                    <Phill :href="route('posts.index', { topic: post.topic.slug })">
                        {{ post.topic.name }}
                    </Phill>
                </li>
            </ul>

            <Pagination :meta="posts.meta" :only="['posts']" />
        </Container>
    </AppLayout>
</template>

<script setup>

import Container from '@/Components/Container.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { relativeDate } from '@/Utilities/date';
import PageHeading from '@/Components/PageHeading.vue';
import Phill from '@/Components/Phill.vue';


defineProps(['posts', 'topics', 'selectedTopic']);

const formattedDate = (post) => relativeDate(post.created_at);
</script>
