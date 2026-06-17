<template>
  <div class="container mx-auto p-6" v-if="post">
    <h1 class="text-3xl font-bold mb-4">{{ (post as any).title }}</h1>
    <div class="bg-gray-100 p-4 rounded">
      <p><strong>Автор:</strong> {{ (post as any).user?.name }}</p>
      <p><strong>Категорія:</strong> {{ (post as any).category?.title }}</p>
      <p><strong>Дата:</strong> {{ (post as any).published_at }}</p>
    </div>
    <div class="mt-6">
      <p>{{ (post as any).content }}</p>
    </div>
    <NuxtLink to="/BlogPostsUi" class="text-blue-500 underline mt-4 block">Назад до списку</NuxtLink>
  </div>
  <div v-else class="p-6">Завантаження...</div>
</template>

<script setup lang="ts">
const route = useRoute();
const id = route.params.id;
const backendUrl = 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-80.app.github.dev';

// Додаємо <any> тут, щоб TypeScript не сварився
const { data: post } = await useFetch<any>(`${backendUrl}/api/admin/blog/posts/${id}`);
</script>