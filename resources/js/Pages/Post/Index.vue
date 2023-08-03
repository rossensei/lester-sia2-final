<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import PostCard from '@/Components/PostCard.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const createPostModal = ref(false);
const uploadPhoto = ref(false);
const contentInput = ref(null);
const photoInput = ref(null);
const photoPreview = ref(null);

const props = defineProps({
    posts: Array
})

const form = useForm({
    photo: null,
    content: null
})

function toggleCreateModal() {
    // router.visit('/create-post')
    createPostModal.value = true;

    setTimeout(() => contentInput.value.focus(), 250);
}

function toggleCreateModalWithPhotoUpload() {
    createPostModal.value = true;
    uploadPhoto.value = true;
    setTimeout(() => contentInput.value.focus(), 250);
}

function closeModal() {
    createPostModal.value = false;
    uploadPhoto.value = false;
    photoPreview.value = null;
    form.reset();
}

function selectNewPhoto() {
    photoInput.value.click();
};

function updatePhotoPreview() {
    uploadPhoto.value = false;
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

function createPost() {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post('/create-post');

    closeModal();
};

function search(ev) {
    router.visit('/search/' + ev.target.value)
}
</script>

<template>
    <AppLayout title="Home">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Home
                </h2>

                <TextInput 
                class="w-1/4" 
                placeholder="Search post"
                @keyup.enter="search($event)"
                />
            </div>
        </template>

        <div class="py-4">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

                <div v-if="$page.props.auth.user.roles.includes('user')" class="bg-white overflow-hidden shadow-sm border sm:rounded-lg p-4">
                    <div class="flex space-x-3">
                        <img 
                        :src="$page.props.auth.user.profile_photo_url"
                        class="w-[40px] rounded-full"
                        :alt="$page.props.auth.user.name">

                        <TextInput class="w-full hover:bg-gray-100 focus:ring-0 focus:border" placeholder="What is happening?" @click="toggleCreateModal"/>

                    </div>

                    <hr class="mb-3 mt-3">

                    <div class="flex justify-center items-center">
                        <button class="px-8 py-1.5 flex hover:bg-gray-100 rounded-lg" @click="toggleCreateModalWithPhotoUpload">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-images mr-2" viewBox="0 0 16 16">
                            <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                            <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
                          </svg>
                          Upload photo
                        </button>
                    </div>
                </div>

                <PostCard 
                v-for="post in props.posts" 
                :key="post.id" 
                :post="post"
                :user="$page.props.auth.user"
                 />

                <DialogModal :show="createPostModal" @close="closeModal">
                    <template #title>
                        Create new post
                        <hr class="mb-3 mt-3">
                    </template>
                    
                    <template #content>
                        <div class="mt-4">
                            <textarea 
                            ref="contentInput"
                            v-model="form.content"
                            type="text"
                            class="mt-1 block border-none focus:border-none focus:ring-0 w-full rounded-lg"
                            placeholder="Write something.."
                            @keyup.enter="createPost"
                            ></textarea>
                            <InputError :message="form.errors.content" class="mt-2" />
                        </div>

                        <div v-show="uploadPhoto" class="mt-4">
                            <div class="relative w-full h-[170px] border shadow-sm rounded-lg">
                                <span class="absolute right-3 top-3 rounded-full bg-gray-300 hover:bg-gray-200 p-2" @click="uploadPhoto = false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                      </svg>
                                </span>

                                <div class="flex justify-center items-center w-full h-full bg-gray-50 hover:bg-gray-100" @click="selectNewPhoto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-images mr-2" viewBox="0 0 16 16">
                                        <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                        <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
                                      </svg>
                                    Add photo

                                    <input
                                        ref="photoInput"
                                        type="file"
                                        class="hidden"
                                        @change="updatePhotoPreview"
                                    >
                                </div>
                            </div>
                        </div>

                        <div v-show="photoPreview" class="mt-2">
                            <img :src="photoPreview" class="h-full w-full">
                        </div>

                    </template>
    
                    <template #footer>
                        <SecondaryButton @click="closeModal">
                            Cancel
                        </SecondaryButton>
    
                        <PrimaryButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="createPost"
                        >
                            Post
                        </PrimaryButton>
                    </template>
                </DialogModal>

            </div>
        </div>
    </AppLayout>
</template>
