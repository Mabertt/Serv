<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Таблиця через Nuxt UI з пагінацією</h1>

    <UTable 
      :data="posts" 
      :columns="columns" 
      :loading="pending"
      class="w-full bg-white rounded-lg shadow border border-gray-200"
    >
      
      <template #title-cell="{ row }">
        <a :href="'/admin/blog/posts/' + row.original.id + '/'" class="text-blue-600 hover:underline font-medium">
          {{ row.original.title }}
        </a>
      </template>

      <template #published_at-cell="{ row }">
        <span class="text-gray-500">
          {{ row.original.published_at || 'Чернетка' }}
        </span>
      </template>

    </UTable>

    <div class="flex justify-center mt-6" v-if="totalPosts > perPage">
      <UPagination
        v-model="page"
        :page-count="perPage"
        :total="totalPosts"
        :ui="{ root: 'flex items-center gap-1' }"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

// Для Nuxt UI v4 конфіг стовпців має бути максимально простим.
// accessorKey вказує на точний ключ у пласкому об'єкті рядка.
const columns = [
  { accessorKey: 'id', header: '#' },
  { accessorKey: 'author_name', header: 'Автор' },
  { accessorKey: 'category_title', header: 'Категорія' },
  { accessorKey: 'title', header: 'Заголовок' },
  { accessorKey: 'published_at', header: 'Дата публікації' }
];

const posts = ref<any[]>([]);
const page = ref(1);
const totalPosts = ref(0);
const perPage = ref(25);
const pending = ref(false);

const backendUrl = 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-80.app.github.dev';

const loadApiData = async (pageNumber: number) => {
  pending.value = true;
  try {
    const response = await $fetch<any>(`${backendUrl}/api/admin/blog/posts?page=${pageNumber}`);
    
    if (response && response.data) {
      // КРИТИЧНИЙ КРОК: Робимо структуру об'єктів пласкою для TanStack Table v8
      posts.value = response.data.map((item: any) => ({
        id: item.id,
        title: item.title,
        published_at: item.published_at,
        // Виносимо вкладені дані на верхній рівень, щоб уникнути помилок з об'єктами
        author_name: item.user?.name || 'Невідомий автор',
        category_title: item.category?.title || 'Без категорії'
      }));

      totalPosts.value = response.total || 0;
      perPage.value = response.per_page || 25;
    }
    
    console.log('Пласкі дані успішно підготовлені для Nuxt UI v4:', posts.value);
  } catch (error) {
    console.error('Помилка під час завантаження даних з API:', error);
  } finally {
    pending.value = false;
  }
};

watch(page, (newPage) => {
  loadApiData(newPage);
});

loadApiData(page.value);
</script>