<template>
  <div class="s-taxonomies-category" @dblclick="open">
    <ul v-if="$multilang" class="s-taxonomies-category-multilang">
      <li v-for="(language) in $languages" :key="language.code">
        <s-taxonomies-badge :theme="language.default ? 'dark' : null">{{ language.code }}</s-taxonomies-badge>
        <span class="s-taxonomies-category-name" :data-default="language.default">{{ (language.default ? content.title : content["title_" + language.code]) || "—" }}</span>
        <span class="s-taxonomies-category-slug">({{ (language.default ? content.slug : content["slug_" + language.code]) || "—" }})</span>
      </li>
    </ul>
    <div class="s-taxonomies-category-title" v-else>
      <span class="s-taxonomies-category-name">{{ content.title || "—" }}</span>
      <span class="s-taxonomies-category-slug">({{ content.slug || "—" }})</span>
    </div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    fieldset: Object,
    content: Object
  },
}
</script>

<style>
.s-taxonomies-category {
  font-size: var(--text-sm);
  line-height: var(--leading-normal);
}

.s-taxonomies-category-multilang {
  display: flex;
  flex-wrap: wrap;
  gap: var(--spacing-4);
}

.s-taxonomies-category-multilang > li {
  display: flex;
  align-items: baseline;
  column-gap: var(--spacing-2);
}

.s-taxonomies-category-multilang > li .s-taxonomies-badge {
  position: relative;
  top: -1.5px;
}

.s-taxonomies-category-title {
  display: flex;
  align-items: center;
  min-width: 0;
  column-gap: var(--spacing-3);
}

.s-taxonomies-category-icon {
  width: 1rem;
  color: var(--color-gray-500);
}

.s-taxonomies-category-name[data-default="true"] {
  font-weight: var(--font-bold);
}

.s-taxonomies-category-slug {
  color: var(--color-gray-600);
  font-size: var(--text-xs);
  font-family: var(--font-mono);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.s-taxonomies-category-translations {
  border-top: 1px dotted var(--color-border);
  margin-top: var(--spacing-3);
  padding-top: var(--spacing-3);
}
</style>
