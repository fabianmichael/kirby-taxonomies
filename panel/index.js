/* globals panel */

import CategoryBlock from './components/blocks/Category.vue'
import Badge from './components/Badge.vue'

panel.plugin('fabianmichael/taxonomies', {
  blocks: {
    category: CategoryBlock
  },
  components: {
    's-taxonomies-badge': Badge
  }
})
