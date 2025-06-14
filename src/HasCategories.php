<?php

namespace FabianMichael\Taxonomies;

use Kirby\Cms\Blocks;
use Kirby\Cms\Html;
use Kirby\Uuid\Identifiable;

trait HasCategories
{
	public function getCategories(): Blocks
	{
		$categories = $this->parent()->getCategoryDefinitions(true);
		$result = new class () extends Blocks {
			public Identifiable $model;

			public function __toString(): string
			{
				return implode(', ', array_map(fn ($item) => $item->title()->html(), $this->data));
			}

			public function toLinks(): string
			{
				$html = [];

				foreach ($this->data as $category) {
					$html[] = Html::link(
						$category->url(),
						$category->title()
					);
				}

				return implode(', ', $html);
			}
		};

		$result->model = $this;

		foreach ($this->categories()->split(',') as $uuid) {
			$category = $categories->findBy('id', $uuid);
			if (! $category) {
				continue;
			}
			$result->add($category);
		}

		return $result;
	}
}
