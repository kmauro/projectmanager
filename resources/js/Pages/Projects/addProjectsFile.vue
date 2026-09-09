<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import { Label } from '@/Components/ui/label'
import { useForm } from '@inertiajs/vue3'
import { FileSpreadsheet, Upload } from 'lucide-vue-next'
import { ref } from 'vue'

defineOptions({
  layout: AppLayout,
})

defineProps({
  title: String,
})

const fileInput = ref(null)
const form = useForm({
  file: null,
})

function selectFile(event) {
  form.file = event.target.files?.[0] ?? null
  form.clearErrors('file')
}

function reset() {
  form.reset('file')

  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

function submit() {
  form.post('/file/add', {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: reset,
  })
}
</script>

<template>
  <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(280px,1fr)]">
    <section class="rounded-xl border border-border bg-card p-5 shadow-sm sm:p-6">
      <div class="mb-6 flex items-start gap-4">
        <div class="grid size-11 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
          <FileSpreadsheet class="size-6" />
        </div>
        <div>
          <h1 class="font-semibold">Importar proyectos desde CSV</h1>
          <p class="mt-1 text-sm text-muted-foreground">Selecciona un archivo de hasta 10 MB con la estructura esperada por el sistema.</p>
        </div>
      </div>

      <form class="space-y-5" @submit.prevent="submit">
        <div class="space-y-2">
          <Label for="project-file">Archivo CSV</Label>
          <input
            id="project-file"
            ref="fileInput"
            type="file"
            accept=".csv,text/csv,text/plain"
            class="flex h-11 w-full cursor-pointer rounded-md border border-input bg-background px-3 py-2 text-sm shadow-xs file:mr-4 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1 file:text-sm file:font-medium file:text-primary hover:file:bg-primary/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
            :aria-invalid="Boolean(form.errors.file)"
            @change="selectFile"
          />
          <p v-if="form.file" class="text-sm text-muted-foreground">Seleccionado: {{ form.file.name }}</p>
          <p v-if="form.errors.file" class="text-sm text-destructive">{{ form.errors.file }}</p>
        </div>

        <Button type="submit" :disabled="!form.file || form.processing">
          <Upload class="size-4" />
          {{ form.processing ? 'Importando...' : 'Importar archivo' }}
        </Button>
      </form>
    </section>

    <aside class="rounded-xl border border-border bg-muted/30 p-5">
      <h2 class="font-semibold">Antes de importar</h2>
      <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-muted-foreground">
        <li>Conserva los encabezados del archivo original.</li>
        <li>No cambies el orden de las columnas.</li>
        <li>Evita abrir y volver a guardar el CSV con una codificación distinta.</li>
      </ul>
    </aside>
  </div>
</template>
