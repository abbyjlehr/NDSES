<?php
/**
 * Reusable template output helpers.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ndses_render_hero(?string $slug = null): void
{
    $hero = ndses_page_hero($slug);
    $image = $hero['image'] ?? '';
    $image_url = is_array($image) && isset($image['url']) ? $image['url'] : ($image ? ndses_asset((string) $image) : '');
    // Width/height (available whenever the image comes from an ACF image
    // field with return_format=array) reserve the image's box before it
    // loads, avoiding layout shift -- this is the largest image on the
    // page and almost always the Largest Contentful Paint element, so it
    // loads eagerly with high priority rather than lazily.
    $width = is_array($image) ? ($image['width'] ?? null) : null;
    $height = is_array($image) ? ($image['height'] ?? null) : null;

    $is_home = $slug === 'home';
    $image_alt = ($hero['heading'] ?? 'NDS Environmental Solutions') . ' visual';

    // Home: always the Delavan lakefront truck photo. The Home page's Hero
    // image (and its built-in fallback, a sign proof) is intentionally not used.
    if ($is_home) {
        $image_url = ndses_asset('home-hero-truck.jpg');
        $width = 1672;
        $height = 941;
        $image_alt = 'NDS Environmental Solutions garbage truck on a street in Delavan';
    }
    ?>
    <section class="hero-section<?php echo $is_home ? ' hero-section--home' : ''; ?>">
        <div class="container hero-grid<?php echo $image_url ? '' : ' hero-grid--no-media'; ?>">
            <div class="hero-copy">
                <h1><?php echo esc_html($hero['heading'] ?? get_the_title()); ?></h1>
                <p class="hero-text"><?php echo esc_html($hero['description'] ?? ''); ?></p>
                <div class="hero-actions">
                    <?php ndses_button('Get a Quote', home_url('/contact/')); ?>
                    <?php ndses_button('Call NDS', ndses_phone_href(), 'button button-secondary'); ?>
                    <?php if ($is_home || $slug === 'dumpster-rentals') : ?>
                        <a class="text-link hero-link" href="<?php echo esc_url(home_url('/dumpster-rentals/')); ?>">Rent a Dumpster</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($image_url) : ?>
            <div class="hero-media">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" <?php echo $width ? 'width="' . esc_attr((string) $width) . '"' : ''; ?> <?php echo $height ? 'height="' . esc_attr((string) $height) . '"' : ''; ?> fetchpriority="high" decoding="async">
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

/**
 * Hand-drawn line icons for the service cards -- avoids relying on stock
 * photography (see the note on ndses_data()['services']). Simple 2px
 * stroke style, deliberately restrained so it reads as clean/premium
 * rather than decorative.
 */
function ndses_service_icon(string $name): string
{
    $icons = [
        'residential' => '<path d="M8 28 L32 10 L56 28"/><path d="M14 24 V54 H50 V24"/><path d="M26 54 V38 H38 V54"/>',
        'commercial' => '<rect x="10" y="14" width="26" height="42" rx="1"/><rect x="38" y="24" width="16" height="32" rx="1"/><path d="M17 22h4M27 22h4M17 30h4M27 30h4M17 38h4M27 38h4M17 46h4M27 46h4M44 32h4M44 40h4M44 48h4"/>',
        'rolloff' => '<path d="M9 22 H55 L48 47 H16 Z"/><path d="M9 22 L4 15 M55 22 L60 15"/><circle cx="20" cy="52" r="4"/><circle cx="44" cy="52" r="4"/>',
    ];

    return $icons[$name] ?? '';
}

function ndses_render_service_cards(): void
{
    ?>
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <h2><?php echo esc_html(ndses_sec('services', 'heading', 'home')); ?></h2>
            </div>
            <div class="card-grid three">
                <?php foreach (ndses_data()['services'] as $index => $service) :
                    $service['title'] = ndses_sec('services', 'card' . ($index + 1) . '_title', 'home');
                    $service['body'] = ndses_sec('services', 'card' . ($index + 1) . '_body', 'home');
                    ?>
                    <article class="feature-card icon-card">
                        <div class="icon-card-media" aria-hidden="true">
                            <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><?php echo ndses_service_icon($service['icon']); ?></svg>
                        </div>
                        <div>
                            <h3><?php echo esc_html($service['title']); ?></h3>
                            <p><?php echo esc_html($service['body']); ?></p>
                            <a href="<?php echo esc_url(home_url($service['url'])); ?>" class="text-link"><?php echo esc_html(ndses_sec('services', 'link_label', 'home')); ?></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

function ndses_material_list_items(array $material_lists, string $heading): array
{
    foreach ($material_lists as $list) {
        if (($list['heading'] ?? '') === $heading) {
            return array_map(static fn ($row) => $row['item'] ?? '', $list['items'] ?? []);
        }
    }

    return [];
}

function ndses_render_dumpster_cards(): void
{
    $posts = function_exists('get_field')
        ? get_posts(['post_type' => 'dumpster_size', 'numberposts' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC', 'no_found_rows' => true])
        : [];

    if (empty($posts)) {
        ?>
        <div class="card-grid five">
            <?php foreach (ndses_data()['dumpsters'] as $dumpster) : ?>
                <article class="feature-card dumpster-card">
                    <img src="<?php echo ndses_asset($dumpster['image']); ?>" alt="<?php echo esc_attr($dumpster['name']); ?>" loading="lazy" decoding="async">
                    <p class="kicker"><?php echo esc_html($dumpster['capacity']); ?></p>
                    <h3><?php echo esc_html($dumpster['name']); ?></h3>
                    <?php ndses_list($dumpster['uses'], 'compact-list'); ?>
                    <?php if (!empty($dumpster['dimensions'])) : ?><p class="small-note"><strong>Dimensions:</strong> <?php echo esc_html($dumpster['dimensions']); ?></p><?php endif; ?>
                    <p class="small-note">Includes: <?php echo esc_html(implode(', ', $dumpster['included'])); ?>.</p>
                    <?php ndses_button('Request This Size', ndses_dumpster_request_url($dumpster['slug']), 'button button-small'); ?>
                </article>
            <?php endforeach; ?>
        </div>
        <?php
        return;
    }

    ?>
    <div class="card-grid five">
        <?php foreach ($posts as $dumpster_post) :
            $capacity = get_field('truck_loads', $dumpster_post->ID) ?: get_field('capacity', $dumpster_post->ID);
            $dimensions = get_field('dimensions', $dumpster_post->ID);
            $material_lists = get_field('material_lists', $dumpster_post->ID) ?: [];
            // One-per-line textareas (free ACF); the older material_lists
            // repeater headings are still honored if a site has ACF PRO.
            $uses = ndses_lines(get_field('ideal_uses', $dumpster_post->ID)) ?: ndses_material_list_items($material_lists, 'Ideal For');
            $included = ndses_lines(get_field('included', $dumpster_post->ID)) ?: ndses_material_list_items($material_lists, 'Included');
            $image = get_field('field_ndses_image', $dumpster_post->ID);
            $image_url = is_array($image) && !empty($image['url']) ? $image['url'] : '';
            // No graphic uploaded: use the theme's matching size graphic (10 Yard -> dumpster-10-yard.png).
            if (!$image_url && preg_match('/\b(\d+)\s*yard/i', get_the_title($dumpster_post), $m)
                && file_exists(get_theme_file_path('/assets/images/dumpster-' . $m[1] . '-yard.png'))) {
                $image_url = ndses_asset('dumpster-' . $m[1] . '-yard.png');
            }
            ?>
            <article class="feature-card dumpster-card">
                <?php if ($image_url) : ?><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title($dumpster_post)); ?>" loading="lazy" decoding="async"><?php endif; ?>
                <?php if ($capacity) : ?><p class="kicker"><?php echo esc_html($capacity); ?></p><?php endif; ?>
                <h3><?php echo esc_html(get_the_title($dumpster_post)); ?></h3>
                <?php if ($uses) : ?><?php ndses_list($uses, 'compact-list'); ?><?php endif; ?>
                <?php if ($dimensions) : ?><p class="small-note"><strong>Dimensions:</strong> <?php echo esc_html($dimensions); ?></p><?php endif; ?>
                <?php if ($included) : ?><p class="small-note">Includes: <?php echo esc_html(implode(', ', $included)); ?>.</p><?php endif; ?>
                <?php ndses_button('Request This Size', ndses_dumpster_request_url($dumpster_post->post_name), 'button button-small'); ?>
            </article>
        <?php endforeach; ?>
    </div>
    <?php
}

function ndses_render_faqs(?string $category = null): void
{
    $groups = ndses_get_faqs();
    if ($category && isset($groups[$category])) {
        $groups = [$category => $groups[$category]];
    }
    ?>
    <div class="faq-stack">
        <?php foreach ($groups as $label => $faqs) : ?>
            <section class="faq-group" id="<?php echo esc_attr(sanitize_title($label)); ?>">
                <h2><?php echo esc_html($label); ?></h2>
                <?php foreach ($faqs as $faq) : ?>
                    <details class="faq-item">
                        <summary><?php echo esc_html($faq[0]); ?></summary>
                        <div class="faq-answer"><?php echo wp_kses_post($faq[1]); ?></div>
                    </details>
                <?php endforeach; ?>
            </section>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Inquiry form shared by Contact, Commercial, Dumpster Rentals, and Special
 * Events. Submits (via the data-inquiry-form handler in theme.js) to the
 * ndses/v1/forms REST route, which stores the submission and emails the
 * office.
 *
 * $form_type is carried as a hidden field so submissions from each page are
 * distinguishable in the notification email and the Form Submissions list —
 * it identifies which page the visitor was on, not necessarily what they
 * picked in the Service Type dropdown.
 */
function ndses_render_inquiry_form(string $form_type): void
{
    $service_options = [
        '' => 'Select one',
        'Residential Trash & Recycling' => 'Residential Trash & Recycling',
        'Commercial Trash & Recycling' => 'Commercial Trash & Recycling',
        'Dumpster Rental' => 'Dumpster Rental',
        'Special Event Service' => 'Special Event Service',
        'General Question' => 'General Question',
    ];
    $default_service = [
        'commercial' => 'Commercial Trash & Recycling',
        'dumpster' => 'Dumpster Rental',
        'event' => 'Special Event Service',
    ][$form_type] ?? '';
    ?>
    <form class="site-form" id="<?php echo esc_attr($form_type); ?>-inquiry-form" method="post" data-inquiry-form novalidate>
        <input type="hidden" name="formType" value="<?php echo esc_attr($form_type); ?>">
        <div class="honeypot-field" aria-hidden="true">
            <label for="<?php echo esc_attr($form_type); ?>-website">Leave this field blank</label>
            <input id="<?php echo esc_attr($form_type); ?>-website" type="text" name="website" tabindex="-1" autocomplete="off">
        </div>
        <label>Name <input required type="text" name="name" autocomplete="name"></label>
        <div class="field-row">
            <label>Email <input required type="email" name="email" autocomplete="email"></label>
            <label>Phone <input required type="tel" name="phone" autocomplete="tel"></label>
        </div>
        <label>Service address <input type="text" name="serviceAddress"></label>
        <?php $has_pair = in_array($form_type, ['dumpster', 'event'], true); ?>
        <?php if ($has_pair) : ?><div class="field-row"><?php endif; ?>
        <label>Service type
            <select required name="serviceType">
                <?php foreach ($service_options as $value => $label) : ?>
                    <option value="<?php echo esc_attr($value); ?>" <?php selected($value, $default_service); ?>><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <?php if ($form_type === 'dumpster') : ?>
            <label>Selected dumpster size <input type="text" name="selectedDumpsterSize" data-dumpster-size-field></label>
        <?php endif; ?>
        <?php if ($form_type === 'event') : ?>
            <label>Event date <input type="date" name="eventDate"></label>
        <?php endif; ?>
        <?php if ($has_pair) : ?></div><?php endif; ?>
        <label>Message <textarea required minlength="10" name="message" rows="5"></textarea></label>
        <p class="form-status" role="status"></p>
        <button class="button button-primary" type="submit">Send Request</button>
    </form>
    <?php
}

/**
 * Frame of the coverage map (assets/images/service-area-map.svg): its pixel
 * size and the longitude/latitude range it was drawn with. Written by
 * scripts/build-service-area-map.cjs next to the SVG, so regenerating the
 * map regenerates these numbers.
 *
 * @return array{width:int,height:int,minLon:float,maxLon:float,minLat:float,maxLat:float}
 */
function ndses_map_bounds(): array
{
    static $bounds = null;
    if ($bounds !== null) {
        return $bounds;
    }

    $decoded = json_decode((string) @file_get_contents(get_theme_file_path('/assets/images/service-area-map-bounds.json')), true);
    $bounds = [];
    foreach (['width', 'height', 'minLon', 'maxLon', 'minLat', 'maxLat'] as $key) {
        $bounds[$key] = is_array($decoded) && isset($decoded[$key]) && is_numeric($decoded[$key]) ? (float) $decoded[$key] : 0.0;
    }
    $bounds['width'] = (int) $bounds['width'];
    $bounds['height'] = (int) $bounds['height'];

    return $bounds;
}

/**
 * Projects a latitude/longitude onto the coverage map as percentages of its
 * width and height, with the same linear projection the build script uses
 * to draw the SVG. Returns null for missing coordinates, a point outside
 * the map, or an unreadable bounds file, so the caller lists the area
 * without a pin.
 *
 * @return array{x:float,y:float}|null
 */
function ndses_project_map_pin(?float $lat, ?float $lon, array $bounds): ?array
{
    $lon_span = $bounds['maxLon'] - $bounds['minLon'];
    $lat_span = $bounds['maxLat'] - $bounds['minLat'];
    if ($lat === null || $lon === null || $lon_span <= 0 || $lat_span <= 0) {
        return null;
    }
    if ($lon < $bounds['minLon'] || $lon > $bounds['maxLon'] || $lat < $bounds['minLat'] || $lat > $bounds['maxLat']) {
        return null;
    }

    return [
        'x' => round(($lon - $bounds['minLon']) / $lon_span * 100, 2),
        'y' => round(($bounds['maxLat'] - $lat) / $lat_span * 100, 2),
    ];
}

/**
 * @param string[] $filter_types Optional subset of 'Residential', 'Commercial', 'Roll-off' to
 *                                include (e.g. the Commercial page shows Commercial + Roll-off
 *                                only). Omit to show every service area, as on Home and About.
 */
function ndses_render_service_area_map(array $filter_types = []): void
{
    $areas = ndses_get_service_areas();
    if ($filter_types) {
        $areas = array_filter($areas, static fn ($area) => array_intersect($area['types'], $filter_types));
    }

    // One map of the service area (county outlines from US Census data,
    // generated by scripts/build-service-area-map.cjs). Counties are labeled
    // on the map; residential communities get small numbered pins inside the
    // outlined residential area, matching the numbered list beside the map.
    // Each pin is projected from the town's latitude/longitude using the
    // bounds the build script publishes; a town without coordinates, or
    // outside the map, is listed without a pin.
    $bounds = ndses_map_bounds();
    $is_county = static fn (array $area): bool => (bool) preg_match('/county$/i', $area['name']);
    $towns = array_values(array_filter($areas, static fn ($area) => !$is_county($area)));
    $counties = array_values(array_filter($areas, $is_county));
    ?>
    <div class="service-map-wrap">
        <figure class="service-map">
            <div class="map-canvas">
                <img src="<?php echo ndses_asset('service-area-map.svg'); ?>" width="<?php echo (int) ($bounds['width'] ?: 800); ?>" height="<?php echo (int) ($bounds['height'] ?: 547); ?>" loading="lazy" decoding="async" alt="Map of southern Wisconsin showing the counties NDS serves (Walworth, Rock, Jefferson, Waukesha, and Kenosha) and the residential service area in southwestern Walworth County">
                <?php foreach ($towns as $index => $area) :
                    $pos = ndses_project_map_pin($area['lat'] ?? null, $area['lon'] ?? null, $bounds);
                    if (!$pos) {
                        continue;
                    }
                    ?>
                    <span class="map-pin" aria-hidden="true" style="left: <?php echo esc_attr((string) $pos['x']); ?>%; top: <?php echo esc_attr((string) $pos['y']); ?>%;"><b><?php echo (int) $index + 1; ?></b></span>
                <?php endforeach; ?>
            </div>
            <?php if ($towns) : ?>
                <figcaption><span class="map-key" aria-hidden="true"></span>Residential service area &middot; numbers match the list</figcaption>
            <?php endif; ?>
        </figure>
        <div class="map-list">
            <h2>Southern Wisconsin coverage</h2>
            <p>Residential service is concentrated in listed communities. Commercial and temporary roll-off service is available in select areas of nearby counties.</p>
            <?php if ($towns) : ?>
                <h3 class="map-list-heading">Residential communities</h3>
                <ol class="area-list">
                    <?php foreach ($towns as $index => $area) : ?>
                        <li>
                            <span class="map-pin static" aria-hidden="true"><b><?php echo (int) $index + 1; ?></b></span>
                            <span><strong><?php echo esc_html($area['name']); ?></strong><small><?php echo esc_html(implode(' · ', $area['types'])); ?></small></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
            <?php if ($counties) : ?>
                <h3 class="map-list-heading">Counties</h3>
                <ul class="county-list">
                    <?php foreach ($counties as $area) : ?>
                        <li><strong><?php echo esc_html($area['name']); ?></strong><small><?php echo esc_html(implode(' · ', $area['types'])); ?></small></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

function ndses_print_schema(): void
{
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => ndses_setting('company_name'),
        'telephone' => ndses_setting('phone'),
        'email' => ndses_setting('email'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '1635 Mound Rd',
            'addressLocality' => 'Delavan',
            'addressRegion' => 'WI',
            'postalCode' => '53115',
        ],
        'areaServed' => wp_strip_all_tags((string) ndses_setting('service_area')),
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}

