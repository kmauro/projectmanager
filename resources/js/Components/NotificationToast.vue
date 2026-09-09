<script setup>
import { usePage } from '@inertiajs/vue3'
import { CheckCircle2, XCircle } from 'lucide-vue-next'
import { computed, ref, watch } from 'vue'

const page = usePage()
const visible = ref(false)
let timeoutId

const success = computed(() => page.props.flash?.success ?? null)
const error = computed(() => page.props.flash?.error ?? null)
const message = computed(() => success.value ?? error.value)

watch(message, (value) => {
  window.clearTimeout(timeoutId)
  visible.value = Boolean(value)

  if (value) {
    timeoutId = window.setTimeout(() => {
      visible.value = false
    }, 3500)
  }
}, { immediate: true })
</script>

<template>
  <Transition
    enter-active-class="transition duration-300"
    enter-from-class="-translate-y-3 opacity-0"
    enter-to-class="translate-y-0 opacity-100"
    leave-active-class="transition duration-200"
    leave-from-class="translate-y-0 opacity-100"
    leave-to-class="-translate-y-3 opacity-0"
  >
    <div
      v-if="visible && message"
      role="status"
      class="fixed left-1/2 top-6 z-[100] flex max-w-[calc(100%-2rem)] -translate-x-1/2 items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-white shadow-lg"
      :class="success ? 'bg-emerald-600' : 'bg-destructive'"
    >
      <CheckCircle2 v-if="success" class="size-5 shrink-0" />
      <XCircle v-else class="size-5 shrink-0" />
      <span>{{ message }}</span>
    </div>
  </Transition>
</template>
