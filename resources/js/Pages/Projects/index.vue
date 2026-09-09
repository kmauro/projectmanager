<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { NativeSelect, NativeSelectOption } from '@/Components/ui/native-select'
import { Link, router } from '@inertiajs/vue3'
import { ChevronLeft, ChevronRight, Search, X } from 'lucide-vue-next'
import { computed, reactive } from 'vue'

defineOptions({
  layout: AppLayout,
})

const props = defineProps({
  title: String,
  projects: Object,
  filters: Object,
})

const searchForm = reactive({
  search: props.filters?.search ?? '',
  field: props.filters?.field ?? 'title',
  per_page: props.filters?.per_page ?? 25,
})

const numberedLinks = computed(() => (
  props.projects.links?.filter((link) => /^\d+$/.test(link.label)) ?? []
))

function search() {
  router.get('/projects', searchForm, {
    preserveScroll: true,
    preserveState: true,
    replace: true,
  })
}

function clearSearch() {
  searchForm.search = ''
  searchForm.field = 'title'
  search()
}

function openProject(projectId) {
  router.get(`/project/${projectId}`)
}
</script>

<template>
  <div class="space-y-6">
    <section class="rounded-xl border border-border bg-card p-4 shadow-sm sm:p-6">
      <form class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_220px_120px_auto] lg:items-end" @submit.prevent="search">
        <div class="space-y-2">
          <Label for="project-search">Buscar proyectos</Label>
          <Input
            id="project-search"
            v-model="searchForm.search"
            type="search"
            placeholder="Escribe un término de búsqueda"
          />
        </div>

        <div class="space-y-2">
          <Label for="search-field">Buscar por</Label>
          <NativeSelect id="search-field" v-model="searchForm.field">
            <NativeSelectOption value="title">Título</NativeSelectOption>
            <NativeSelectOption value="applicant">Solicitante</NativeSelectOption>
            <NativeSelectOption value="trl">Nivel TRL</NativeSelectOption>
            <NativeSelectOption value="province">Provincia</NativeSelectOption>
          </NativeSelect>
        </div>

        <div class="space-y-2">
          <Label for="per-page">Por página</Label>
          <NativeSelect id="per-page" v-model="searchForm.per_page">
            <NativeSelectOption :value="10">10</NativeSelectOption>
            <NativeSelectOption :value="25">25</NativeSelectOption>
            <NativeSelectOption :value="50">50</NativeSelectOption>
          </NativeSelect>
        </div>

        <div class="flex gap-2">
          <Button type="submit" class="flex-1 lg:flex-none">
            <Search class="size-4" />
            Buscar
          </Button>
          <Button v-if="searchForm.search" type="button" variant="outline" size="icon" aria-label="Limpiar búsqueda" @click="clearSearch">
            <X class="size-4" />
          </Button>
        </div>
      </form>
    </section>

    <section class="overflow-hidden rounded-xl border border-border bg-card shadow-sm">
      <div class="flex flex-col gap-1 border-b border-border px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
          <h2 class="font-semibold">Proyectos</h2>
          <p class="text-sm text-muted-foreground">
            {{ projects.total }} {{ projects.total === 1 ? 'resultado' : 'resultados' }}
          </p>
        </div>
        <p v-if="projects.total" class="text-sm text-muted-foreground">
          Mostrando {{ projects.from }}–{{ projects.to }}
        </p>
      </div>

      <div v-if="projects.data?.length" class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-muted/60 text-xs uppercase tracking-wide text-muted-foreground">
            <tr>
              <th class="px-4 py-3 font-medium sm:px-6">SID</th>
              <th class="min-w-72 px-4 py-3 font-medium sm:px-6">Título</th>
              <th class="px-4 py-3 font-medium sm:px-6">Solicitante</th>
              <th class="px-4 py-3 font-medium sm:px-6">TRL</th>
              <th class="px-4 py-3 font-medium sm:px-6">Provincia</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">
            <tr
              v-for="project in projects.data"
              :key="project.id"
              role="link"
              tabindex="0"
              :aria-label="`Ver detalles de ${project.title || `proyecto ${project.id}`}`"
              class="cursor-pointer transition hover:bg-muted/30 focus-visible:bg-muted/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-ring"
              @click="openProject(project.id)"
              @keydown.enter="openProject(project.id)"
            >
              <td class="whitespace-nowrap px-4 py-4 font-mono text-xs text-muted-foreground sm:px-6">
                {{ project.SID ?? '—' }}
              </td>
              <td class="px-4 py-4 font-medium sm:px-6">{{ project.title || 'Sin título' }}</td>
              <td class="whitespace-nowrap px-4 py-4 text-muted-foreground sm:px-6">
                {{ project.applicant?.full_name || '—' }}
              </td>
              <td class="whitespace-nowrap px-4 py-4 text-muted-foreground sm:px-6">
                {{ project.trl_level?.trl_level.slice(0, 5) || '—' }}
              </td>
              <td class="whitespace-nowrap px-4 py-4 text-muted-foreground sm:px-6">
                {{ project.province?.province || '—' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="grid place-items-center gap-2 px-6 py-16 text-center">
        <Search class="size-10 text-muted-foreground/60" />
        <h3 class="font-medium">No se encontraron proyectos</h3>
        <p class="max-w-md text-sm text-muted-foreground">Prueba con otro término o limpia la búsqueda actual.</p>
        <Button v-if="searchForm.search" type="button" variant="outline" class="mt-2" @click="clearSearch">
          Limpiar búsqueda
        </Button>
      </div>

      <nav v-if="projects.last_page > 1" class="flex items-center justify-between gap-3 border-t border-border px-4 py-4 sm:px-6" aria-label="Paginación">
        <Button as-child variant="outline" size="sm" :disabled="!projects.prev_page_url">
          <Link v-if="projects.prev_page_url" :href="projects.prev_page_url" preserve-scroll>
            <ChevronLeft class="size-4" />
            Anterior
          </Link>
          <span v-else>
            <ChevronLeft class="size-4" />
            Anterior
          </span>
        </Button>

        <div class="hidden items-center gap-1 sm:flex">
          <Button
            v-for="link in numberedLinks"
            :key="link.label"
            as-child
            :variant="link.active ? 'default' : 'outline'"
            size="icon-sm"
          >
            <Link :href="link.url" preserve-scroll>{{ link.label }}</Link>
          </Button>
        </div>

        <span class="text-sm text-muted-foreground sm:hidden">Página {{ projects.current_page }} de {{ projects.last_page }}</span>

        <Button as-child variant="outline" size="sm" :disabled="!projects.next_page_url">
          <Link v-if="projects.next_page_url" :href="projects.next_page_url" preserve-scroll>
            Siguiente
            <ChevronRight class="size-4" />
          </Link>
          <span v-else>
            Siguiente
            <ChevronRight class="size-4" />
          </span>
        </Button>
      </nav>
    </section>
  </div>
</template>
