<template>
    <AppLayout title="Create a Post">
        <Container>
            <PageHeading>Create a Post</PageHeading>

            <form @submit.prevent="createPost" class="mt-6">
                <div class="mt-3">
                    <InputLabel for="title" class="sr-only">Title</InputLabel>
                    <TextInput id="title" class="w-full" v-model="form.title" placeholder="Give it a great title..." />
                    <InputError :message="form.errors.title" class="mt-1" />
                </div>

                <div class="mt-3">
                    <InputLabel for="topic_id">Select a Topic</InputLabel>
                    <select v-model="form.topic_id" id="topic_id"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 w-full">
                        <option :value="topic.id" v-for="topic in topics" :key="topic.id">{{ topic.name }}</option>
                    </select>
                    <InputError :message="form.errors.topic_id" class="mt-1" />
                </div>

                <div class="mt-3">
                    <InputLabel for="body" class="sr-only">Title</InputLabel>
                    <MarkdownEditor v-model="form.body">
                        <template v-if="!isInProduction()" #toolbar="{ editor }">
                            <li><button type="button" @click="autofill" class="px-3 py-2" title="Autofill"><i
                                        class="ri-article-line"></i></button></li>
                        </template>
                    </MarkdownEditor>
                    <InputError :message="form.errors.body" class="mt-1" />
                </div>

                <div class="mt-3">
                    <PrimaryButton type="submit">Create Post</PrimaryButton>
                </div>
            </form>
        </Container>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextArea from '@/Components/TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import MarkdownEditor from '@/Components/MarkdownEditor.vue';
import axios from 'axios';
import { isInProduction } from '@/Utilities/environment';
import PageHeading from '@/Components/PageHeading.vue';

const props = defineProps(['topics']);

const form = useForm({
    title: '',
    topic_id: props.topics[0].id,
    body: '',
});

const createPost = () => form.post(route('posts.store'));

const autofill = async () => {

    if (isInProduction()) {
        return;
    }

    const response = await axios.get('/local/post-content');

    form.title = response.data.title;
    form.body = response.data.body;
};

</script>
