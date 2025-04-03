<template>
  <textarea
    :id="id"
    :value="modelValue"
    :class="[
      'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm',
      $attrs.class || ''
    ]"
    v-bind="$attrs"
    @input="$emit('update:modelValue', $event.target.value)"
  ></textarea>
</template>

<script setup>
defineProps({
  id: String,
  modelValue: [String, Number, Boolean],
});

defineEmits(['update:modelValue']);
</script>