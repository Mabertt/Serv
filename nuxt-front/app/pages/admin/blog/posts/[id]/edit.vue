<template>
  <div class="container mx-auto p-6 max-w-2xl">
    <div v-if="error" class="p-4 mb-4 bg-red-100 border border-red-400 text-red-700 rounded">
      <p class="font-bold">Помилка завантаження даних:</p>
      <p>{{ error.message }}</p>
      <p class="text-sm mt-2">Перевірте консоль (F12) та статус сервера на порту 8080.</p>
    </div>

    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold">Редагування поста</h1>
      <UButton to="/admin/blog/posts" color="neutral" variant="ghost" icon="i-heroicons-arrow-left-20-solid">
        Назад
      </UButton>
    </div>

    <div v-if="pending" class="flex justify-center items-center h-40">
      <UIcon name="i-heroicons-arrow-path-20-solid" class="animate-spin text-2xl" />
    </div>

    <UForm v-else-if="data" :state="state" class="space-y-6" @submit="onSubmit">
      
      <UFormField label="Заголовок" name="title" required>
        <UInput v-model="state.title" class="w-full" />
      </UFormField>

      <UFormField label="Slug" name="slug" required>
        <UInput v-model="state.slug" class="w-full" />
      </UFormField>

      <UFormField label="Категорія" name="category_id" required>
        <USelect 
          v-model="state.category_id" 
          :items="categories" 
          label-key="title" 
          value-key="id"
          class="w-full"
        />
      </UFormField>

      <UFormField label="Короткий опис" name="excerpt">
        <UTextarea v-model="state.excerpt" :rows="3" class="w-full" />
      </UFormField>

      <UFormField label="Контент" name="content_raw" required>
        <UTextarea v-model="state.content_raw" :rows="10" class="w-full" />
      </UFormField>

      <UFormField name="is_published">
        <UCheckbox v-model="state.is_published" label="Опубліковано" />
      </UFormField>

      <div class="flex gap-4 pt-4">
        <UButton type="submit" color="primary" :loading="isSubmitting" icon="i-heroicons-check-20-solid">
          Зберегти зміни
        </UButton>
        <UButton to="/admin/blog/posts" color="neutral" variant="ghost" icon="i-heroicons-x-mark-20-solid">
          Скасувати
        </UButton>
      </div>
    </UForm>
  </div>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()
const backendUrl = config.public.apiBase || 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-8080.app.github.dev'
const route = useRoute()
const router = useRouter()
const id = route.params.id

const isSubmitting = ref(false)
const categories = ref<any[]>([])

definePageMeta({ layout: 'admin' })

// Використовуємо lazy: true, щоб сторінка не зависала при завантаженні
const { data, pending, error } = await useFetch<any>(`${backendUrl}/api/admin/blog/posts/${id}`, {
  lazy: true,
  key: `post-edit-${id}`
})

const state = reactive({
  title: '',
  slug: '',
  category_id: null,
  excerpt: '',
  content_raw: '',
  is_published: false
})

// Спостерігач за даними
watch(data, (newData) => {
  if (newData?.data) {
    const p = newData.data
    state.title = p.title || ''
    state.slug = p.slug || ''
    state.category_id = p.category_id || null
    state.excerpt = p.excerpt || ''
    state.content_raw = p.content_raw || ''
    state.is_published = !!p.published_at
  }
}, { immediate: true })

const loadCategories = async () => {
  try {
    const res = await $fetch<any>(`${backendUrl}/api/admin/blog/categories`)
    categories.value = res.data?.data || res.data || []
  } catch (e) {
    console.error('Помилка завантаження категорій', e)
  }
}

const onSubmit = async () => {
  isSubmitting.value = true
  try {
    await $fetch(`${backendUrl}/api/admin/blog/posts/${id}`, {
      method: 'PUT',
      body: state
    })
    router.push('/admin/blog/posts')
  } catch (error) {
    console.error('Помилка збереження:', error)
  } finally {
    isSubmitting.value = false
  }
}

onMounted(loadCategories)
</script>