<script setup>
import ChangePassword from './ChangePassword.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { useForm } from '@inertiajs/vue3'
import { CircleX, Edit3, Save } from 'lucide-vue-next'
import { ref } from 'vue'

defineOptions({
  layout: AppLayout,
})

const props = defineProps({
  title: String,
  user: Object,
  isCurrentUser: Boolean,
})

const isEditing = ref(false)
const form = useForm({
  name: props.user.name ?? '',
  surname: props.user.surname ?? '',
  email: props.user.email ?? '',
  phone: props.user.phone ?? '',
})

function cancelEditing() {
  form.reset()
  form.clearErrors()
  isEditing.value = false
}

function updateProfile() {
  form.post('/profile', {
    preserveScroll: true,
    onSuccess: () => {
      isEditing.value = false
    },
  })
}
</script>

<template>
  <div class="space-y-6">
    <section class="rounded-xl border border-border bg-card p-5 shadow-sm sm:p-6">
      <div class="mb-6 flex items-start justify-between gap-4 border-b border-border pb-5">
        <div>
          <h2 class="font-semibold">Información del perfil</h2>
          <p class="mt-1 text-sm text-muted-foreground">Datos de identificación y contacto de la cuenta.</p>
        </div>
        <Button v-if="isCurrentUser && !isEditing" type="button" variant="outline" @click="isEditing = true">
          <Edit3 class="size-4" />
          Editar
        </Button>
        <Button v-else-if="isCurrentUser" type="button" variant="outline" @click="cancelEditing">
          <CircleX class="size-4" />
          Cancelar
        </Button>
      </div>

      <form class="space-y-5" @submit.prevent="updateProfile">
        <div class="grid gap-5 md:grid-cols-2">
          <div class="space-y-2">
            <Label for="username">Nombre de usuario</Label>
            <Input id="username" :model-value="user.username" disabled />
          </div>

          <div class="space-y-2">
            <Label for="role">Rol</Label>
            <Input id="role" :model-value="user.role?.role_name ?? 'Sin rol'" disabled />
          </div>

          <div class="space-y-2">
            <Label for="name">Nombre</Label>
            <Input id="name" v-model="form.name" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.name)" />
            <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
          </div>

          <div class="space-y-2">
            <Label for="surname">Apellido</Label>
            <Input id="surname" v-model="form.surname" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.surname)" />
            <p v-if="form.errors.surname" class="text-sm text-destructive">{{ form.errors.surname }}</p>
          </div>

          <div class="space-y-2">
            <Label for="email">Correo electrónico</Label>
            <Input id="email" v-model="form.email" type="email" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.email)" />
            <p v-if="form.errors.email" class="text-sm text-destructive">{{ form.errors.email }}</p>
          </div>

          <div class="space-y-2">
            <Label for="phone">Teléfono</Label>
            <Input id="phone" v-model="form.phone" type="tel" :disabled="!isEditing" :aria-invalid="Boolean(form.errors.phone)" />
            <p v-if="form.errors.phone" class="text-sm text-destructive">{{ form.errors.phone }}</p>
          </div>
        </div>

        <div v-if="isEditing" class="flex justify-end border-t border-border pt-5">
          <Button type="submit" :disabled="form.processing">
            <Save class="size-4" />
            {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
          </Button>
        </div>
      </form>
    </section>

    <ChangePassword v-if="isCurrentUser" />
  </div>
</template>
