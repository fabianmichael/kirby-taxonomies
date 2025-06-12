<?php

namespace FabianMichael\Taxonomies;

use Kirby\Cms\Block;
use Kirby\Cms\Blocks;

trait HasCategoryDefinitions
{
	public function getCategoryBySlug(string $slug): ?Block
	{
		return $this->getCategoryDefinitions(true)
			->filterBy('slug', $slug)
			->first();
	}

	public function getCategoryDefinitions(bool $empty = false): Blocks
	{
		$categories = $this
			->content($this->kirby()->defaultLanguage()?->code())
			->get('categories')
			->toBlocks();

		if ($empty === false) {
			// Filter out empty (= not used) categories
			$used = $this->children()->listed()->pluck('categories', ',', true);
			$categories = $categories->filterBy('id', 'in', $used);
		}

		return $categories;
	}
}
