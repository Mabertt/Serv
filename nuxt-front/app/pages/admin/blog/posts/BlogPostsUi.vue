<template>
  <div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Список постів</h1>
      <UButton to="/admin/blog/posts/create" color="primary" icon="i-heroicons-plus-20-solid">
        Додати пост
      </UButton>
    </div>

    <div class="mb-6">
      <UInput 
        v-model="searchQuery" 
        placeholder="Пошук за заголовком..." 
        icon="i-heroicons-magnifying-glass-20-solid"
        class="w-full md:max-w-sm"
        @input="handleSearch"
      />
    </div>

    <UTable 
      :data="posts" 
      :columns="columns" 
      :loading="pending"
    />

    <div class="flex justify-center mt-6" v-if="totalPosts > perPage">
      <UPagination
        v-model:page="page"
        :items-per-page="perPage"
        :total="totalPosts"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, h, resolveComponent } from 'vue';
import type { TableColumn } from '@nuxt/ui';

const NuxtLink = resolveComponent('NuxtLink');
const UBadge = resolveComponent('UBadge');
const UButton = resolveComponent('UButton');

type Post = {
  id: number;
  author_name: string;
  category_title: string;
  title: string;
  published_at: string | null;
};

const columns: TableColumn<Post>[] = [
  { accessorKey: 'id', header: '#' },
  { accessorKey: 'author_name', header: 'Автор' },
  { accessorKey: 'category_title', header: 'Категорія' },
  { 
    accessorKey: 'title', 
    header: 'Заголовок',
    cell: ({ row }) => h(NuxtLink, { 
      to: `/admin/blog/posts/${row.original.id}`, 
      class: 'hover:underline font-medium' 
    }, () => row.original.title)
  },
  { 
    accessorKey: 'published_at', 
    header: 'Дата публікації',
    cell: ({ row }) => row.original.published_at 
      ? h('span', {}, row.original.published_at) 
      : h(UBadge, { variant: 'subtle' }, () => 'Чернетка')
  },
  {
    accessorKey: 'actions',
    header: 'Дії',
    cell: ({ row }) => {
      return h('div', { class: 'flex space-x-2' }, [
        // Додана кнопка "Перегляд"
        h(UButton, {
          size: 'xs',
          to: `/admin/blog/posts/${row.original.id}`,
          icon: 'i-heroicons-eye-20-solid',
          variant: 'ghost',
          color: 'gray'
        }),
        // Кнопка "Редагувати"
        h(UButton, {
          size: 'xs',
          to: `/admin/blog/posts/${row.original.id}/edit`,
          icon: 'i-heroicons-pencil-square-20-solid',
          variant: 'ghost'
        }),
        // Кнопка "Видалити"
        h(UButton, {
          size: 'xs',
          icon: 'i-heroicons-trash-20-solid',
          variant: 'ghost',
          onClick: () => deletePost(row.original.id)
        })
      ])
    }
  }
];

const posts = ref<Post[]>([]);
const page = ref(1);
const totalPosts = ref(0);
const perPage = ref(10);
const pending = ref(false);
const searchQuery = ref('');
let searchTimeout: NodeJS.Timeout;

const backendUrl = 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-8080.app.github.dev';

const loadApiData = async (pageNumber: number, query: string = '') => {
  pending.value = true;
  try {
    const response = await $fetch<any>(`${backendUrl}/api/admin/blog/posts`, {
      params: { 
        page: pageNumber,
        search: query 
      }
    });
    
    if (response) {
      const rawData = response.data?.data || response.data || [];
      posts.value = rawData.map((item: any) => ({
        id: item.id,
        title: item.title,
        published_at: item.published_at,
        author_name: item.user?.name || 'Невідомий автор',
        category_title: item.category?.title || 'Без категорії'
      }));
      totalPosts.value = response.meta?.total || response.total || 0;
      perPage.value = response.meta?.per_page || response.per_page || 10;
    }
  } catch (error) {
    console.error('Помилка завантаження:', error);
  } finally {
    pending.value = false;
  }
};

const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    page.value = 1; 
    loadApiData(page.value, searchQuery.value);
  }, 500);
};

const deletePost = async (id: number) => {
  if (!confirm('Ви впевнені, що хочете видалити цей пост?')) return
  try {
    await $fetch(`${backendUrl}/api/admin/blog/posts/${id}`, { method: 'DELETE' })
    loadApiData(page.value, searchQuery.value)
  } catch (error) {
    console.error('Помилка видалення:', error)
  }
};

// Спостерігач за сторінкою
watch(page, (newPage) => loadApiData(newPage, searchQuery.value));

loadApiData(page.value);
</script>