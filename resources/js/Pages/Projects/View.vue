<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'
import { Textarea } from '@/Components/ui/textarea'
import { Link, router, useForm } from '@inertiajs/vue3'
import { ArrowLeft, CircleX, Edit3, Save, Trash2 } from 'lucide-vue-next'
import { ref } from 'vue'

defineOptions({
  layout: AppLayout,
})

const props = defineProps({
  title: String,
  project: Object,
  trlLevels: Array,
  provinces: Array,
  areas: Array,
})

const isEditing = ref(false)
const isDeleting = ref(false)

const form = useForm({
  title: props.project.title ?? '',
  start_date: props.project.start_date ?? '',
  end_date: props.project.end_date ?? '',
  objective: props.project.objective ?? '',
  innovation_value: props.project.innovation_value ?? '',
  trl_level_id: props.project.trl_level_id ?? '',
  area_id: props.project.area_id ?? '',
  located_entity: props.project.located_entity ?? '',
  province_id: props.project.province_id ?? '',
  EBCT_amount: props.project.EBCT_amount ?? '',
  countries: props.project.countries ?? '',
})

function cancelEditing() {
  form.reset()
  form.clearErrors()
  isEditing.value = false
}

function submit() {
  form
    .transform((data) => ({
      ...data,
      start_date: data.start_date || null,
      end_date: data.end_date || null,
      trl_level_id: data.trl_level_id || null,
      area_id: data.area_id || null,
      province_id: data.province_id || null,
      located_entity: data.located_entity || null,
      EBCT_amount: data.EBCT_amount || null,
      countries: data.countries || null,
    }))
    .put(`/project/${props.project.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        form.defaults()
        isEditing.value = false
      },
    })
}

function deleteProject() {
  const projectName = props.project.title || `#${props.project.id}`

  if (!window.confirm(`¿Eliminar definitivamente el proyecto "${projectName}"? Esta acción no se puede deshacer.`)) {
    return
  }

  router.delete(`/project/${props.project.id}`, {
    preserveScroll: true,
    onStart: () => {
      isDeleting.value = true
    },
    onFinish: () => {
      isDeleting.value = false
    },
  })
}

function formatDateTime(value) {
  if (!value) {
    return 'Sin registrar'
  }

  return new Intl.DateTimeFormat('es-AR', {
    dateStyle: 'medium',
    timeStyle: 'short',
  }).format(new Date(value))
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <Button as-child variant="ghost" class="w-fit px-0 hover:bg-transparent hover:text-primary">
        <Link href="/projects">
          <ArrowLeft class="size-4" />
          Volver a proyectos
        </Link>
      </Button>

      <div class="flex gap-2">
        <Button v-if="!isEditing" type="button" variant="outline" @click="isEditing = true">
          <Edit3 class="size-4" />
          Modificar o eliminar
        </Button>
        <Button v-else type="button" variant="outline" @click="cancelEditing">
          <CircleX class="size-4" />
          Cancelar
        </Button>
      </div>
    </div>

    <section class="rounded-xl border border-border bg-card p-4 shadow-sm sm:p-6">
      <div class="mb-6 flex flex-col gap-2 border-b border-border pb-5 sm:flex-row sm:items-start sm:justify-between">
        <div class="space-y-1">
          <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Proyecto {{ project.SID ? `SID ${project.SID}` : `#${project.id}` }}</p>
          <h1 class="text-2xl font-semibold tracking-tight">{{ project.title || 'Sin título' }}</h1>
        </div>
        <span class="w-fit rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary">
          {{ project.trl_level?.trl_level || 'TRL sin definir' }}
        </span>
      </div>

      <form class="space-y-6" @submit.prevent="submit">
        <div class="grid gap-5 md:grid-cols-2">
          <div class="space-y-2 md:col-span-2">
            <Label for="title">Título</Label>
            <Input id="title" v-model="form.title" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.title)" />
            <p v-if="form.errors.title" class="text-sm text-destructive">{{ form.errors.title }}</p>
          </div>

          <div class="space-y-2">
            <Label for="start_date">Fecha de inicio</Label>
            <Input id="start_date" v-model="form.start_date" type="date" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.start_date)" />
            <p v-if="form.errors.start_date" class="text-sm text-destructive">{{ form.errors.start_date }}</p>
          </div>

          <div class="space-y-2">
            <Label for="end_date">Fecha de fin</Label>
            <Input id="end_date" v-model="form.end_date" type="date" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.end_date)" />
            <p v-if="form.errors.end_date" class="text-sm text-destructive">{{ form.errors.end_date }}</p>
          </div>

          <div class="space-y-2 md:col-span-2">
            <Label for="objective">Objetivo del proyecto</Label>
            <Textarea id="objective" v-model="form.objective" rows="4" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.objective)" />
            <p v-if="form.errors.objective" class="text-sm text-destructive">{{ form.errors.objective }}</p>
          </div>

          <div class="space-y-2 md:col-span-2">
            <Label for="innovation_value">Diferencia innovativa</Label>
            <Textarea id="innovation_value" v-model="form.innovation_value" rows="4" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.innovation_value)" />
            <p v-if="form.errors.innovation_value" class="text-sm text-destructive">{{ form.errors.innovation_value }}</p>
          </div>

          <div class="space-y-2">
            <Label for="trl_level_id">Nivel TRL</Label>
            <Select v-model="form.trl_level_id" :disabled="!isEditing">
              <SelectTrigger id="trl_level_id" class="w-full" :aria-invalid="Boolean(form.errors.trl_level_id)">
                <SelectValue placeholder="Selecciona un nivel" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem v-for="trlLevel in trlLevels" :key="trlLevel.id" :value="trlLevel.id">
                  {{ trlLevel.trl_level }}
                </SelectItem>
              </SelectContent>
            </Select>
            <p v-if="form.errors.trl_level_id" class="text-sm text-destructive">{{ form.errors.trl_level_id }}</p>
          </div>

          <div class="space-y-2">
            <Label for="area_id">Vertical</Label>
            <Select v-model="form.area_id" :disabled="!isEditing">
              <SelectTrigger id="area_id" class="w-full" :aria-invalid="Boolean(form.errors.area_id)">
                <SelectValue placeholder="Selecciona una vertical" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem v-for="area in areas" :key="area.id" :value="area.id">
                  {{ area.area }}
                </SelectItem>
              </SelectContent>
            </Select>
            <p v-if="form.errors.area_id" class="text-sm text-destructive">{{ form.errors.area_id }}</p>
          </div>

          <div class="space-y-2">
            <Label for="located_entity">Entidad donde se encuentra radicado</Label>
            <Input id="located_entity" v-model="form.located_entity" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.located_entity)" />
            <p v-if="form.errors.located_entity" class="text-sm text-destructive">{{ form.errors.located_entity }}</p>
          </div>

          <div class="space-y-2">
            <Label for="province_id">Provincia</Label>
            <Select v-model="form.province_id" :disabled="!isEditing">
              <SelectTrigger id="province_id" class="w-full" :aria-invalid="Boolean(form.errors.province_id)">
                <SelectValue placeholder="Selecciona una provincia" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem v-for="province in provinces" :key="province.id" :value="province.id">
                  {{ province.province }}
                </SelectItem>
              </SelectContent>
            </Select>
            <p v-if="form.errors.province_id" class="text-sm text-destructive">{{ form.errors.province_id }}</p>
          </div>

          <div class="space-y-2 md:col-span-2">
            <Label for="EBCT_amount">Monto de financiamiento EBCT/Startup</Label>
            <Input id="EBCT_amount" v-model="form.EBCT_amount" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.EBCT_amount)" />
            <p v-if="form.errors.EBCT_amount" class="text-sm text-destructive">{{ form.errors.EBCT_amount }}</p>
          </div>

          <div class="space-y-2 md:col-span-2">
            <Label for="countries">Países relacionados</Label>
            <Input id="countries" v-model="form.countries" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.countries)" />
            <p v-if="form.errors.countries" class="text-sm text-destructive">{{ form.errors.countries }}</p>
          </div>
        </div>

        <div v-if="isEditing" class="flex flex-col-reverse justify-between gap-3 border-t border-border pt-5 sm:flex-row">
          <Button type="button" variant="destructive" :disabled="form.processing || isDeleting" @click="deleteProject">
            <Trash2 class="size-4" />
            {{ isDeleting ? 'Eliminando...' : 'Eliminar proyecto' }}
          </Button>
          <Button type="submit" :disabled="form.processing || isDeleting">
            <Save class="size-4" />
            {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
          </Button>
        </div>
      </form>
    </section>

    <section class="grid gap-4 rounded-xl border border-border bg-card p-4 shadow-sm sm:grid-cols-2 sm:p-6 lg:grid-cols-4">
      <div>
        <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Solicitante</p>
        <p class="mt-1 font-medium">{{ project.applicant?.full_name || 'Sin definir' }}</p>
      </div>
      <div>
        <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Correo</p>
        <p class="mt-1 font-medium">{{ project.applicant?.email || 'Sin definir' }}</p>
      </div>
      <div>
        <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Teléfono</p>
        <p class="mt-1 font-medium">{{ project.applicant?.phone || 'Sin definir' }}</p>
      </div>
      <div>
        <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Provincia</p>
        <p class="mt-1 font-medium">{{ project.province?.province || 'Sin definir' }}</p>
      </div>
    </section>

    <section class="rounded-xl border border-border bg-card p-4 shadow-sm sm:p-6">
      <h2 class="font-semibold">Clasificación y alcance</h2>
      <div class="mt-4 grid gap-5 md:grid-cols-2">
        <div>
          <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Tecnologías</p>
          <div v-if="project.techs?.length" class="mt-2 flex flex-wrap gap-2">
            <span v-for="tech in project.techs" :key="tech.id" class="rounded-full bg-muted px-3 py-1 text-sm">{{ tech.name }}</span>
          </div>
          <p v-else class="mt-1 text-sm text-muted-foreground">Sin definir</p>
        </div>
        <div>
          <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Necesidades</p>
          <div v-if="project.needs?.length" class="mt-2 flex flex-wrap gap-2">
            <span v-for="need in project.needs" :key="need.id" class="rounded-full bg-muted px-3 py-1 text-sm">{{ need.name }}</span>
          </div>
          <p v-else class="mt-1 text-sm text-muted-foreground">Sin definir</p>
        </div>
        <div>
          <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Sectores</p>
          <div v-if="project.sectors?.length" class="mt-2 flex flex-wrap gap-2">
            <span v-for="sector in project.sectors" :key="sector.id" class="rounded-full bg-muted px-3 py-1 text-sm">{{ sector.name }}</span>
          </div>
          <p v-else class="mt-1 text-sm text-muted-foreground">Sin definir</p>
        </div>
        <div>
          <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Fuentes de financiamiento</p>
          <div v-if="project.funding_sources?.length" class="mt-2 flex flex-wrap gap-2">
            <span v-for="source in project.funding_sources" :key="source.id" class="rounded-full bg-muted px-3 py-1 text-sm">
              {{ source.name }}<template v-if="source.pivot?.percentage != null"> · {{ source.pivot.percentage }}%</template>
            </span>
          </div>
          <p v-else class="mt-1 text-sm text-muted-foreground">Sin definir</p>
        </div>
      </div>
    </section>

    <section class="rounded-xl border border-border bg-card p-4 shadow-sm sm:p-6">
      <h2 class="font-semibold">Registro del proyecto</h2>
      <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div>
          <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Enviado</p>
          <p class="mt-1 font-medium">{{ formatDateTime(project.time_sent) }}</p>
        </div>
        <div>
          <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Completado</p>
          <p class="mt-1 font-medium">{{ formatDateTime(project.time_completed) }}</p>
        </div>
        <div>
          <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Última modificación</p>
          <p class="mt-1 font-medium">{{ formatDateTime(project.time_modified) }}</p>
        </div>
      </div>
    </section>
  </div>
</template>
