<template>
  <div class="min-h-[calc(100vh-64px)] flex flex-col items-center justify-center p-4 relative overflow-hidden bg-slate-950">
    
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-16 text-center tracking-tight z-10">
      Управління блогом
    </h1>

    <div class="flex flex-col sm:flex-row gap-6 z-10 w-full max-w-md sm:max-w-none justify-center">
      <UButton 
        to="/admin/blog/posts/BlogPostsUi" 
        color="primary" 
        size="xl" 
        icon="i-heroicons-document-text-20-solid"
        class="justify-center px-10 py-5 text-lg font-semibold transition-all duration-200 hover:scale-105 shadow-lg shadow-emerald-950/50"
      >
        Blog Posts
      </UButton>

      <UButton 
        to="/admin/blog/categories" 
        color="primary" 
        size="xl" 
        variant="solid"
        icon="i-heroicons-tag-20-solid"
        class="justify-center px-10 py-5 text-lg font-semibold transition-all duration-200 hover:scale-105 shadow-lg shadow-emerald-950/50"
      >
        Category Post
      </UButton>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: false 
})
</script>