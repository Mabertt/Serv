<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans antialiased">
    <UNotifications />
    
    <header class="bg-slate-900/80 backdrop-blur border-b border-slate-800 sticky top-0 z-50">
      <div class="container mx-auto px-4 h-16 flex items-center justify-between">
        <div class="flex items-center gap-6">
          <span class="text-lg font-bold text-emerald-400 flex items-center gap-2 tracking-tight">
            <UIcon name="i-heroicons-rectangle-group" class="w-5 h-5 text-emerald-500" /> 
            Панель керування
          </span>
          <nav class="flex items-center gap-1">
            <UButton to="/admin/blog/categories" variant="ghost" color="neutral" icon="i-heroicons-folder" class="text-slate-300 hover:text-white">
              Категорії
            </UButton>
            <UButton to="/admin/blog/posts/BlogPostsUi" variant="ghost" color="neutral" icon="i-heroicons-document-text" class="text-slate-300 hover:text-white">
              Пости
            </UButton>
          </nav>
        </div>
      </div>
    </header>

    <main>
      <NuxtPage />
    </main>
  </div>
</template>