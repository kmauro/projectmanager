<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { BriefcaseBusinessIcon, Plus } from 'lucide-vue-next'

defineProps({
  title: String,
})

defineOptions({
  layout: AppLayout,
})

const page = usePage()
const user = computed(() => page.props.auth?.user)
const firstName = computed(() => user.value?.name.split(' ')[0] || 'Usuario')
</script>

<template>
  <div class="space-y-6">
    <section class="rounded-lg border border-border bg-card p-6 shadow-sm">
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="space-y-2">
          <p class="text-sm font-medium text-muted-foreground">Project Manager</p>
          <h1 class="text-2xl font-semibold">Bienvenido, {{ firstName }} {{ user?.surname }}</h1>
          <p class="max-w-2xl text-sm text-muted-foreground">
            Tu sesión está activa. Desde aquí puedes consultar proyectos o importar nuevos registros.
          </p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row">
          <Button as-child variant="default">
            <Link view-transition href="/projects">
              <BriefcaseBusinessIcon class="size-4" />
              Proyectos
            </Link>
          </Button>
          <Button as-child variant="outline">
            <Link view-transition href="/file/add">
              <Plus class="size-4" />
              Importar
            </Link>
          </Button>
        </div>
      </div>
    </section>
  </div>
</template>
