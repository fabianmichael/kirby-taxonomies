<template>
  <span class="s-taxonomies-badge" :data-theme="theme" :data-rounded="rounded">
    <k-icon v-if="icon" :type="icon" class="s-taxonomies-badge__icon" />
    <span class="s-taxonomies-badge__text" v-if="$slots.default">
      <slot />
    </span>
  </span>
</template>

<script>
export default {
  props: {
    icon: String,
    theme: {
      type: String,
      default: "light",
    },
    rounded: {
      type: Boolean,
      default: false,
    },
  },
}
</script>

<style>
.s-taxonomies-badge {
  background: var(--color-gray-100);
  border-radius: var(--rounded-sm);
  border: 1px solid var(--color-gray-300);
  color: var(--color-gray-600);
  display: inline-flex;
  font: normal normal .625rem/1 var(--font-sans);
  gap: var(--spacing-1);
  padding: .3em .4em;
  text-transform: uppercase;
}

.s-taxonomies-badge[data-theme="dark"] {
  background: var(--color-gray-700);
  color: var(--color-white);
  border-color: var(--color-gray-700);
}

.s-taxonomies-badge[data-theme="warning"] {
  background: var(--color-yellow-200);
  color: var(--color-text);
  border-color: var(--color-yellow-300);
}

.s-taxonomies-badge[data-rounded="true"] {
  border-radius: 1000px;
  padding-inline: .75em;
}

.s-taxonomies-badge__icon {
  height: .625rem;
  width: .625rem;
}

</style>
