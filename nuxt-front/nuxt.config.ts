// nuxt.config.ts
export default defineNuxtConfig({
  future: {
    compatibilityVersion: 4,
  },

  modules: [
    '@nuxt/ui'
  ],

  // ДОДАЙ ЦЕЙ РЯДОК: Підключаємо файл, який запустить Tailwind та Nuxt UI
  css: ['~/assets/css/main.css'],

  compatibilityDate: '2026-06-20',
  devtools: { enabled: true }
})