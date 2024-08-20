<template>
    <ConfirmationModal :show="state.show">
        <template #title>
            {{ state.title }}
        </template>

        <template #content>
            {{ state.message }}
        </template>

        <template #footer>
            <SecondaryButton @click="cancel" ref="cancelButtonRef">Cancel</SecondaryButton>
            <PrimaryButton @click="confirm" class="ml-3" >Confirm</PrimaryButton>
        </template>
    </ConfirmationModal>
</template>

<script setup>
import { useConfirm } from '@/Utilities/Composables/useConfirm';
import ConfirmationModal from './ConfirmationModal.vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import { nextTick, watchEffect, ref } from 'vue';

const {state, confirm, cancel} = useConfirm();

const cancelButtonRef = ref(null);

watchEffect(async () => {
    if(state.show) {
        await nextTick();

        cancelButtonRef.value?.$el.focus();
    }
});

</script>
