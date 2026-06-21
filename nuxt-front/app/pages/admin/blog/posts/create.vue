<template>
  <div class="max-w-2xl p-6">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Створення нового поста</h1>

    <UForm :state="state" @submit="onSubmit" class="space-y-6">
      
      <UFormField label="Заголовок" name="title" required hint="Обов'язкове поле">
        <UInput 
          v-model="state.title" 
          placeholder="Введіть заголовок поста" 
          icon="i-heroicons-document-text-20-solid"
          class="w-full"
        />
      </UFormField>

      <UFormField label="Slug (URI)" name="slug" required hint="Унікальний ідентифікатор">
        <UInput 
          v-model="state.slug" 
          placeholder="my-new-post" 
          icon="i-heroicons-link-20-solid"
          class="w-full"
        />
      </UFormField>

      <UFormField label="Категорія" name="category_id" required>
        <USelect 
          v-model="state.category_id" 
          :items="categories" 
          label-key="title" 
          value-key="id"
          placeholder="Оберіть категорію..." 
          class="w-full"
        />
      </UFormField>

      <UFormField label="Короткий опис (Excerpt)" name="excerpt">
        <UTextarea 
          v-model="state.excerpt" 
          :rows="3" 
          placeholder="Напишіть короткий вступ..." 
          class="w-full"
        />
      </UFormField>

      <UFormField label="Контент (Raw)" name="content_raw" required>
        <UTextarea 
          v-model="state.content_raw" 
          :rows="10" 
          placeholder="Основний текст вашого поста..." 
          class="w-full"
        />
      </UFormField>

      <UFormField name="is_published">
        <UCheckbox v-model="state.is_published" label="Опублікувати одразу?" />
      </UFormField>

      <div class="flex gap-4 pt-6 border-t">
        <UButton type="submit" color="primary" size="lg" :loading="pending" icon="i-heroicons-check-20-solid">
          Зберегти пост
        </UButton>
        <UButton to="/admin/blog/posts/BlogPostsUi" color="neutral" variant="ghost" size="lg" icon="i-heroicons-arrow-left-20-solid">
          Скасувати
        </UButton>
      </div>
    </UForm>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'

const config = useRuntimeConfig()
const backendUrl = config.public.apiBase || 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-8080.app.github.dev'

definePageMeta({ layout: 'admin' })

const pending = ref(false)
const categories = ref<any[]>([])

const state = reactive({
  title: '',
  slug: '',
  category_id: null,
  excerpt: '',
  content_raw: '',
  is_published: false
})

const loadCategories = async () => {
  try {
    const response = await $fetch<any>(`${backendUrl}/api/admin/blog/categories`)
    categories.value = response.data?.data || response.data || []
  } catch (error) {
    console.error('Помилка завантаження категорій:', error)
  }
}

const onSubmit = async () => {
  pending.value = true
  try {
    await $fetch(`${backendUrl}/api/admin/blog/posts`, {
      method: 'POST',
      body: state
    })
    navigateTo('/admin/blog/posts/BlogPostsUi')
  } catch (error) {
    console.error('Помилка при збереженні поста:', error)
    alert('Не вдалося зберегти пост.')
  } finally {
    pending.value = false
  }
}

onMounted(() => {
  loadCategories()
})
</script>