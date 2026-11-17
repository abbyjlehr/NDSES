<?php
/**
 * Editable page sections.
 *
 * ndses_page_sections() is the single list of every page's sections and the
 * text/list/image fields inside each one, with today's copy as the default.
 * It drives both sides:
 *
 *  - wp-admin: each page gets a "<Page> Sections" ACF box with one tab per
 *    section, in the order the sections appear on the page, pre-filled with
 *    the current copy (ndses_register_page_section_fields()).
 *  - templates: ndses_sec() / ndses_sec_lines() / ndses_sec_image() return
 *    what the editor saved, or the default when the field is empty, so the
 *    page never renders blank.
 *
 * Saved values are stored as "<section>_<field>" on the page itself.
 * Free ACF only: lists are textareas with one item per line.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Field spec: [type, label, default, instructions?]. Types: text, textarea,
 * list (one item per line), image (default is ['url' => ..., 'width' => ...,
 * 'height' => ..., 'alt' => ...] or null for none).
 */
function ndses_page_sections(): array
{
    static $sections = null;
    if ($sections !== null) {
        return $sections;
    }

    $data = ndses_data();
    $services = $data['services'];
    $story_note = 'The story text itself is edited under Site Settings → About Page: Our Story.';
    $mission_note = 'The mission text itself is edited under Site Settings → Mission Statement.';

    $sections = [
        'home' => [
            'title' => 'Home',
            'sections' => [
                'services' => ['label' => 'Our Services', 'fields' => [
                    'heading' => ['text', 'Heading', 'Choose the service you need'],
                    'card1_title' => ['text', 'Card 1 title (links to Residential)', $services[0]['title']],
                    'card1_body' => ['textarea', 'Card 1 text', $services[0]['body']],
                    'card2_title' => ['text', 'Card 2 title (links to Commercial)', $services[1]['title']],
                    'card2_body' => ['textarea', 'Card 2 text', $services[1]['body']],
                    'card3_title' => ['text', 'Card 3 title (links to Dumpster Rentals)', $services[2]['title']],
                    'card3_body' => ['textarea', 'Card 3 text', $services[2]['body']],
                    'link_label' => ['text', 'Card link text', 'Explore service'],
                ]],
                'autopay' => ['label' => 'Automatic Payments', 'fields' => [
                    'heading' => ['text', 'Heading', 'Set up automatic payments'],
                    'body' => ['textarea', 'Text', 'Pay your NDS bill automatically from your checking or savings account. Download the authorization form, fill it out, attach a voided check, and return it to the NDS office.'],
                    'button' => ['text', 'Download button text', 'Download ACH form (PDF)'],
                    'file' => ['file', 'ACH authorization form (PDF)', get_theme_file_uri('/assets/docs/nds-ach-authorization-form.pdf'), 'Upload a new PDF here to replace the form visitors download.'],
                    'link_label' => ['text', 'Second link text', 'Or pay a bill online'],
                ]],
                'why' => ['label' => 'Why NDS', 'fields' => [
                    'heading' => ['text', 'Heading', 'Waste solutions from a local team that knows the routes'],
                    'body' => ['textarea', 'Text', "We understand that effective waste management is critical to the quality of life in Wisconsin's towns and cities. Whether you're looking for dependable residential trash pickup or tailored commercial waste solutions, NDS is committed to responsive service."],
                    'mission_heading' => ['text', 'Mission box heading', 'Our Mission', $mission_note],
                ]],
                'sizes' => ['label' => 'Dumpster Sizes', 'fields' => [
                    'heading' => ['text', 'Heading', 'Primary roll-off sizes at a glance'],
                    'button' => ['text', 'Button text', 'Open Calculator', 'The size cards come from Dumpster Sizes in the left menu.'],
                ]],
                'faq' => ['label' => 'FAQs', 'fields' => [
                    'heading' => ['text', 'Heading', 'Frequently asked questions', 'The questions come from FAQs in the left menu (Dumpster Rentals category).'],
                ]],
            ],
        ],
        'residential' => [
            'title' => 'Residential',
            'sections' => [
                'communities' => ['label' => 'Communities', 'fields' => [
                    'heading' => ['text', 'Heading', 'Select your community', 'The community cards come from Communities in the left menu.'],
                    'link_label' => ['text', 'Card link text', 'View details'],
                ]],
                'pickup' => ['label' => 'Pickup Expectations', 'fields' => [
                    'heading' => ['text', 'Heading', 'Simple rules that keep collection moving'],
                    'list' => ['list', 'Rules (one per line)', ['Bag all trash.', 'Place materials roadside by 5:30am.', 'Keep cans under 60 pounds.', 'Check the calendar for holiday delays.']],
                ]],
                'recycling' => ['label' => 'Accepted Recycling', 'fields' => [
                    'heading' => ['text', 'Box heading', 'Accepted recycling'],
                    'list' => ['list', 'Accepted items (one per line)', $data['communities']['town-of-delavan']['accepted_recycling']],
                ]],
            ],
        ],
        'commercial' => [
            'title' => 'Commercial',
            'sections' => [
                'areas' => ['label' => 'Areas We Service', 'fields' => [
                    'heading' => ['text', 'Heading', 'Commercial service across select Southern Wisconsin counties'],
                    'list' => ['list', 'Counties (one per line)', ['Walworth County', 'Rock County', 'Jefferson County', 'Waukesha County', 'Kenosha County']],
                    'note' => ['textarea', 'Note under the list', 'We service select areas of each county. Please call {phone} to see if we service your area.', 'Write {phone} where the office phone number from Site Settings should appear.'],
                    'image' => ['image', 'County map image', ['url' => 'https://ndses.flywheelsites.com/wp-content/uploads/2026/09/commercial-county-map.png', 'width' => 1300, 'height' => 842, 'alt' => 'Map of Walworth, Rock, Jefferson, Waukesha, and Kenosha counties served by NDS Environmental Solutions']],
                ]],
                'schedules' => ['label' => 'Collection Schedules', 'fields' => [
                    'heading' => ['text', 'Box heading', 'Flexible collection schedules'],
                    'list' => ['list', 'Schedules (one per line)', ['Monthly', 'Every other week', '2 times per week', '3 times per week', '4 times per week', '5 times per week']],
                ]],
                'containers' => ['label' => 'Container Options', 'fields' => [
                    'heading' => ['text', 'Heading', 'Find the right size dumpster for your business', 'The container cards come from Commercial Containers in the left menu.'],
                ]],
                'recycling' => ['label' => 'Commercial Recycling', 'fields' => [
                    'heading' => ['text', 'Heading', 'Commercial recycling'],
                    'body' => ['textarea', 'Text', 'Recycling services can be paired with your trash services.'],
                    'list' => ['list', 'Materials (one per line)', ['Cardboard', 'Paper', 'Plastic', 'Aluminum', 'Mixed recycling']],
                ]],
                'industries' => ['label' => 'Industries We Serve', 'fields' => [
                    'heading' => ['text', 'Heading', 'Industries we serve'],
                    'list' => ['list', 'Industries (one per line)', ['Restaurants', 'Offices', 'Retail Stores', 'Apartment Complexes', 'Schools', 'Manufacturing', 'Churches']],
                ]],
                'quote' => ['label' => 'Quote Form', 'fields' => [
                    'heading' => ['text', 'Heading', 'Need commercial service priced?'],
                    'body' => ['textarea', 'Text', 'Tell NDS about your business and the team will help choose the right container and schedule.'],
                ]],
            ],
        ],
        'dumpster-rentals' => [
            'title' => 'Dumpster Rentals',
            'sections' => [
                'sizes' => ['label' => 'Size Guide', 'fields' => [
                    'heading' => ['text', 'Heading', 'Find the right size dumpster for you', 'The size cards come from Dumpster Sizes in the left menu.'],
                    'button' => ['text', 'Button text', 'Use Calculator'],
                ]],
                'materials' => ['label' => 'Materials', 'fields' => [
                    'accepted_heading' => ['text', 'Accepted heading', 'Accepted Materials'],
                    'accepted_list' => ['list', 'Accepted materials (one per line)', $data['accepted_materials']],
                    'not_heading' => ['text', 'Not accepted heading', 'Not Accepted Materials'],
                    'not_list' => ['list', 'Not accepted materials (one per line)', $data['prohibited_materials']],
                ]],
                'process' => ['label' => 'Rental Process', 'fields' => [
                    'heading' => ['text', 'Heading', 'Four simple steps'],
                    'step1_title' => ['text', 'Step 1 title', 'Request A Quote'],
                    'step1_body' => ['textarea', 'Step 1 text', 'Tell us about your project so we can help you find the dumpster that fits your needs.'],
                    'step2_title' => ['text', 'Step 2 title', 'Schedule Delivery'],
                    'step2_body' => ['textarea', 'Step 2 text', 'Call NDS to schedule delivery at the right location.'],
                    'step3_title' => ['text', 'Step 3 title', 'Fill It Up'],
                    'step3_body' => ['textarea', 'Step 3 text', 'Fill the dumpster during your 15 day rental period.'],
                    'step4_title' => ['text', 'Step 4 title', 'We Pick It Up'],
                    'step4_body' => ['textarea', 'Step 4 text', 'Call when you are done and NDS will haul everything away.'],
                ]],
                'quote' => ['label' => 'Quote Form', 'fields' => [
                    'heading' => ['text', 'Heading', 'Request a dumpster'],
                    'body' => ['textarea', 'Text', 'Arriving from "Request This Size"? The size you picked carries over automatically.'],
                ]],
            ],
        ],
        'dumpster-calculator' => [
            'title' => 'Dumpster Calculator',
            'sections' => [
                'result' => ['label' => 'Recommendation Box', 'fields' => [
                    'eyebrow' => ['text', 'Label', 'Recommendation'],
                    'heading' => ['text', 'Starting heading', 'Start with the 15 Yard Dumpster', 'Shown before a visitor picks a project. After they click Recommend a Size, the heading shows the recommended size.'],
                    'body' => ['textarea', 'Starting text', 'Choose project details to update the recommendation. You can still compare all sizes below.'],
                    'note' => ['textarea', 'Small print', 'This is a 15 day rental. If you use a credit card for payment, there will be a 3% fee. Dumpster dimensions may vary based on different manufacturers.', 'Stays the same after a recommendation is shown.'],
                    'button' => ['text', 'Button text', 'Request Quote'],
                ]],
                'compare' => ['label' => 'Compare Sizes', 'fields' => [
                    'heading' => ['text', 'Heading', 'Compare every dumpster size', 'The size cards come from Dumpster Sizes in the left menu.'],
                ]],
            ],
        ],
        'special-events' => [
            'title' => 'Special Events',
            'sections' => [
                'options' => ['label' => 'Event Options', 'fields' => [
                    'heading' => ['text', 'Heading', 'Event service support'],
                    'card1_title' => ['text', 'Card 1 title', 'Community events'],
                    'card1_body' => ['textarea', 'Card 1 text', 'Temporary container arrangements and collection planning.'],
                    'card2_title' => ['text', 'Card 2 title', 'Corporate events and weddings'],
                    'card2_body' => ['textarea', 'Card 2 text', 'Clean, organized waste collection support for guest-facing events.'],
                    'card3_title' => ['text', 'Card 3 title', 'Cleanup support'],
                    'card3_body' => ['textarea', 'Card 3 text', 'Post-event collection needs and removal timing managed through quote requests.'],
                ]],
                'quote' => ['label' => 'Quote Form', 'fields' => [
                    'heading' => ['text', 'Heading', 'Plan containers and cleanup support'],
                    'body' => ['textarea', 'Text', 'Use this form for festivals, community events, corporate events, weddings, and temporary collection needs.'],
                ]],
            ],
        ],
        'about' => [
            'title' => 'About',
            'sections' => [
                'story' => ['label' => 'Our Story', 'fields' => [
                    'heading' => ['text', 'Heading', 'Our Story', $story_note],
                    'mission_heading' => ['text', 'Mission box heading', 'Our Mission', $mission_note],
                ]],
                'team' => ['label' => 'Meet The Team', 'fields' => [
                    'heading' => ['text', 'Heading', 'Meet the team', 'This section stays hidden until at least one card below has text.'],
                    'member1_name' => ['text', 'Card 1 name or role', 'Leadership'],
                    'member1_body' => ['textarea', 'Card 1 text', '', 'Leave empty to hide this card.'],
                    'member1_photo' => ['image', 'Card 1 photo', null],
                    'member2_name' => ['text', 'Card 2 name or role', 'Customer Service'],
                    'member2_body' => ['textarea', 'Card 2 text', '', 'Leave empty to hide this card.'],
                    'member2_photo' => ['image', 'Card 2 photo', null],
                    'member3_name' => ['text', 'Card 3 name or role', 'Operations'],
                    'member3_body' => ['textarea', 'Card 3 text', '', 'Leave empty to hide this card.'],
                    'member3_photo' => ['image', 'Card 3 photo', null],
                ]],
            ],
        ],
        'make-a-payment' => [
            'title' => 'Make a Payment',
            'sections' => [
                'form' => ['label' => 'Payment Form', 'fields' => [
                    'heading' => ['text', 'Form heading', 'Payment Form', 'The payment fields themselves are fixed so PayEngine keeps working.'],
                ]],
                'sidebar' => ['label' => 'Side Box', 'fields' => [
                    'heading' => ['text', 'Heading', 'ACH Bank Payment'],
                    'body' => ['textarea', 'Text', "Pay directly from a checking or savings account using PayEngine's secure bank-transfer flow."],
                    'note' => ['textarea', 'Small print', 'Card payments may include a 3% fee where applicable.'],
                ]],
            ],
        ],
        'contact' => [
            'title' => 'Contact',
            'sections' => [
                'card' => ['label' => 'Contact Box', 'fields' => [
                    'heading' => ['text', 'Heading', 'Contact Card', 'Address, phone, email, and hours come from Site Settings.'],
                    'hours_heading' => ['text', 'Hours heading', 'Office Hours'],
                ]],
            ],
        ],
    ];

    return $sections;
}

/** The page (post ID) a section slug lives on, or 0 if that page doesn't exist. */
function ndses_section_page_id(string $slug): int
{
    if ($slug === 'home') {
        return (int) get_option('page_on_front');
    }
    $page = get_page_by_path($slug);

    return $page ? (int) $page->ID : 0;
}

function ndses_section_spec(string $section, string $field, ?string $slug = null): ?array
{
    $slug = $slug ?: ndses_current_slug();

    return ndses_page_sections()[$slug]['sections'][$section]['fields'][$field] ?? null;
}

/** Raw saved value (or null) for a section field on its page. */
function ndses_section_value(string $section, string $field, ?string $slug = null)
{
    $slug = $slug ?: ndses_current_slug();
    $page_id = ndses_section_page_id($slug);
    if (!$page_id || !function_exists('get_field')) {
        return null;
    }
    $value = get_field($section . '_' . $field, $page_id);

    return ($value === null || $value === false || $value === '' || $value === []) ? null : $value;
}

/** Text for a section field: the saved value, or the default copy. */
function ndses_sec(string $section, string $field, ?string $slug = null): string
{
    $value = ndses_section_value($section, $field, $slug);
    if (is_string($value) && trim($value) !== '') {
        $text = $value;
    } else {
        $default = ndses_section_spec($section, $field, $slug)[2] ?? '';
        $text = is_array($default) ? implode("\n", $default) : (string) $default;
    }

    return str_replace('{phone}', (string) ndses_setting('phone'), $text);
}

/** List items (one per line) for a section field. */
function ndses_sec_lines(string $section, string $field, ?string $slug = null): array
{
    $lines = ndses_lines(ndses_sec($section, $field, $slug));
    if ($lines) {
        return $lines;
    }

    return ndses_lines(ndses_section_spec($section, $field, $slug)[2] ?? []);
}

/**
 * Image for a section field as ['url', 'alt', 'width', 'height'], or null
 * when neither an uploaded image nor a default exists.
 */
function ndses_sec_image(string $section, string $field, ?string $slug = null): ?array
{
    $value = ndses_section_value($section, $field, $slug);
    if (is_array($value) && !empty($value['url'])) {
        return [
            'url' => $value['url'],
            'alt' => $value['alt'] ?? '',
            'width' => $value['width'] ?? null,
            'height' => $value['height'] ?? null,
        ];
    }

    $default = ndses_section_spec($section, $field, $slug)[2] ?? null;

    return is_array($default) && !empty($default['url']) ? $default + ['alt' => '', 'width' => null, 'height' => null] : null;
}

/** URL for a section file field: the uploaded file, or the default. */
function ndses_sec_file(string $section, string $field, ?string $slug = null): string
{
    $value = ndses_section_value($section, $field, $slug);
    if (is_array($value) && !empty($value['url'])) {
        return (string) $value['url'];
    }
    if (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
        return $value;
    }

    return (string) (ndses_section_spec($section, $field, $slug)[2] ?? '');
}

/** Prints an <img> for a section image field, if there is one. */
function ndses_sec_img(string $section, string $field, string $class, string $fallback_alt = '', ?string $slug = null): void
{
    $image = ndses_sec_image($section, $field, $slug);
    if (!$image) {
        return;
    }
    printf(
        '<img class="%s" src="%s" alt="%s"%s%s loading="lazy" decoding="async">',
        esc_attr($class),
        esc_url($image['url']),
        esc_attr($image['alt'] ?: $fallback_alt),
        $image['width'] ? ' width="' . (int) $image['width'] . '"' : '',
        $image['height'] ? ' height="' . (int) $image['height'] . '"' : ''
    );
}

/**
 * Registers one "<Page> Sections" field group per page, shown only on that
 * page's edit screen, with a tab per section.
 */
function ndses_register_page_section_fields(): void
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    foreach (ndses_page_sections() as $slug => $page) {
        $page_id = ndses_section_page_id($slug);
        if (!$page_id) {
            continue;
        }

        $key_base = 'ndses_ps_' . str_replace('-', '_', $slug);
        $fields = [];
        foreach ($page['sections'] as $section => $section_spec) {
            $fields[] = [
                'key' => "field_{$key_base}_{$section}_tab",
                'label' => $section_spec['label'],
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
            ];
            foreach ($section_spec['fields'] as $name => $spec) {
                [$type, $label, $default] = $spec;
                $field = [
                    'key' => "field_{$key_base}_{$section}_{$name}",
                    'label' => $label,
                    'name' => "{$section}_{$name}",
                    'instructions' => $spec[3] ?? '',
                ];
                if ($type === 'file') {
                    $field += [
                        'type' => 'file',
                        'return_format' => 'array',
                        'library' => 'all',
                        'mime_types' => 'pdf',
                    ];
                    $field['instructions'] = trim($field['instructions'] . ' Leave empty to keep the current file.');
                } elseif ($type === 'image') {
                    $field += [
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ];
                    if ($default) {
                        $field['instructions'] = trim($field['instructions'] . ' Leave empty to keep the current image.');
                    }
                } elseif ($type === 'list') {
                    $field += ['type' => 'textarea', 'rows' => max(4, min(12, count($default))), 'new_lines' => '', 'default_value' => implode("\n", $default)];
                } elseif ($type === 'textarea') {
                    $field += ['type' => 'textarea', 'rows' => 3, 'new_lines' => '', 'default_value' => $default];
                } else {
                    $field += ['type' => 'text', 'default_value' => $default];
                }
                $fields[] = $field;
            }
        }

        acf_add_local_field_group([
            'key' => 'group_' . $key_base,
            'title' => $page['title'] . ' Page Sections',
            'fields' => $fields,
            'location' => [[['param' => 'page', 'operator' => '==', 'value' => (string) $page_id]]],
            'menu_order' => 1,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
            'description' => 'Each tab is one section of this page, top to bottom.',
            // These pages are built by the theme, so the main content
            // editor has no effect on them -- hide it to avoid confusion.
            'hide_on_screen' => ['the_content'],
            'show_in_rest' => 0,
        ]);
    }
}
add_action('acf/include_fields', 'ndses_register_page_section_fields');

/**
 * Theme-built pages (the ones with a Page Sections box) use the plain edit
 * screen, so the Hero and Page Sections boxes are the main thing on screen
 * instead of sitting in the block editor's collapsed "Meta Boxes" panel.
 * Free-form pages (Privacy Policy, Terms, ...) keep the block editor.
 */
function ndses_section_pages_use_classic_editor(bool $use_block_editor, $post): bool
{
    return ndses_section_slug_for_post($post) ? false : $use_block_editor;
}
add_filter('use_block_editor_for_post', 'ndses_section_pages_use_classic_editor', 10, 2);

/** The section slug (e.g. 'home', 'commercial') a page is, or '' if none. */
function ndses_section_slug_for_post($post): string
{
    $post = get_post($post);
    if (!$post || $post->post_type !== 'page') {
        return '';
    }
    foreach (array_keys(ndses_page_sections()) as $slug) {
        if ((int) $post->ID === ndses_section_page_id($slug)) {
            return $slug;
        }
    }

    return '';
}

/** The page being edited in wp-admin, if any. */
function ndses_admin_edited_post(): ?WP_Post
{
    if (!is_admin()) {
        return null;
    }
    $id = (int) ($_GET['post'] ?? $_POST['post_ID'] ?? 0);

    return $id ? get_post($id) : null;
}

/**
 * The main content editor does nothing on theme-built pages, so it's
 * removed from their edit screen (ACF's hide_on_screen only applies to the
 * first box on screen, which is the shared Page Hero & SEO box).
 */
add_action('load-post.php', function () {
    if (ndses_section_slug_for_post(ndses_admin_edited_post())) {
        remove_post_type_support('page', 'editor');
    }
});

/**
 * Pre-fills the Hero fields with what the page shows today (the theme's
 * built-in hero copy) so editors start from the current text, the same way
 * the Page Sections fields do.
 */
function ndses_prefill_hero_field(array $field): array
{
    $post = ndses_admin_edited_post();
    if (!$post || $post->post_type !== 'page') {
        return $field;
    }
    $slug = $post->ID === (int) get_option('page_on_front') ? 'home' : $post->post_name;
    $hero = ndses_data()['pages'][$slug] ?? null;

    if ($field['name'] === 'image' && $slug === 'home') {
        $field['instructions'] = 'Not used on the Home page: the Home banner always shows the NDS truck photo.';
    } elseif ($hero && isset($hero[$field['name']]) && is_string($hero[$field['name']])) {
        $field['default_value'] = $hero[$field['name']];
    }

    return $field;
}
foreach (['field_hero_eyebrow', 'field_hero_heading', 'field_hero_description', 'field_hero_image'] as $ndses_hero_key) {
    add_filter('acf/load_field/key=' . $ndses_hero_key, 'ndses_prefill_hero_field');
}
unset($ndses_hero_key);

/** Hero labels were removed from the design, so their field is hidden. */
add_filter('acf/prepare_field/key=field_hero_eyebrow', '__return_false');
