<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import PostCard from '@/Components/PostCard.vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    posts: Array
})

function search(ev) {
    router.visit('/search/' + ev.target.value)
}
</script>

<template>
    <AppLayout title="Result">
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

                <h1 class="text-xl mb-3 text-gray-600 font-semibold">Results</h1>
                <PostCard 
                v-for="post in props.posts" 
                :key="post.id" 
                :post="post"
                :user="$page.props.auth.user"
                 />

            </div>
        </div>
    </AppLayout>
</template>
