<?php
/**
 * CMS-backed content with seed fallbacks.
 *
 * Each getter returns the same shape as its ndses_data() seed so templates
 * don't care where content came from. Content entered in wp-admin wins;
 * when a post type has no published entries yet, the seed in inc/data.php
 * is used instead, so the site never renders empty.
 *
 * Only free-ACF field types are used here (no repeaters / flexible content /
 * options pages): lists are textareas with one item per line.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ndses_acf(string $key, $post_id = false, bool $format = true)
{
    return function_exists('get_field') ? get_field($key, $post_id, $format) : null;
}

/**
 * Normalizes a list value into an array of non-empty strings. Accepts a
 * one-item-per-line textarea string, a plain array (seed data), or legacy
 * repeater rows such as [['line' => '...'], ...] / [['item' => '...'], ...].
 */
function ndses_lines($value): array
{
    if (is_string($value)) {
        $value = preg_split('/\r\n|\r|\n/', $value);
    }
    if (!is_array($value)) {
        return [];
    }

    $lines = [];
    foreach ($value as $row) {
        if (is_array($row)) {
            $row = $row['line'] ?? $row['item'] ?? reset($row);
        }
        $row = trim((string) $row);
        if ($row !== '') {
            $lines[] = $row;
        }
    }

    return $lines;
}

/** Published posts of a type in editor order (menu order, then title). */
function ndses_cms_posts(string $post_type): array
{
    static $cache = [];
    if (!function_exists('get_field')) {
        return [];
    }
    if (isset($cache[$post_type])) {
        return $cache[$post_type];
    }

    return $cache[$post_type] = get_posts([
        'post_type' => $post_type,
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC'],
        'no_found_rows' => true,
    ]);
}

function ndses_get_mission(): string
{
    return (string) ndses_setting('mission', ndses_data()['mission']);
}

/** About page "Our Story" as safe HTML. */
function ndses_get_our_story_html(): string
{
    $story = ndses_setting('our_story', '');
    if (is_string($story) && trim(wp_strip_all_tags($story)) !== '') {
        return wp_kses_post($story);
    }

    return implode('', array_map(static fn ($paragraph) => '<p>' . esc_html($paragraph) . '</p>', ndses_data()['our_story']));
}

/** FAQs grouped by display label: [label => [[question, answer_html], ...]]. */
function ndses_get_faqs(): array
{
    $posts = ndses_cms_posts('faq');
    if (!$posts) {
        $groups = [];
        foreach (ndses_faq_seed() as $label => $faqs) {
            $groups[$label] = array_map(static fn ($faq) => [$faq[0], '<p>' . esc_html($faq[1]) . '</p>'], $faqs);
        }

        return $groups;
    }

    $labels = [
        'dumpster' => 'Dumpster Rentals',
        'residential' => 'Residential Trash & Recycling',
        'commercial' => 'Commercial Trash & Recycling',
    ];
    $rows = [];
    foreach ($posts as $post) {
        $question = ndses_acf('question', $post->ID) ?: get_the_title($post);
        $answer = (string) ndses_acf('answer', $post->ID);
        $category = (string) ndses_acf('category', $post->ID);
        if ($answer === '' || !isset($labels[$category])) {
            continue;
        }
        $rows[] = [
            'label' => $labels[$category],
            'order' => (int) ndses_acf('sortOrder', $post->ID),
            'faq' => [$question, wp_kses_post($answer)],
        ];
    }
    usort($rows, static fn ($a, $b) => $a['order'] <=> $b['order']);

    $groups = array_fill_keys(array_values($labels), []);
    foreach ($rows as $row) {
        $groups[$row['label']][] = $row['faq'];
    }

    return array_filter($groups);
}

/**
 * Service areas for the coverage map: [['name', 'county', 'types', 'lat', 'lon'], ...].
 * 'lat'/'lon' are decimal degrees, or null when the editor left them blank
 * (counties, or a town that should be listed without a pin).
 */
function ndses_get_service_areas(): array
{
    $posts = ndses_cms_posts('service_area');
    if (!$posts) {
        return ndses_data()['service_areas'];
    }

    $type_labels = ['residential' => 'Residential', 'commercial' => 'Commercial', 'rollOff' => 'Roll-off'];
    $coordinate = static function (string $key, int $post_id): ?float {
        $value = ndses_acf($key, $post_id);

        return is_numeric($value) ? (float) $value : null;
    };
    $areas = [];
    foreach ($posts as $post) {
        $types = array_values(array_intersect_key($type_labels, array_flip((array) ndses_acf('service_types', $post->ID))));
        $areas[] = [
            'name' => get_the_title($post),
            'county' => (string) ndses_acf('county', $post->ID),
            'types' => $types,
            'lat' => $coordinate('latitude', $post->ID),
            'lon' => $coordinate('longitude', $post->ID),
        ];
    }

    return $areas;
}

/**
 * Counties the address lookup treats as served: the distinct County values of
 * the published Service Areas (or the seed), without the word "County".
 * The client controls this list by publishing and unpublishing Service Areas.
 */
function ndses_service_counties(): array
{
    $counties = [];
    foreach (ndses_get_service_areas() as $area) {
        $county = trim(preg_replace('/\s+county$/i', '', (string) $area['county']));
        if ($county !== '') {
            $counties[$county] = true;
        }
    }

    return array_keys($counties);
}

/**
 * Counties with residential service: where an address gets the definite
 * "in the service area" answer rather than "likely, call to confirm".
 */
function ndses_residential_counties(): array
{
    $counties = [];
    foreach (ndses_get_service_areas() as $area) {
        if (!in_array('Residential', $area['types'], true)) {
            continue;
        }
        $county = trim(preg_replace('/\s+county$/i', '', (string) $area['county']));
        if ($county !== '') {
            $counties[$county] = true;
        }
    }

    return array_keys($counties);
}

/**
 * Service notices currently live: enabled, Active (or Scheduled and past its
 * start), and inside its display window unless "show until resolved" is on.
 */
function ndses_active_notice_posts(): array
{
    $now = current_time('timestamp');
    $live = [];
    foreach (ndses_cms_posts('service_notice') as $post) {
        if (!ndses_acf('notice_enabled', $post->ID)) {
            continue;
        }
        $status = (string) ndses_acf('notice_status', $post->ID);
        $start = ndses_acf('display_start', $post->ID, false);
        $end = ndses_acf('display_end', $post->ID, false);
        $started = !$start || strtotime((string) $start) <= $now;
        $ended = $end && !ndses_acf('show_until_resolved', $post->ID) && strtotime((string) $end) < $now;
        if (($status === 'Active' || ($status === 'Scheduled' && $start)) && $started && !$ended) {
            $live[] = $post;
        }
    }

    return $live;
}

/** What's New cards: [['title', 'type', 'date', 'body'], ...]. */
function ndses_get_notices(): array
{
    $posts = ndses_active_notice_posts();
    if (!$posts) {
        return ndses_data()['notices'];
    }

    return array_map(static function ($post) {
        $start = ndses_acf('display_start', $post->ID, false);

        return [
            'title' => ndses_acf('notice_title', $post->ID) ?: get_the_title($post),
            'type' => ndses_acf('notice_priority', $post->ID) ?: 'Notice',
            'date' => $start ? wp_date('F j, Y', strtotime((string) $start)) : get_the_date('F j, Y', $post),
            'body' => (string) (ndses_acf('short_summary', $post->ID) ?: wp_strip_all_tags((string) ndses_acf('full_details', $post->ID))),
        ];
    }, $posts);
}

/** Site-wide announcement bar text, or '' when no site-wide notice is live. */
function ndses_get_announcement(): string
{
    foreach (ndses_active_notice_posts() as $post) {
        if (in_array('siteWide', (array) ndses_acf('display_locations', $post->ID), true)) {
            return (string) (ndses_acf('announcement_text', $post->ID) ?: ndses_acf('notice_title', $post->ID) ?: get_the_title($post));
        }
    }

    return '';
}

/**
 * Communities from seed, with Service Day / Trash / Recycling schedule
 * overridden by a published Community post whose slug matches.
 */
function ndses_get_communities(): array
{
    $communities = ndses_data()['communities'];
    foreach (ndses_cms_posts('community') as $post) {
        if (!isset($communities[$post->post_name])) {
            continue;
        }
        foreach (['service_day', 'trash_schedule', 'recycling_schedule'] as $key) {
            $value = trim((string) ndses_acf($key, $post->ID));
            if ($value !== '') {
                $communities[$post->post_name][$key] = $value;
            }
        }
    }

    return $communities;
}

/** Commercial containers: [['name', 'body', 'dimensions', 'capacity', 'image', 'ideal'], ...]. */
function ndses_get_commercial_containers(): array
{
    $posts = ndses_cms_posts('commercial_container');
    if (!$posts) {
        return ndses_data()['commercial_containers'];
    }

    return array_map(static fn ($post) => [
        'name' => get_the_title($post),
        'body' => (string) ndses_acf('body', $post->ID),
        'dimensions' => (string) ndses_acf('dimensions', $post->ID),
        'capacity' => (string) ndses_acf('capacity', $post->ID),
        'image' => ndses_acf('image', $post->ID) ?: '',
        'ideal' => ndses_lines(ndses_acf('ideal_for', $post->ID)),
    ], $posts);
}

/** Theme-asset filename or ACF image array -> URL. */
function ndses_image_url($image): string
{
    if (is_array($image)) {
        return esc_url($image['url'] ?? '');
    }

    return $image ? ndses_asset((string) $image) : '';
}
