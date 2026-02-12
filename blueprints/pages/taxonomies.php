<?php

use FabianMichael\Taxonomies\Taxonomies;

return function (): array {
    $columns = [];

    foreach (array_keys(Taxonomies::$taxonomies) as $slug) {
        $columns[] = [
            'width' => '1/2',
            'sections' =>  [
                "{$slug}_terms" => [
                    'extends' => "sections/taxonomies/{$slug}-terms",
                    'parent' => "page.find('{$slug}')",
                ],
            ],
        ];
    }

    return [
        'title' => 'Taxonomies',
        'icon' => 'tag',
        'image' => [
            'back' => 'var(--item-color-back)',
            'color' => 'var(--item-color-icon)',
            'query' => false,
        ],
        'options' => [
            'create' => false,
            'preview' => false,
            'delete' => false,
            'changeSlug' => false,
            'changeStatus' => false,
            'duplicate' => false,
            'changeTitle' => false,
            'update' => false,
            'move' => false,
        ],
        'status' => [
            'draft' => false,
            'unlisted' => true,
            'listed' => false,
        ],
        'buttons' => [
            'languages' => true,
        ],
        'columns' => $columns,
    ];
};
