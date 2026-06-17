<script setup>
const route = useRoute();
const id = route.params.id;
const backendUrl = 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-80.app.github.dev';

// Використовуємо явний виклик для дебагу
const { data, error, pending } = await useFetch(`${backendUrl}/api/admin/blog/posts/${id}`);

if (error.value) {
  console.error("Помилка API:", error.value);
}
</script>

<template>
  <div v-if="pending">Завантаження...</div>
  <div v-else-if="error">Помилка: {{ error.message }}</div>
  <div v-else-if="data">
    <h1>{{ data.title }}</h1>
    </div>
</template>