<template>
  <div class="container mx-auto p-6 max-w-2xl">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold">Редагування категорії</h1>
      <UButton to="/admin/blog/categories" color="neutral" variant="ghost" icon="i-heroicons-arrow-left-20-solid">
        Назад
      </UButton>
    </div>

    <div v-if="pending" class="flex justify-center items-center h-40">
      <UIcon name="i-heroicons-arrow-path-20-solid" class="animate-spin text-2xl" />
    </div>

    <UForm v-else :schema="schema" :state="state" class="space-y-6" @submit="onSubmit">
      
      <UFormField label="Назва категорії" name="title" required>
        <UInput 
          v-model="state.title" 
          placeholder="Наприклад: JavaScript"
          class="w-full"
        />
      </UFormField>

      <UFormField label="Slug" name="slug">
        <UInput 
          v-model="state.slug" 
          placeholder="javascript"
          class="w-full"
        />
      </UFormField>

      <UFormField label="Опис" name="description">
        <UTextarea 
          v-model="state.description" 
          placeholder="Опис категорії..."
          :rows="4"
          class="w-full"
        />
      </UFormField>

      <div class="flex gap-4 pt-4">
        <UButton type="submit" color="primary" :loading="isSubmitting" icon="i-heroicons-check-20-solid">
          Зберегти зміни
        </UButton>
        <UButton to="/admin/blog/categories" color="neutral" variant="ghost" icon="i-heroicons-x-mark-20-solid">
          Скасувати
        </UButton>
      </div>

    </UForm>
  </div>
</template>

<script setup lang="ts">
import { z } from 'zod'
import { ref, reactive } from 'vue'

const config = useRuntimeConfig()
const backendUrl = config.public.apiBase || 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-8080.app.github.dev'
const route = useRoute()
const router = useRouter()
const id = route.params.id
const isSubmitting = ref(false)

definePageMeta({ layout: 'admin' })

const schema = z.object({
  title: z.string().min(3, 'Назва має містити мінімум 3 символи'),
  slug: z.string().optional(),
  description: z.string().optional()
})

const { data, pending } = await useFetch<any>(`${backendUrl}/api/admin/blog/categories/${id}`)

const state = reactive({
  title: data.value?.data?.title || '',
  slug: data.value?.data?.slug || '',
  description: data.value?.data?.description || ''
})

const onSubmit = async () => {
  isSubmitting.value = true
  try {
    await $fetch(`${backendUrl}/api/admin/blog/categories/${id}`, {
      method: 'PUT',
      body: state
    })
    router.push('/admin/blog/categories')
  } catch (error) {
    console.error('Помилка збереження:', error)
  } finally {
    isSubmitting.value = false
  }
}
</script>