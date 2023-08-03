<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { useForm, Link, router } from '@inertiajs/vue3'
import { ref, onMounted, computed } from 'vue';

const isLiked = ref(false);
const open = ref(false)
const prop = defineProps({
    post: Object,
    user: Object,
})

// console.log(prop.user)

const form = useForm({
    post_id: null
})

const isLikedByCurrentUser = computed(() => {
  return prop.post.likes.some(like => like.user_id === prop.user.id);
});

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

function like() {
    form.post_id = prop.post.id

    form.post('/like-post/' + prop.post.id, {
        preserveScroll: true,
    })

    isLiked.value = true;
}

function unlike() {
    form.post_id = prop.post.id

    form.delete('/unlike-post/' + prop.post.id, {
        preserveScroll: true,
    })
    isLiked.value = false;

}

function openDropdown() {
    open.value = true;
}

function toggleHidden(post) {
    router.post('/post/toggle/' + post.id)
}

onMounted(() => {
    isLiked.value = isLikedByCurrentUser.value
})
</script>

<template>
    <div class="bg-white overflow-hidden shadow-sm border sm:rounded-lg mt-2 mb-2">
        <div class="relative flex justify-between items-center">
            <div class="flex space-x-3 p-4">
                <img 
                :src="post.user.profile_photo_url"
                class="w-[40px] rounded-full"
                :alt="post.user.name">
    
                <div>
                    <span class="text-sm font-semibold">{{ post.user.name }}</span>
                    <div class="flex items-center space-x-1">
                        <span class="text-xs">{{ formatDate(post.created_at) }}</span>
                        <span class="text-gray-500">&#8226;</span>
                        <span class="text-xs">{{ post.privacy }}</span>
                    </div>
                </div>
            </div>

            <div v-if="prop.user.roles.includes('admin')">
                <label class="relative inline-flex items-center cursor-pointer" :for="'status-' + post.id">
                    <input type="checkbox" :checked="post.hidden" :id="'status-' + post.id" class="sr-only peer" @change="toggleHidden(post)">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-md font-semibold text-gray-900 dark:text-gray-300"></span>
                  </label>
            </div>

            <span v-if="prop.user.roles.includes('user')" class="rounded-full hover:bg-gray-100 p-2 mr-4" @click="openDropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                </svg>
            </span>

            <!-- Dropdown -->
            <div v-show="open" class="fixed inset-0 z-40" @click="open = false" />
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div
                    v-show="open"
                    class="absolute right-4 bottom-0 z-50 mt-2 rounded-md shadow-lg"
                    style="display: none;"
                    @click="open = false"
                >
                    <div class="absolute -bottom-10 right-4 rounded-md ring-1 ring-black ring-opacity-5 bg-white w-auto">
                        <!-- <slot name="content" /> -->
                        <Link 
                        v-if="post.user_id == user.id"
                        as="button"
                        :href="'/post/edit/' + prop.post.id" 
                        class="w-[100px] flex px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-pencil mr-2" viewBox="0 0 16 16">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                          </svg>
                        Edit</Link>

                        <Link
                        as="button"
                        v-if="post.user_id == user.id"
                        :href="'/delete-post/' + prop.post.id" 
                        method="DELETE"
                        class="w-[100px] flex px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3 mr-2" viewBox="0 0 16 16">
                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                          </svg>Delete</Link>

                    </div>
                </div>
            </transition>
        

            
        </div>
        <div class="px-4 pb-4">{{ post.content }}</div>
        <img v-if="post.photo_url" :src="post.photo_url" class="w-full h-full" alt="">
        <hr class="">
        <div v-if="prop.user.roles.includes('user')" class="p-4 flex justify-center items-center">
            <span v-if="isLiked" @click.prevent="unlike" class="px-6 py-1.5 rounded-lg hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill text-red-400" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                  </svg>
            </span>
            <span v-else @click.prevent="like" class="px-6 py-1.5 rounded-lg hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                  </svg>
            </span>
        </div>
    </div>
</template>