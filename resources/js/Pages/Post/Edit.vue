<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const showPhoto = ref(false)

const props = defineProps({
    post: Object,
    user: Object
})

const form = useForm({
    photo: props.post.photo,
    content: props.post.content,
})

function updatePost() {
    form.patch('/post/' + props.post.id);
};

function removePhoto() {
    showPhoto.value = ! showPhoto.value
    form.photo = null;
}

function search(ev) {
    router.visit('/search/' + ev.target.value)
}

function formatDate(timestamp) {
  const months = [
    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
  ];

  const dateObj = new Date(timestamp);
  const month = months[dateObj.getMonth()];
  const day = dateObj.getDate();
  const year = dateObj.getFullYear();

  return `${month} ${day}, ${year}`;
}

onMounted(() => {
    showPhoto.value = props.post.photo_url ? true : false;
})
</script>

<template>
    <AppLayout title="Edit Post">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit your post
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
                <div class="p-4 bg-white rounded-lg">
                    <div class="flex space-x-3 p-4">
                        <img 
                        :src="user.profile_photo_url"
                        class="w-[50px] rounded-full"
                        :alt="user.name">
            
                        <div>
                            <span class="text-sm font-semibold">{{ user.name }}</span>
                            <div class="flex items-center space-x-1">
                                <span class="text-xs">{{ formatDate(post.updated_at) }}</span>
                                <span class="text-gray-500">&#8226;</span>
                                <span class="text-xs px-4 rounded-lg border">{{ post.privacy }}</span>
                            </div>
                        </div>
                    </div>

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
    
                    <div v-show="showPhoto" class="mt-2 relative w-full h-full" >
                        <img :src="post.photo_url" class="h-full w-full">
                        <span class="absolute right-0 top-0 p-2 text-gray-600 hover:bg-gray-100 rounded-full" @click="removePhoto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                              </svg>
                        </span>
                    </div>
                    <hr class="mb-3 mt-3">
                    <div class="flex justify-end space-x-2">
                        <Link 
                        :href="'/post/' + props.post.id" 
                        method="DELETE"
                        class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >Delete Post</Link>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="updatePost">
                            Save
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
