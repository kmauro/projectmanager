<script setup>
import ThemeToggle from '@/Components/ThemeToggle.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { MoonStar } from 'lucide-vue-next'

defineOptions({
  layout: AppLayout,
})

defineProps({
  title: String,
})
</script>

<template>
  <section class="rounded-xl border border-border bg-card p-5 shadow-sm sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex items-start gap-3">
        <div class="grid size-10 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
          <MoonStar class="size-5" />
        </div>
        <div>
          <h2 class="font-semibold">Apariencia</h2>
          <p class="mt-1 text-sm text-muted-foreground">Alterna entre el tema claro y oscuro del sistema.</p>
        </div>
      </div>
      <ThemeToggle />
    </div>
  </section>
</template>
