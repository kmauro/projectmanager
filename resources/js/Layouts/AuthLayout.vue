<script setup>
import Footer from '@/Components/Footer.vue'
import NotificationToast from '@/Components/NotificationToast.vue'
import ThemeToggle from '@/Components/ThemeToggle.vue'
import Header from '@/Components/ui/Header.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  title: String,
  subtitle: String,
  actionText: String,
  actionLabel: String,
  actionHref: String,
})
</script>

<template>
  <Head :title="title" />
  <div class="flex min-h-dvh flex-col bg-muted/30 text-foreground">
    <Header />

    <main class="relative flex flex-1 items-center justify-center px-4 py-10 sm:px-6">
      <div class="absolute right-4 top-4 sm:right-6 sm:top-6">
        <ThemeToggle />
      </div>

      <div class="w-full max-w-md rounded-xl border border-border bg-card p-6 text-card-foreground shadow-sm sm:p-8">
        <div class="mb-6 space-y-2">
          <h1 class="text-2xl font-semibold tracking-tight">{{ title }}</h1>
          <p class="text-sm text-muted-foreground">{{ subtitle }}</p>
        </div>

        <slot />

        <p class="mt-6 text-center text-sm text-muted-foreground">
          {{ actionText }}
          <Link :href="actionHref" class="font-medium text-primary underline-offset-4 hover:underline">
            {{ actionLabel }}
          </Link>
        </p>
      </div>
    </main>

    <Footer :is-auth="true" />
    <NotificationToast />
  </div>
</template>
