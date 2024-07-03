<template>
    <input
    :id="id"
    :name="name"
    :type="type"
    :autocomplete="autocomplete"
    :placeholder="placeholder"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    
    class="block px-2 w-full rounded-md border-0 py-1.5 text-dutyLight font-SourceSans font-semibold shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-dutyLight/50 focus:ring-2 focus:ring-inset focus:ring-habit sm:text-sm sm:leading-6"
  />
  </template>
  
  <script>
  export default {
    name: 'MainInput',
    props: {
      modelValue: {
    type: [String, Number],
    default: ''
  },
      id: {
        type: String,
        required: true
      },
      name: {
        type: String,
        required: true
      },
      type: {
        type: String,
        default: 'text'
      },
      autocomplete: {
        type: String,
        default: 'off'
      },
      placeholder: {
        type: String,
        default: ''
      }
    },
    emits: ['update:modelValue']
  };
  </script>