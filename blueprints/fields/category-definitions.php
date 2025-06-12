<?php

use Kirby\Cms\App;

return function (App $kirby) {
	if ($kirby->multilang()) {
		// Multilang installation: The blueprints provides fields
		// for translation categories.
		$fields = [];
		$languages = $kirby->languages();

		$i = 0;
		foreach ($languages as $l) {
			$suffix = ! $l->isDefault() ? "_{$l->code()}" : '';

			if ($i > 0) {
				$fields["line{$suffix}"] = [
					'type' => 'line',
				];
			}

			$fields["title{$suffix}"] = [
				'type' => 'text',
				'label' => tt('taxonomies.fields.title.multilang.label', ['language' => $l->name()]),
				'required' => true,
				'width' => '1/2',
			];

			$fields["slug{$suffix}"] = [
				'type' => 'slug',
				'label' => tt('taxonomies.fields.slug.multilang.label', ['language' => $l->name()]),
				'required' => true,
				'converter' => 'slug',
				'sync' => "title{$suffix}",
				'help' => $i++ === 0 ? t('taxonomies.fields.slug.help') : null,
				'width' => '1/2',
				'wizard' => [
					'field' => "title{$suffix}",
					'text' => t('taxonomies.fields.slug.wizardText'),
				],
			];
		}
	} else {
		// Single-language installation: only title and slug fields

		$fields = [
			'title' => [
				'type' => 'text',
				'label' => t('taxonomies.fields.title.label'),
				'required' => true,
				'width' => '1/2',
			],
			'slug' => [
				'type' => 'slug',
				'label' => t('taxonomies.fields.slug.label'),
				'converter' => 'slug',
				'sync' => 'title',
				'wizard' => [
					'field' => 'title',
					'text' => t('taxonomies.fields.slug.wizardText'),
				],
				'help' => t('taxonomies.fields.slug.help'),
				'width' => '1/2',
			],
		];
	}

	return [
		'type' => 'blocks',
		'label' => t('taxonomies.fields.categoryDefinitions.label'),
		'preview' => 'category',
		'translate' => false,
		'empty' => t('taxonomies.fields.categoryDefinitions.empty'),
		'fieldsets' => [
			'category' => [
				'name' => t('taxonomies.blocks.category'),
				'fields' => $fields,
			],
		],
		'help' => (!$kirby->multilang() || $kirby->language()?->isDefault())
			? null
			: t('taxonomies.fields.categoryDefinitions.help'),
	];
};
