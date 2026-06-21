<template>
  <div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Категорії блогу</h1>
      <UButton to="/admin/blog/categories/create" color="primary" icon="i-heroicons-plus-20-solid">
        Додати категорію
      </UButton>
    </div>

    <div class="mb-6">
      <UInput 
        v-model="searchQuery" 
        icon="i-heroicons-magnifying-glass-20-solid"
        placeholder="Пошук категорій..." 
        class="w-full md:max-w-sm"
      />
    </div>

    <div>
      <div v-if="pending" class="flex justify-center items-center h-40">
        <UIcon name="i-heroicons-arrow-path-20-solid" class="animate-spin text-2xl" />
      </div>
      
      <div v-else-if="filteredCategories.length === 0" class="p-6 text-center">
        {{ searchQuery ? 'Нічого не знайдено' : 'Немає категорій. Створіть нову!' }}
      </div>
      
      <UTable 
        v-else 
        :data="filteredCategories" 
        :columns="columns" 
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, h, resolveComponent } from 'vue'
import type { TableColumn } from '@nuxt/ui'

const config = useRuntimeConfig()
const backendUrl = config.public.apiBase || 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-8080.app.github.dev'

definePageMeta({ layout: 'admin' })

const UButton = resolveComponent('UButton')
const searchQuery = ref('') // Змінна для пошуку

type Category = { id: number; title: string; slug: string; parent_title: string | null }

// Фільтрація категорій
// Фільтрація категорій
const filteredCategories = computed(() => {
  const query = searchQuery.value.toLowerCase()
  if (!query) return categories.value
  
  // Явно вказуємо, що cat має тип Category
  return categories.value.filter((cat: Category) => 
    cat.title.toLowerCase().includes(query) || 
    cat.slug.toLowerCase().includes(query)
  )
})

const columns: TableColumn<Category>[] = [
  { accessorKey: 'id', header: '#' },
  { accessorKey: 'title', header: 'Назва' },
  { accessorKey: 'slug', header: 'Slug', cell: ({ row }) => h('span', {}, row.original.slug) },
  { accessorKey: 'parent_title', header: 'Батько', cell: ({ row }) => row.original.parent_title || '-' },
  {
    accessorKey: 'actions',
    header: 'Дії',
    cell: ({ row }) => {
      return h('div', { class: 'flex space-x-2' }, [
        h(UButton, {
          size: 'xs',
          to: `/admin/blog/categories/${row.original.id}/edit`,
          icon: 'i-heroicons-pencil-square-20-solid',
          variant: 'ghost'
        }),
        h(UButton, {
          size: 'xs',
          icon: 'i-heroicons-trash-20-solid',
          variant: 'ghost',
          onClick: () => deleteCategory(row.original.id)
        })
      ])
    }
  }
];

const { data, pending, refresh } = await useFetch<any>(`${backendUrl}/api/admin/blog/categories`)
const categories = computed(() => data.value?.data || [])

const deleteCategory = async (id: number) => {
  if (!confirm('Ви впевнені?')) return
  await $fetch(`${backendUrl}/api/admin/blog/categories/${id}`, { method: 'DELETE' })
  refresh()
}
</script>