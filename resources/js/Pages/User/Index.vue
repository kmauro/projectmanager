<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import { Link } from '@inertiajs/vue3'
import { Eye, Mail, ShieldCheck, Users } from 'lucide-vue-next'

defineOptions({
  layout: AppLayout,
})

defineProps({
  title: String,
  users: Array,
})
</script>

<template>
  <section class="overflow-hidden rounded-xl border border-border bg-card shadow-sm">
    <div class="flex items-center gap-3 border-b border-border px-5 py-4 sm:px-6">
      <Users class="size-5 text-primary" />
      <div>
        <h2 class="font-semibold">Usuarios registrados</h2>
        <p class="text-sm text-muted-foreground">{{ users.length }} {{ users.length === 1 ? 'cuenta' : 'cuentas' }}</p>
      </div>
    </div>

    <div class="divide-y divide-border">
      <article v-for="user in users" :key="user.id" class="flex flex-col gap-4 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div class="min-w-0 space-y-2">
          <h3 class="truncate font-semibold">{{ user.name }} {{ user.surname }}</h3>
          <div class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-muted-foreground">
            <span class="flex items-center gap-1.5"><Mail class="size-4" />{{ user.email }}</span>
            <span class="flex items-center gap-1.5"><ShieldCheck class="size-4" />{{ user.role?.role_name ?? 'Sin rol' }}</span>
          </div>
        </div>

        <Button as-child variant="outline" size="sm">
          <Link :href="`/users/${user.id}`">
            <Eye class="size-4" />
            Ver perfil
          </Link>
        </Button>
      </article>
    </div>
  </section>
</template>
