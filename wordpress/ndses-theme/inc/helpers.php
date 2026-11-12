<?php
/**
 * Theme helper functions.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ndses_setting(string $key, mixed $fallback = ''): mixed
{
    if (function_exists('get_field')) {
        // The Site Settings screen (ndses-cms plugin) saves with acf_form()
        // under post_id 'ndses-site-settings', not ACF's default 'option'.
        $value = get_field($key, 'ndses-site-settings');
        if ($value !== null && $value !== false && $value !== '') {
            return $value;
        }
    }

    return ndses_data()['settings'][$key] ?? $fallback;
}

/**
 * Office hours as display lines, each ending in the time zone (NDS is in
 * Delavan, WI: Central Time) unless the editor already wrote one.
 */
function ndses_hours_lines(): array
{
    return array_map(static function (string $line): string {
        return preg_match('/\b(CT|CST|CDT|Central)\b/i', $line) ? $line : $line . ' CT';
    }, ndses_lines(ndses_setting('hours', [])));
}

function ndses_page_field(string $field, mixed $fallback = ''): mixed
{
    if (function_exists('get_field')) {
        $value = get_field($field);
        if ($value !== null && $value !== false && $value !== '') {
            return $value;
        }
    }

    return $fallback;
}

/**
 * "Request This Size" target. The Dumpster Rentals page's #quote form is the
 * only inquiry form with a dumpster-size field, so the size has to land there
 * (the Contact page's general form would silently drop it).
 */
function ndses_dumpster_request_url(string $slug = ''): string
{
    $query = $slug !== '' ? '?service=dumpster&size=' . rawurlencode($slug) : '?service=dumpster';

    return home_url('/dumpster-rentals/' . $query . '#quote');
}

function ndses_asset(string $filename): string
{
    return esc_url(get_theme_file_uri('/assets/images/' . ltrim($filename, '/')));
}

function ndses_phone_href(): string
{
    return 'tel:' . preg_replace('/[^0-9+]/', '', (string) ndses_setting('phone'));
}

function ndses_current_slug(): string
{
    if (is_front_page()) {
        return 'home';
    }

    $community = get_query_var('ndses_community');
    if ($community) {
        return 'residential';
    }

    return get_post_field('post_name') ?: 'home';
}

function ndses_page_hero(?string $slug = null): array
{
    $slug = $slug ?: ndses_current_slug();
    $fallback = ndses_data()['pages'][$slug] ?? ndses_data()['pages']['home'];
    $acf_hero = ndses_page_field('hero', []);

    if (is_array($acf_hero) && !empty($acf_hero)) {
        // ACF returns '' (not null) for empty sub-fields, so fall back on
        // emptiness rather than with ??.
        $pick = static fn (string $key) => !empty($acf_hero[$key]) ? $acf_hero[$key] : ($fallback[$key] ?? '');

        return [
            'eyebrow' => $pick('eyebrow'),
            'heading' => $pick('heading'),
            'description' => $pick('description'),
            'image' => $pick('image'),
        ];
    }

    return $fallback;
}

function ndses_button(string $label, string $url, string $class = 'button button-primary'): void
{
    printf('<a class="%s" href="%s">%s</a>', esc_attr($class), esc_url($url), esc_html($label));
}

function ndses_list(array $items, string $class = 'check-list'): void
{
    echo '<ul class="' . esc_attr($class) . '">';
    foreach ($items as $item) {
        echo '<li>' . esc_html($item) . '</li>';
    }
    echo '</ul>';
}

/**
 * Navigation tree: [['label', 'url', 'description', 'children' => [...]], ...].
 * Uses the menu assigned to "Primary Navigation" (Appearance > Menus) when
 * there is one -- top-level items become dropdown triggers and their
 * sub-items the dropdown links -- otherwise the built-in nav in data.php.
 */
function ndses_nav_items(): array
{
    $locations = get_nav_menu_locations();
    if (!empty($locations['primary'])) {
        $items = wp_get_nav_menu_items($locations['primary']) ?: [];
        $tree = [];
        foreach ($items as $item) {
            if ((int) $item->menu_item_parent === 0) {
                $tree[$item->ID] = ['label' => $item->title, 'url' => $item->url, 'description' => $item->description, 'children' => []];
            }
        }
        foreach ($items as $item) {
            $parent = (int) $item->menu_item_parent;
            if ($parent && isset($tree[$parent])) {
                $tree[$parent]['children'][] = ['label' => $item->title, 'url' => $item->url, 'description' => $item->description];
            }
        }
        if ($tree) {
            return array_values($tree);
        }
    }

    return array_map(static function (array $item): array {
        $item['url'] = home_url($item['url']);
        $item['children'] = array_map(static fn (array $child) => array_merge($child, ['url' => home_url($child['url'])]), $item['children'] ?? []);

        return $item;
    }, ndses_data()['nav']);
}

/** True when the current request is the item's page or one of its children's pages. */
function ndses_nav_is_active(array $item): bool
{
    $current = untrailingslashit((string) wp_parse_url(add_query_arg([]), PHP_URL_PATH));
    $paths = array_merge([$item['url']], array_column($item['children'] ?? [], 'url'));
    foreach ($paths as $url) {
        $path = untrailingslashit((string) wp_parse_url(strtok($url, '#'), PHP_URL_PATH));
        if ($path === '' ? $current === '' : ($current === $path || str_starts_with($current . '/', $path . '/'))) {
            return true;
        }
    }

    return false;
}

