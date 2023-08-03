<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
// import PostCard from '@/Components/PostCard.vue';
import { router, useForm } from '@inertiajs/vue3';

// const props = defineProps({
//     posts: Array
// })

function search(ev) {
    router.visit('/explore/' + ev.target.value)
}
</script>

<template>
    <AppLayout title="Explore">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Explore
                </h2>

                <TextInput 
                class="w-1/4" 
                placeholder="Search post"
                @keyup.enter="search($event)"
                />

                <p class="px-8">&nbsp;&nbsp;&nbsp;</p>
            </div>
        </template>

        <div class="py-4">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

                <div class="flex justify-center">
                    <span class="flex items-center text-gray-400 text-xl">
                        
                          Looking for something? &nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                          </svg>
                          
                        </span>
                </div>
                <!-- <PostCard 
                v-for="post in props.posts" 
                :key="post.id" 
                :post="post"
                :user="$page.props.auth.user"
                 /> -->

            </div>
        </div>
    </AppLayout>
</template>
