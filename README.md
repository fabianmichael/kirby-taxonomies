# Taxonomies for Kirby CMS

This plugin adds taxonomy management (i.e. tags, categories etc.) to [Kirby CMS](https://getkirby.com)

Key features:

- Blueprint-based taxonomy definitions
- Multilang support
- Dedicated panel area for managing taxonomy terms
- Nested terms
- Custom fields for terms
- Relations to terms are based on UUIDs, so you can rename and/or translate terms later on
- Helper methods for filtering by slug, UUID etc.

## Installation

This version of the plugin requires PHP 8.4+, Kirby 5 and your Kirby installation must have UUIDs enabled. The recommended way of installing is by using Composer:

```
$ composer require fabianmichael/kirby-taxonomies
```

Alternatively, download and copy this repository to `site/plugins/taxonomies/`

## Configuration

The is current only one global option, which defined the UUID and id of the container page which holds all taxonomies and their respective terms. You only have to change this, if your project requires a public page named `taxonomies` at root level.

```php
<?php

return [
    'fabianmichael.taxonomies.page' => 'taxonomies',
];
```

If your site uses a custom panel menu, make sure to include the `'taxonomies'` item to make the area visible.

## Define your taxonomies

Taxonomies are always global and are defined by adding blueprints to `site/blueprints/taxonomies`. A taxonomy is basically just an extended version of a regular page, but the blueprints work a bit different from regular page blueprints. The page also does not have a preview button, cannot be deleted and will automatically created once the blueprint has been defined.

```yaml
# site/blueprints/taxonomies/categories.yml
title: Categories
```

> [!TIP]
> It is advisable to use the plural form ("cartegories", NOT "category"), since every taxonomy will hold multiple terms. Pages can usually can have multiple terms assigned. E.g. a blog post, that has the categories "Frontend" and "CSS".

This is the simplest possible taxonomy definition. The filename is the internal name and the title is displayed in the panel UI.

Taxonomy blueprints support a few additional properties:

| Name | Type | Default | Description |
|:-----|:-----|:--------|:------------|
| files | `mixed` | `false` | Use boolean to add a simple files section, an array to define a custom blueprint or a string (e.g. `sections/files`) for a reusable blueprint if you want to allow file uploads (e.g. an icon) for terms in this taxonomy. |
| hierarchical | `bool` | `false` | Enables/disables nested terms |
| fields | `mixed` | `false` | Define custom additional fields

> [!NOTE]
> Taxonomies do not support advanced layout options such as tabs, columns and sections.

## Add fields to your blueprints

Add the `fields/taxonomies/[taxonomy name]` field to your blueprints and your are ready to go:

```yaml
# site/blueprints/pages/article.yml
title: Blog article
fields:
  [...]
  categories: fields/taxonomies/categories
```

> [!NOTE]
> By default there is an n-to-n relation between terms and pages. If you want your blog article to only allow a single category, you have to extend the blueprints like this:
> ```yaml
> categories:
>   extends: fields/taxonomies/categories
>   multiple: false
> ```

## Template usage

After your blueprint setup is complete, you probably want to display taxonomy terms in the frontend. Use `$field->toTerms()` to get a taxonomy term collection. It extends Kirby’s [`$pages`](https://getkirby.com/docs/reference/objects/cms/pages) collection and inherits all it’s methods, but adds a few additional ones:

### `$terms->toString()`

Returns a string of comma-separated terms.

> [!CAUTION]
> The output is not not HTML-escaped and should not be used directly in your templates without proper escaping. See `$terms->html()` for an alternate method.

### `$terms->toHtml()`

Returns an HTML-escaped, comma-separated version of the terms list that can safely used in your templates:

```php
# site/templates/article.php

<h1><?= $page->title()->html() ?></h1>
<p><?= $page->categories()->toHtml() ?></p>

```

### `$terms->uuids()`

Returns an array of plain UUID values without the scheme (i.e. without the `page://` prefix).

## Filtering by taxonomy terms

This is where taxonomies really become handy. Let’s say our articles a subpages of a page which uses the template `blog`. Any collection of pages has a `filterByTaxonomySlugs()` method, which ensures you still get nice URLs. 

```php
# site/controllers/blog.php
<?php

return function ($kirby, $page): array {
    $articles = $page->children()->listed();
    $categories = $articles->terms('categories'); // get all actively used category terms as collection

    if ($category = param('category')) {
        $articles = $articles->filterByTaxonomySlugs('categories', $category);
    }

    return [
        'articles' => $articles,
        'categories' => $catories,
    ]
};
```

```php
# site/templates/blog.php

<h1><?= $page->title()->html() ?></h1>

<p>Filter by category:</p>
<ul>
    <li><a href="<?= $page->url() ?>">All articles</a></li>
    <?php foreach ($categories as $category): ?>
        <li><a href="<?= $page->url([
            'params' => ['category' => $category->slug()],
        ]) ?>"><?= $category->title()->html() ?>
    <?php endforeach ?>
</ul>

<ul>
    <?php foreach ($articles as $article): ?>
        <li>
            <a href="<?= $article->url() ?>"><?= $article->title()->html() ?></a>
        </li>
    <?php endforeach ?>
</ul>

```

> [!TIP]
> If it is critical for your project that filter views stay valid after the slugs of terms have changed, you could also use their UUID values instead of slugs. There is a helper method called `$pages->filterByTaxonomyUuids()` for that.


## Reference

### Global helper functions

`taxonomies(): Taxonomies`  
Returns the main taxonomies page instance.

`taxonomy(Taxonomy|string $taxonomyOrName): ?Taxonomy`  
Returns the taxonomy page instance for a given taxonomy or its name.

### `$site` methods

`$site->taxonomies(): Taxonomies`  
Returns the taxonomies collection (equivalent to calling `taxonomies()`).

### `$page` methods

`$page->taxonomy(string $name): TermsCollection`  
Returns the terms collection for the given taxonomy field on the page.

### `$pages` methods

`$pages->filterByTaxonomyUuids(string $taxonomy, Pages|string|array $uuids, bool $all = false): Pages`  
Filters the collection to pages that are related to the given term UUID(s).

`$pages->filterByTaxonomySlugs(string $taxonomy, Pages|string|array $slugs, bool $all = false): Pages`  
Filters the collection to pages that are related to the given term slug(s).

`$pages->terms(string $taxonomy): TermsCollection`  
Returns all terms used on the pages for the given taxonomy, sorted by title.

### Field methods

`$field->toTerms(): TermsCollection`  
Converts a taxonomy field value into a terms collection, preserving the field’s parent as the collection’s parent.

### Terms collection methods

`$terms->toString(): string`  
Returns a plain, comma-separated list of term titles.

`$terms->toHtml(): string`  
Returns an HTML-escaped, comma-separated list of term titles.

`$terms->uuids(): array`  
Returns an array of UUID strings (without the `page://` scheme) for all terms in the collection.

