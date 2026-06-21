<template>
  <div class="p-8">
    <nav class="flex gap-4 mb-8">
      <NuxtLink 
        to="/admin/blog/categories" 
        class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700"
      >
        Категорії
      </NuxtLink>
      
      <NuxtLink 
        to="/admin/blog/posts/BlogPostsUi" 
        class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
      >
        Пости
      </NuxtLink>
    </nav>

    <main>
      <slot />
    </main>
  </div>
</template>