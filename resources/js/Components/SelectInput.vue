<template>
  <select
    :id="id"
    ref="selectElement"
    :value="modelValue"
    :class="[
      'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm',
      $attrs.class || ''
    ]"
    v-bind="$attrs"
    @input="$emit('update:modelValue', $event.target.value)"
  >
    <slot></slot>
  </select>
</template>

<script setup>
import { ref, onMounted, watch, nextTick, onUnmounted } from 'vue';
import TomSelect from 'tom-select';

const props = defineProps({
  id: String,
  modelValue: [String, Number, Boolean, Array],
  options: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['update:modelValue']);

const selectElement = ref(null);
let tomSelect = null;

const defaultOptions = {
  create: false,
  sortField: {
    field: "text",
    direction: "asc"
  },
  render: {
    option: function(data, escape) {
      return `<div class='px-3 py-2 hover:bg-indigo-100'>${escape(data.text)}</div>`;
    },
    item: function(data, escape) {
      return `<div class='ts-item'>${escape(data.text)}</div>`;
    },
    dropdown: function() {
      return '<div class="ts-dropdown"></div>';
    }
  },
  onInitialize: function() {
    this.wrapper.classList.add(
      'border-gray-300',
      'focus:border-indigo-500',
      'focus:ring-indigo-500',
      'rounded-md',
      'shadow-sm',
      'mt-1',
      'block',
      'w-full'
    );
    this.control.classList.add('px-3', 'py-2');
    this.dropdown.classList.add('border', 'border-gray-300', 'shadow-lg', 'rounded-md', 'mt-1');
  },
  ...props.options
};

onMounted(() => {
  nextTick(() => {
    tomSelect = new TomSelect(selectElement.value, {
      ...defaultOptions,
      onChange: (value) => {
        emit('update:modelValue', value);
      }
    });

    if (tomSelect.wrapper) {
      tomSelect.wrapper.classList.add(
        'border-gray-300',
        'focus-within:border-indigo-500',
        'focus-within:ring-indigo-500',
        'rounded-md',
        'shadow-sm',
        'mt-1',
        'block',
        'w-full'
      );
      if (tomSelect.control_input) {
        tomSelect.control_input.classList.add('focus:ring-0', 'focus:outline-none');
      }
    }
  });
});

watch(() => props.modelValue, (newValue) => {
  if (tomSelect && newValue !== tomSelect.getValue()) {
    tomSelect.setValue(newValue);
  }
});

onUnmounted(() => {
  if (tomSelect) {
    tomSelect.destroy();
  }
});
</script>

<style>
.ts-wrapper.single .ts-control {
  @apply bg-white border-gray-300 rounded-md shadow-sm;
  background-image: none !important;
}

.ts-dropdown {
  @apply border border-gray-300 shadow-lg rounded-md mt-1;
}

.ts-dropdown .active {
  @apply bg-indigo-100 text-indigo-800;
}

.ts-control {
  @apply px-3 py-2;
}

.ts-wrapper.multi .ts-control > div {
  @apply bg-indigo-100 text-indigo-800 rounded px-2 py-1 mr-1 mb-1;
}

.ts-wrapper.multi .ts-control input {
  @apply focus:ring-0 focus:outline-none;
}

.ts-dropdown .option {
  @apply px-3 py-2 hover:bg-indigo-100 cursor-pointer;
}

.ts-dropdown .create {
  @apply px-3 py-2 text-gray-500;
}

.ts-dropdown .dropdown-header:after {
  display: none !important;
}
</style>