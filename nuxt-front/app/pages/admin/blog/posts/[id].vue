<script setup>
const route = useRoute();
const id = route.params.id;
const backendUrl = 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-8080.app.github.dev';

// Перейменовуємо data в post і за допомогою transform дістаємо потрібний ключ
const { data: post, error, pending } = await useFetch(`${backendUrl}/api/admin/blog/posts/${id}`, {
  transform: (response) => response.data // витягує дані з обгортки Laravel Resource
});

if (error.value) {
  console.error("Помилка API:", error.value);
}
</script>

<template>
  <div v-if="pending" class="p-4 text-center">Завантаження...</div>
  
  <div v-else-if="error" class="p-4 text-red-500">
    Помилка: {{ error.message }}
  </div>
  
  <div v-else-if="post" class="p-6 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-4">{{ post.title }}</h1>
    
    <div class="text-sm text-gray-500 mb-4">
      <span>Автор: {{ post.user?.name || 'Невідомий' }}</span>
      <span class="mx-2">|</span>
      <span>Категорія: {{ post.category?.title || 'Без категорії' }}</span>
    </div>
  </div>
</template>