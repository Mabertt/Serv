<template>
  <div class="max-w-2xl p-6">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Створення категорії</h1>

    <UForm :schema="schema" :state="state" class="space-y-6" @submit="onSubmit">
      
      <UFormField label="Назва категорії" name="title" hint="Обов'язкове поле">
        <UInput 
          v-model="state.title" 
          placeholder="Наприклад: JavaScript"
          icon="i-heroicons-tag-20-solid"
          class="w-full"
        />
      </UFormField>

      <UFormField label="Slug" name="slug" hint="Залишіть порожнім для автогенерації">
        <UInput 
          v-model="state.slug" 
          placeholder="javascript"
          icon="i-heroicons-code-bracket-20-solid"
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

      <div class="flex gap-4 pt-6 border-t">
        <UButton type="submit" color="primary" size="lg" :loading="isSubmitting" icon="i-heroicons-check-20-solid">
          Зберегти
        </UButton>
        <UButton to="/admin/blog/categories" color="neutral" variant="ghost" size="lg" icon="i-heroicons-arrow-left-20-solid">
          Скасувати
        </UButton>
      </div>

    </UForm>
  </div>
</template>

<script setup lang="ts">
import { z } from 'zod'
import { ref, reactive } from 'vue'

const router = useRouter()
const isSubmitting = ref(false)

const backendUrl = 'https://symmetrical-space-guacamole-r4x6r5jjvwvq3r7v-8080.app.github.dev'

definePageMeta({
  layout: 'admin'
})

const schema = z.object({
  title: z.string().min(3, 'Назва має містити мінімум 3 символи'),
  slug: z.string().optional(),
  description: z.string().optional()
})

const state = reactive({
  title: '',
  slug: '',
  description: ''
})

const onSubmit = async () => {
  isSubmitting.value = true
  try {
    const response = await $fetch<any>(`${backendUrl}/api/admin/blog/categories`, {
      method: 'POST',
      body: state
    })
    
    if (response && (response.success || response.id || response.data)) {
      router.push('/admin/blog/categories')
    }
  } catch (error: any) {
    console.error('Помилка збереження:', error)
    const serverMessage = error.data?.message || 'Не вдалося зберегти категорію';
    alert(`Помилка сервера: ${serverMessage}`)
  } finally {
    isSubmitting.value = false
  }
}
</script>