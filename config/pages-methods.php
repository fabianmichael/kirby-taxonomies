<?php

use Kirby\Cms\Pages;
use Kirby\Toolkit\A;

return [
	'filterByCategories' => function (string|array $categories): Pages {
		$categories = A::wrap($categories);

		return $this->filter(function ($item) use ($categories) {
			return count(array_intersect($item->categories()->split(','), $categories)) > 0;
		});
	},
	'filterByCategorySlugs' => function (string|array $categories): Pages {
		$categories = A::wrap($categories);
		$parent = $this->first()?->parent() ?? $this->parent();

		// Translate category slugs into ids
		$categories = array_filter(array_map(fn ($v) => $parent->getCategoryBySlug($v)?->id(), $categories));


		// Filter by these IDs
		return $this->filterByCategories($categories);
	},
];
