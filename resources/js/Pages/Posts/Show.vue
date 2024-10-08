<template>

    <Head>
        <link rel="canocical" :href="post.routes.show" />
    </Head>

    <AppLayout :title="post.title">
        <Container>
            <Phill :href="route('posts.index', { topic: post.topic.slug })" class="mt-2">{{ post.topic.name }}</Phill>
            <PageHeading>{{ post.title }}</PageHeading>

            <span class="block mt-1 text-sm text-gray-600">{{ formattedDate }} by {{ post.user.name }}</span>

            <div class="mt-4">
                <Likeable  type="post" :likeable="post" />
            </div>

            <article class="mt-6 prose prose-sm max-w-none" v-html="post.html">
            </article>



            <div class="mt-12">
                <h2 class="text-xl font-semibold">Comments</h2>

                <form v-if="$page.props.auth.user"
                    @submit.prevent="() => commentIdBeingEdited ? updateComment() : addComment()" class="mt-4">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <MarkdownEditor ref="commentTextAreaRef" id="body" v-model="commentForm.body" class="w-full"
                            placeholder="Speak your mind Spock..." editorClass="!min-h-[160px]" />
                        <InputError :message="commentForm.errors.body" class="mt-1"></InputError>
                    </div>

                    <PrimaryButton type="submit" class="mt-3" :disabled="commentForm.processing"
                        v-text="commentIdBeingEdited ? 'Update Comment' : 'Add Comment'"></PrimaryButton>
                    <SecondaryButton @click="cancelEditComment" v-if="commentIdBeingEdited" class="ml-2">Cancel
                    </SecondaryButton>
                </form>

                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class=" px-2 py-4">
                        <Comment @edit="editComment" @delete="deleteComment" :comment="comment" />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']" />
            </div>
        </Container>
    </AppLayout>
</template>

<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import { relativeDate } from '@/Utilities/date';
import Comment from '@/Components/Comment.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import TextArea from '@/Components/TextArea.vue';
import InputError from '@/Components/InputError.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useConfirm } from '@/Utilities/Composables/useConfirm';
import MarkdownEditor from '@/Components/MarkdownEditor.vue';
import PageHeading from '@/Components/PageHeading.vue';
import Phill from '@/Components/Phill.vue';
import { Link } from '@inertiajs/vue3';
import Likeable from '@/Components/Likeable.vue';

const props = defineProps(['post', 'comments']);
const formattedDate = computed(() => relativeDate(props.post.created_at));
const commentForm = useForm({
    body: '',
});

const commentTextAreaRef = ref(null);
const commentIdBeingEdited = ref(null);
const commentBeingEdit = computed(() => props.comments.data.find(comment => comment.id === commentIdBeingEdited.value));
const { confirmation } = useConfirm();

const editComment = (commentId) => {
    commentIdBeingEdited.value = commentId;
    commentForm.body = commentBeingEdit.value?.body;
    commentTextAreaRef.value?.focus();
};

const cancelEditComment = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset();
};

const addComment = () => commentForm.post(route('posts.comments.store', props.post.id), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset(),
});

const updateComment = async () => {

    if (! await confirmation('Are you sure you want to update this comment?')) {
        setTimeout(() => commentTextAreaRef.value.focus(), 250);
        return;
    }

    commentForm.put(route('comments.update', {
        comment: commentIdBeingEdited.value,
        page: props.comments.meta.current_page,
    }), {
        preserveScroll: true,
        onSuccess: cancelEditComment,
    });
};

const deleteComment = async (commentId) => {
    if (! await confirmation('Are you sure you want to delete this comment?')) {
        return;
    }

    router.delete(route('comments.destroy', {
        comment: commentId,
        page: props.comments.data.length > 1 ? props.comments.meta.current_page : Math.max(props.comments.meta.current_page - 1, 1)
    }), {
        preserveScroll: true
    });
};
</script>
