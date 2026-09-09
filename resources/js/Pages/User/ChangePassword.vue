<script setup>
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { useForm } from '@inertiajs/vue3'
import { CircleX, KeyRound, Save } from 'lucide-vue-next'
import { ref } from 'vue'

const changingPassword = ref(false)
const form = useForm({
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
}).dontRemember('current_password', 'new_password', 'new_password_confirmation')

function cancel() {
  form.reset()
  form.clearErrors()
  changingPassword.value = false
}

function updatePassword() {
  form.post('/profile/updatePassword', {
    preserveScroll: true,
    onSuccess: cancel,
    onFinish: () => form.reset('current_password', 'new_password', 'new_password_confirmation'),
  })
}
</script>

<template>
  <section class="rounded-xl border border-border bg-card p-5 shadow-sm sm:p-6">
    <div class="mb-6 flex items-start justify-between gap-4 border-b border-border pb-5">
      <div>
        <h2 class="font-semibold">Cambiar contraseña</h2>
        <p class="mt-1 text-sm text-muted-foreground">Confirma tu contraseña actual antes de establecer una nueva.</p>
      </div>
      <Button v-if="!changingPassword" type="button" variant="outline" @click="changingPassword = true">
        <KeyRound class="size-4" />
        Cambiar
      </Button>
      <Button v-else type="button" variant="outline" @click="cancel">
        <CircleX class="size-4" />
        Cancelar
      </Button>
    </div>

    <form v-if="changingPassword" class="space-y-5" @submit.prevent="updatePassword">
      <div class="space-y-2">
        <Label for="current_password">Contraseña actual</Label>
        <Input id="current_password" v-model="form.current_password" type="password" autocomplete="current-password" :aria-invalid="Boolean(form.errors.current_password)" />
        <p v-if="form.errors.current_password" class="text-sm text-destructive">{{ form.errors.current_password }}</p>
      </div>

      <div class="grid gap-5 md:grid-cols-2">
        <div class="space-y-2">
          <Label for="new_password">Nueva contraseña</Label>
          <Input id="new_password" v-model="form.new_password" type="password" autocomplete="new-password" :aria-invalid="Boolean(form.errors.new_password)" />
          <p v-if="form.errors.new_password" class="text-sm text-destructive">{{ form.errors.new_password }}</p>
        </div>

        <div class="space-y-2">
          <Label for="new_password_confirmation">Confirmar nueva contraseña</Label>
          <Input id="new_password_confirmation" v-model="form.new_password_confirmation" type="password" autocomplete="new-password" />
        </div>
      </div>

      <div class="flex justify-end">
        <Button type="submit" :disabled="form.processing">
          <Save class="size-4" />
          {{ form.processing ? 'Actualizando...' : 'Actualizar contraseña' }}
        </Button>
      </div>
    </form>
  </section>
</template>
