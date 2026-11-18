<?php
/**
 * Everything editors can change that isn't a page section:
 *
 * - "Site-wide Text & Images" on Site Settings: logos, browser-tab icon,
 *   share image, header/footer wording, banner buttons, the 404 page.
 * - "Town Page" on each Community: intro, schedule, guidelines, bulk and
 *   recycling lists, downloads. A new Community post becomes a new town
 *   page (and menu item) automatically.
 *
 * Every field starts out holding the copy the site shows today: site-wide
 * fields through their default value, town fields through acf/load_value
 * (each town has its own current copy in data.php). Leaving a site-wide
 * field empty keeps the built-in wording.
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ---------------------------------------------------------------------
 * Site-wide Text & Images (Site Settings)
 * ------------------------------------------------------------------- */

/** Same spec format as page sections: [type, label, default, instructions?]. */
function ndses_sitewide_spec(): array
{
    return [
        'logos' => ['label' => 'Logos & Icons', 'fields' => [
            'site_icon' => ['image', 'Browser tab icon', null, 'The small square icon in browser tabs and bookmarks. Use a square image, at least 512 × 512 pixels. Leave empty to keep the current icon. (The header and footer logos are at the top of Site Settings.)'],
            'share_image' => ['image', 'Share picture', null, 'The picture shown when someone shares a link to the site on Facebook or in a text message. 1200 × 630 pixels works best. Leave empty to use the home page truck photo.'],
        ]],
        'header' => ['label' => 'Header', 'fields' => [
            'tagline' => ['text', 'Top bar text', 'Locally owned waste collection, recycling, and rentals', 'The short line in the dark green bar at the very top of every page. The office hours beside it come from Office Hours above.'],
            'quote_label' => ['text', 'Quote button', 'Get a Quote', 'Goes to the Contact page.'],
            'pay_label' => ['text', 'Payment button', 'Pay Bill', 'Goes to the Make a Payment page.'],
            'alert_link' => ['text', 'Alert banner link', 'View updates', 'The link at the end of the yellow alert banner, when an alert is showing.'],
        ]],
        'banner' => ['label' => 'Page Banner Buttons', 'fields' => [
            'primary' => ['text', 'First button', 'Get a Quote', 'Shown under the big title at the top of each page. Goes to the Contact page.'],
            'secondary' => ['text', 'Second button', 'Call NDS', 'Calls the office phone number.'],
            'rent_link' => ['text', 'Dumpster link', 'Rent a Dumpster', 'The extra link on the Home and Dumpster Rentals pages.'],
        ]],
        'footer' => ['label' => 'Footer', 'fields' => [
            'cta_heading' => ['text', 'Call-to-action heading', 'Need trash service, recycling, or a dumpster?', 'The dark green box at the top of the footer on every page.'],
            'cta_text' => ['textarea', 'Call-to-action text', 'Call the office or send a request, and NDS will confirm availability and pricing.'],
            'cta_button' => ['text', 'Call-to-action button', 'Request a Quote', 'Goes to the Contact page. The phone button beside it shows the office phone number.'],
            'contact_heading' => ['text', 'Contact column heading', 'Contact'],
            'services_heading' => ['text', 'Services column heading', 'Services'],
            'services_links' => ['list', 'Services column links', ['Residential | /residential/', 'Commercial | /commercial/', 'Dumpster Rentals | /dumpster-rentals/', 'Dumpster Calculator | /dumpster-calculator/', 'Special Events | /special-events/'], 'One link per line, written as Link text | /page-address/'],
            'customers_heading' => ['text', 'Customers column heading', 'Customers'],
            'customers_links' => ['list', 'Customers column links', ['Pay Your Bill | /make-a-payment/', 'Service Alerts | /whats-new/', 'FAQs | /faqs/', 'About NDS | /about/', 'Contact | /contact/'], 'One link per line, written as Link text | /page-address/'],
            'legal_links' => ['list', 'Bottom links', ['Privacy Policy | /privacy-policy/', 'Terms & Content Sources | /terms/', 'Photo Credits | /photo-credits/'], 'The small links beside the copyright line. One per line, written as Link text | /page-address/'],
        ]],
        'notfound' => ['label' => 'Page Not Found', 'fields' => [
            'heading' => ['text', 'Heading', 'Page not found', 'Shown when someone visits a page address that doesn\'t exist.'],
            'text' => ['textarea', 'Text', 'The page you\'re looking for has moved or doesn\'t exist. Try one of these instead, or call NDS and we\'ll help.'],
            'home_button' => ['text', 'Home button', 'Go to the home page'],
            'call_button' => ['text', 'Call button', 'Call NDS'],
            'cards' => ['list', 'Suggestion cards', ['Residential Trash & Recycling | /residential/ | Pickup days and schedules for your community.', 'Dumpster Rentals | /dumpster-rentals/ | Sizes, pricing questions, and rental requests.', 'Make a Payment | /make-a-payment/ | Pay your NDS bill online.'], 'One card per line, written as Title | /page-address/ | Short description'],
            'card_link' => ['text', 'Card link text', 'Go to', 'Shown before each card title, e.g. "Go to Dumpster Rentals".'],
        ]],
    ];
}

/** Text for a site-wide field: the saved value, or the built-in wording. */
function ndses_sw(string $section, string $field): string
{
    $value = function_exists('get_field') ? get_field("field_ndses_sw_{$section}_{$field}", 'ndses-site-settings') : null;
    if (is_string($value) && trim($value) !== '') {
        return $value;
    }
    $default = ndses_sitewide_spec()[$section]['fields'][$field][2] ?? '';

    return is_array($default) ? implode("\n", $default) : (string) $default;
}

/** Image URL for a site-wide image field, or ''. */
function ndses_sw_image(string $section, string $field): string
{
    $value = function_exists('get_field') ? get_field("field_ndses_sw_{$section}_{$field}", 'ndses-site-settings') : null;

    return is_array($value) && !empty($value['url']) ? (string) $value['url'] : '';
}

/** [['label', 'url', 'text'], ...] from a "Label | /path/ | text" list. */
function ndses_sw_links(string $section, string $field): array
{
    $links = [];
    foreach (ndses_lines(ndses_sw($section, $field)) as $line) {
        $parts = array_map('trim', explode('|', $line));
        if (($parts[0] ?? '') === '') {
            continue;
        }
        $path = $parts[1] ?? '/';
        $url = preg_match('#^(https?:|mailto:|tel:)#i', $path) ? $path : home_url('/' . ltrim($path, '/'));
        $links[] = ['label' => $parts[0], 'url' => ndses_slash_url($url), 'text' => $parts[2] ?? ''];
    }

    return $links;
}

/** Header/footer logo URL: uploaded in Site Settings, else the custom logo, else the built-in file. */
function ndses_logo_url(string $where): string
{
    $value = ndses_setting($where === 'footer' ? 'footer_logo' : 'header_logo');
    if ($where === 'footer' && !(is_array($value) && !empty($value['url']))) {
        $value = ndses_setting('header_logo');
    }
    if (is_array($value) && !empty($value['url'])) {
        return (string) $value['url'];
    }
    $custom = get_theme_mod('custom_logo');
    if ($custom && ($url = wp_get_attachment_image_url((int) $custom, 'full'))) {
        return (string) $url;
    }

    return ndses_asset('nds-logo-primary.png');
}

/* ---------------------------------------------------------------------
 * Town pages (Communities)
 * ------------------------------------------------------------------- */

const NDSES_TOWN_DOC_SLOTS = 6;

/** The per-town fields: name => [type, label, instructions]. */
function ndses_town_spec(): array
{
    $spec = [
        '_tab_intro' => ['tab', 'Intro & Schedule'],
        'community_type' => ['select', 'Town or city', 'The small label above the name on the Residential page.'],
        'town_intro' => ['textarea', 'Intro paragraph', 'Shown under the town name at the top of the page.'],
        'service_day' => ['text', 'Service day', 'e.g. Tuesday. Also shown on this town\'s card on the Residential page and in the menu.'],
        'trash_schedule' => ['text', 'Trash schedule', 'e.g. Weekly'],
        'recycling_schedule' => ['text', 'Recycling schedule', 'e.g. Every Other Week (see the service calendar below)'],
        '_tab_guidelines' => ['tab', 'Service Guidelines'],
        'guidelines' => ['list', 'Service guidelines', 'One rule per line.'],
        '_tab_bulk' => ['tab', 'Bulk Items'],
        'bulk_policy' => ['textarea', 'Bulk item text', 'The paragraph at the top of the Bulk Item Guidance box.'],
        'bulk_accepted' => ['list', 'Bulk items accepted', 'One item per line.'],
        'bulk_not_accepted' => ['list', 'Bulk items not accepted', 'One item per line.'],
        '_tab_recycling' => ['tab', 'Recycling'],
        'recycling_accepted' => ['list', 'Recycling accepted', 'One item per line.'],
        'recycling_not_accepted' => ['list', 'Recycling not accepted', 'One item per line.'],
        'recycling_never' => ['list', 'Never accepted', 'One item per line.'],
        '_tab_docs' => ['tab', 'Downloads'],
    ];
    for ($i = 1; $i <= NDSES_TOWN_DOC_SLOTS; $i++) {
        $spec["doc{$i}_title"] = ['text', "Download {$i}: title", $i === 1 ? 'Calendars, flyers and guides shown under "Printable schedules & guides". Leave a title empty to hide that download.' : ''];
        $spec["doc{$i}_file"] = ['file', "Download {$i}: file", 'PDF or image.'];
    }
    $spec['_tab_seo'] = ['tab', 'Search (Google)'];
    $spec['seo_title'] = ['text', 'SEO title', 'Optional. The title shown in Google results. Leave empty to use "[Town] Trash & Recycling".'];
    $spec['seo_description'] = ['textarea', 'SEO description', 'Optional. The grey text under the title in Google results.'];

    return $spec;
}

/** Built-in copy for a town: its data.php entry, or a starting point for a brand-new town. */
function ndses_town_seed(string $slug, string $name = ''): array
{
    $communities = ndses_data()['communities'];
    if (isset($communities[$slug])) {
        return $communities[$slug];
    }
    $base = $communities['town-of-delavan'] ?? reset($communities);
    $name = $name ?: ucwords(str_replace('-', ' ', $slug));

    return array_merge($base, [
        'name' => $name,
        'type' => stripos($name, 'city') === 0 ? 'City' : (stripos($name, 'village') === 0 ? 'Village' : 'Town'),
        'service_day' => '',
        'trash_schedule' => '',
        'recycling_schedule' => '',
        'documents' => [],
    ]);
}

/** A field's built-in value for a town, in the shape ACF stores it. */
function ndses_town_seed_value(array $seed, string $name)
{
    $map = [
        'community_type' => $seed['type'] ?? 'Town',
        'town_intro' => $seed['description'] ?? '',
        'service_day' => $seed['service_day'] ?? '',
        'trash_schedule' => $seed['trash_schedule'] ?? '',
        'recycling_schedule' => $seed['recycling_schedule'] ?? '',
        'guidelines' => implode("\n", $seed['guidelines'] ?? []),
        'bulk_policy' => $seed['bulk_policy'] ?? '',
        'bulk_accepted' => implode("\n", $seed['bulk_accepted'] ?? []),
        'bulk_not_accepted' => implode("\n", $seed['bulk_not_accepted'] ?? []),
        'recycling_accepted' => implode("\n", $seed['accepted_recycling'] ?? []),
        'recycling_not_accepted' => implode("\n", $seed['recycling_not_accepted'] ?? []),
        'recycling_never' => implode("\n", $seed['recycling_never_accepted'] ?? []),
    ];
    if (array_key_exists($name, $map)) {
        return $map[$name];
    }
    if (preg_match('/^doc(\d+)_(title|file)$/', $name, $m)) {
        $doc = $seed['documents'][(int) $m[1] - 1] ?? null;
        if (!$doc) {
            return null;
        }
        if ($m[2] === 'title') {
            return $doc['title'];
        }
        $id = attachment_url_to_postid(ndses_town_doc_url($doc['url']));

        return $id ?: null;
    }

    return null;
}

/** Built-in download addresses point at the uploads folder on whatever domain the site is on. */
function ndses_town_doc_url(string $url): string
{
    return preg_replace('#^https?://[^/]+/wp-content/uploads#', wp_upload_dir()['baseurl'], $url) ?? $url;
}

/** Fill never-saved town fields with the town's current copy (admin and front end alike). */
add_filter('acf/load_value', function ($value, $post_id, $field) {
    if (!is_numeric($post_id) || get_post_type((int) $post_id) !== 'community' || !str_starts_with((string) ($field['key'] ?? ''), 'field_ndses_town_')) {
        return $value;
    }
    if (metadata_exists('post', (int) $post_id, $field['name'])) {
        return $value;
    }
    $post = get_post((int) $post_id);
    $seed = ndses_town_seed((string) $post->post_name, (string) $post->post_title);
    $seeded = ndses_town_seed_value($seed, $field['name']);

    return $seeded ?? $value;
}, 10, 3);

function ndses_register_town_fields(): void
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    $fields = [];
    foreach (ndses_town_spec() as $name => $spec) {
        [$type, $label] = $spec;
        $key = 'field_ndses_town_' . ltrim($name, '_');
        $field = ['key' => $key, 'label' => $label, 'name' => str_starts_with($name, '_') ? '' : $name, 'instructions' => $spec[2] ?? ''];
        if ($type === 'tab') {
            $field += ['type' => 'tab', 'placement' => 'top'];
        } elseif ($type === 'select') {
            $field += ['type' => 'select', 'choices' => ['Town' => 'Town', 'City' => 'City', 'Village' => 'Village'], 'default_value' => 'Town', 'return_format' => 'value'];
        } elseif ($type === 'file') {
            $field += ['type' => 'file', 'return_format' => 'array', 'library' => 'all', 'mime_types' => 'pdf,jpg,jpeg,png,webp'];
        } elseif ($type === 'list') {
            $field += ['type' => 'textarea', 'rows' => 7, 'new_lines' => ''];
        } elseif ($type === 'textarea') {
            $field += ['type' => 'textarea', 'rows' => 3, 'new_lines' => ''];
        } else {
            $field += ['type' => 'text'];
        }
        $fields[] = $field;
    }

    acf_add_local_field_group([
        'key' => 'group_ndses_town_page',
        'title' => 'Town Page',
        'fields' => $fields,
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'community']]],
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'description' => 'Everything on this town\'s page. The town name is the title at the top. Headings shared by all town pages are under Pages → Residential → Town Pages.',
        'hide_on_screen' => ['the_content', 'excerpt', 'featured_image'],
    ]);

    // Site-wide Text & Images, on the Site Settings screen.
    $fields = [];
    foreach (ndses_sitewide_spec() as $section => $section_spec) {
        $fields[] = ['key' => "field_ndses_sw_{$section}_tab", 'label' => $section_spec['label'], 'name' => '', 'type' => 'tab', 'placement' => 'top'];
        foreach ($section_spec['fields'] as $name => $spec) {
            [$type, $label, $default] = $spec;
            $field = ['key' => "field_ndses_sw_{$section}_{$name}", 'label' => $label, 'name' => "sw_{$section}_{$name}", 'instructions' => $spec[3] ?? ''];
            if ($type === 'image') {
                $field += ['type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium', 'library' => 'all'];
            } elseif ($type === 'list') {
                $field += ['type' => 'textarea', 'rows' => max(3, count($default)), 'new_lines' => '', 'default_value' => implode("\n", $default)];
            } elseif ($type === 'textarea') {
                $field += ['type' => 'textarea', 'rows' => 2, 'new_lines' => '', 'default_value' => $default];
            } else {
                $field += ['type' => 'text', 'default_value' => $default];
            }
            $fields[] = $field;
        }
    }
    acf_add_local_field_group([
        'key' => 'group_ndses_sitewide_text',
        'title' => 'Site-wide Text & Images',
        'fields' => $fields,
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'ndses-site-settings']]],
        'menu_order' => 1,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'description' => 'Wording and pictures that appear on every page. Leave a box empty to keep the built-in wording.',
    ]);
}
add_action('acf/include_fields', 'ndses_register_town_fields');

/**
 * On Community screens the older shared "Content Library" boxes are
 * replaced by the Town Page group above, so hide them there.
 */
add_filter('acf/prepare_field', function ($field) {
    if (!is_array($field) || !is_admin() || ($field['parent'] ?? '') !== 'group_ndses_content_library') {
        return $field;
    }
    global $typenow;
    $type = $typenow ?: (isset($_GET['post']) ? get_post_type((int) $_GET['post']) : '');

    return $type === 'community' ? false : $field;
}, 20);

/** The old Services screen isn't used by any page; keep it out of the menu. */
add_filter('register_post_type_args', function (array $args, string $post_type): array {
    if ($post_type === 'service') {
        $args['show_in_menu'] = false;
    }

    return $args;
}, 10, 2);

/** Town list for the Residential page, menu and town pages, from the Communities screen. */
function ndses_build_communities(): array
{
    $seeded = ndses_data()['communities'];
    if (!function_exists('get_field')) {
        return $seeded;
    }
    $posts = get_posts([
        'post_type' => 'community',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => ['menu_order' => 'ASC', 'ID' => 'ASC'],
        'no_found_rows' => true,
    ]);
    if (!$posts) {
        return $seeded;
    }

    $communities = [];
    foreach ($posts as $post) {
        $slug = $post->post_name;
        $seed = ndses_town_seed($slug, $post->post_title);
        // Look fields up by key: a never-saved field has no name reference yet.
        $get = static fn (string $name) => get_field('field_ndses_town_' . $name, $post->ID);
        $text = static fn (string $name): string => trim((string) $get($name));
        $lines = static fn (string $name): array => ndses_lines((string) $get($name));

        $documents = [];
        for ($i = 1; $i <= NDSES_TOWN_DOC_SLOTS; $i++) {
            $title = $text("doc{$i}_title");
            if ($title === '') {
                continue;
            }
            $file = $get("doc{$i}_file");
            $url = is_array($file) ? (string) ($file['url'] ?? '') : '';
            // A download that was never saved and whose file isn't in the
            // media library yet keeps using its original address.
            if ($url === '' && !metadata_exists('post', $post->ID, "doc{$i}_file")) {
                $url = ndses_town_doc_url((string) ($seed['documents'][$i - 1]['url'] ?? ''));
            }
            if ($url !== '') {
                $documents[] = ['title' => $title, 'url' => $url];
            }
        }

        $communities[$slug] = array_merge($seed, [
            'name' => get_the_title($post),
            'type' => $text('community_type') ?: ($seed['type'] ?? 'Town'),
            'description' => $text('town_intro'),
            'service_day' => $text('service_day'),
            'trash_schedule' => $text('trash_schedule'),
            'recycling_schedule' => $text('recycling_schedule'),
            'guidelines' => $lines('guidelines'),
            'bulk_policy' => $text('bulk_policy'),
            'bulk_accepted' => $lines('bulk_accepted'),
            'bulk_not_accepted' => $lines('bulk_not_accepted'),
            'accepted_recycling' => $lines('recycling_accepted'),
            'recycling_not_accepted' => $lines('recycling_not_accepted'),
            'recycling_never_accepted' => $lines('recycling_never'),
            'documents' => $documents,
        ]);
    }

    return $communities;
}

/** Town screens: the emptied Content Library box and Featured image do nothing there. */
add_action('admin_head', function () {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ($screen && $screen->post_type === 'community' && $screen->base === 'post') {
        echo '<style>#acf-group_ndses_content_library,#postimagediv{display:none!important}</style>';
    }
});

/** Show "Site-wide Text & Images" on the Site Settings screen (an acf_form() with a fixed group list). */
add_filter('acf/validate_form', function (array $args): array {
    $groups = (array) ($args['field_groups'] ?? []);
    if (($args['post_id'] ?? '') === 'ndses-site-settings' && in_array('group_ndses_site_settings', $groups, true) && !in_array('group_ndses_sitewide_text', $groups, true)) {
        array_splice($groups, array_search('group_ndses_site_settings', $groups, true) + 1, 0, ['group_ndses_sitewide_text']);
        $args['field_groups'] = $groups;
    }

    return $args;
});

/** Flatten a list saved in an older format (array rows) into one item per line. */
function ndses_flatten_lines($value): string
{
    if (!is_array($value)) {
        return (string) $value;
    }
    $lines = [];
    array_walk_recursive($value, static function ($item) use (&$lines) {
        $item = trim((string) $item);
        if ($item !== '') {
            $lines[] = $item;
        }
    });

    return implode("\n", $lines);
}

/** Text boxes on the Town Page never receive an array, on screen or on the site. */
add_filter('acf/load_value', function ($value, $post_id, $field) {
    if (is_array($value) && str_starts_with((string) ($field['key'] ?? ''), 'field_ndses_town_') && in_array($field['type'] ?? '', ['text', 'textarea', 'select'], true)) {
        return ndses_flatten_lines($value);
    }

    return $value;
}, 20, 3);
