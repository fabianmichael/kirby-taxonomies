<?php

use FabianMichael\Taxonomies\BlockModels\Category;
use Kirby\Cms\App as Kirby;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('fabianmichael/kirby-taxonomies', [
	'blockModels' => [
		'category' => Category::class,
	],
	'blueprints' => [
		'fields/categories' => __DIR__ . '/blueprints/fields/categories.yml',
		'fields/category-definitions' => require __DIR__ . '/blueprints/fields/category-definitions.php',
	],
	'pagesMethods' => require __DIR__ . '/config/pages-methods.php',
	'translations' => [
		'en' => require __DIR__ . '/translations/en.php',
		'de' => require __DIR__ . '/translations/de.php',
	],
]);
