<template>
  <div class="container mx-auto p-4">
    <div class="flex justify-center">
      <div class="w-full">
        <nav class="navbar bg-gray-100 p-3 mb-4 rounded shadow-sm">
          <a href="/admin/blog/posts/create" class="text-blue-500 hover:underline">Додати</a>
        </nav>
        <div class="card border border-gray-200 rounded shadow-md bg-white">
          <div class="card-body p-4">
            <table class="table-auto w-full text-left border-collapse">
              <thead>
                <tr class="bg-gray-200 text-gray-700">
                  <th class="p-2 border">#</th>
                  <th class="p-2 border">Автор</th>
                  <th class="p-2 border">Категорія</th>
                  <th class="p-2 border">Заголовок</th>
                  <th class="p-2 border">Дата публікації</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-50 transition">
                  <td class="p-2 border">{{ post.id }}</td>
                  <td class="p-2 border">{{ post.user?.name || 'Невідомий' }}</td>
                  <td class="p-2 border">{{ post.category?.title || 'Без категорії' }}</td>
                  <td class="p-2 border">
                    <a :href="'/admin/blog/posts/' + post.id + '/edit'" class="text-blue-600 hover:underline">
                      {{ post.title }}
                    </a>
                  </td>
                  <td class="p-2 border">{{ post.published_at || 'Чернетка' }}</td>
                </tr>
                <tr v-if="posts.length === 0">
                  <td colspan="5" class="p-4 text-center text-gray-500">Завантаження даних...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const config = useRuntimeConfig()
const backendUrl = config.public.apiBase || 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-8080.app.github.dev'
const posts = ref<any[]>([])

const getPosts = () => {
  $fetch<any>(`${backendUrl}/api/admin/blog/posts`)
    .then(response => {
      console.log('Дані з сервера:', response)
      // Беремо масив із response.data, оскільки Laravel повертає пагіновану структуру
      posts.value = response.data || response
    })
    .catch(error => {
      console.error('Помилка запиту API:', error)
    })
}

getPosts()
</script>