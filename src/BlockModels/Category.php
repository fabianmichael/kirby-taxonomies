<?php

namespace FabianMichael\Taxonomies\BlockModels;

use Kirby\Cms\Block;
use Kirby\Content\Field;

class Category extends Block
{
	public function title(): Field
	{
		return $this->getTranslatedField('title');
	}

	public function slug(): Field
	{
		return $this->getTranslatedField('slug');
	}

	protected function getTranslatedField(string $key): Field
	{
		$kirby = $this->parent()->kirby();
		$content = $this->content();

		if ($kirby->multilang()) {
			$code = $kirby->languageCode();

			if ($code && $kirby->language()?->isDefault() === false) {
				return $content->get("{$key}_{$code}")->or($content->get($key));
			}
		}

		return $content->get($key);
	}
}
