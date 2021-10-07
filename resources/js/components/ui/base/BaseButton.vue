<template>
  <button
    :type="type"
    :class="btnClass"
    :disabled="disabled || loading"
    @click="handleClick"
  >
    <font-awesome-icon
      v-if="icon && !loading && !rightIcon"
      :class="iconClass"
      :icon="icon"
      class="vue-icon icon-left"
    />
    <font-awesome-icon
      v-if="loading"
      :class="iconClass"
      icon="spinner"
      class="fa-spin"
    />
    <slot />
    <font-awesome-icon
      v-if="icon && !loading && rightIcon"
      :class="iconClass"
      :icon="icon"
      class="vue-icon icon-right"
    />
  </button>
</template>

<script>

export default {
  props: {
    icon: {
      type: String,
      required: false,
      default: ''
    },
    color: {
      type: String,
      required: false,
      default: ''
    },
    round: {
      type: Boolean,
      required: false,
      default: false
    },
    outline: {
      type: Boolean,
      required: false,
      default: false
    },
    size: {
      type: String,
      required: false,
      default: 'default'
    },
    loading: {
      type: Boolean,
      required: false,
      default: false
    },
    block: {
      type: Boolean,
      required: false,
      default: false
    },
    iconButton: {
      type: Boolean,
      required: false,
      default: false
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    },
    rightIcon: {
      type: Boolean,
      required: false,
      default: false
    },
    type: {
      type: String,
      required: false,
      default: 'button'
    }
  },
  computed: {
    btnClass () { 
      return '';
    },
    iconClass () {
      if (this.loading || !this.iconButton) {
        if (this.rightIcon) {
          return 'ml-2'
        }
        return 'mr-2'
      }
      return 'icon-button'
    }
  },
  methods: {
    handleClick (e) {
      this.$emit('click')
    }
  }
}
</script>
