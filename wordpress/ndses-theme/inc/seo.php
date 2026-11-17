<?php
/**
 * Search and share metadata: <title>, meta description, Open Graph and
 * Twitter cards. Each page's "SEO Title" / "SEO Description" fields (Page
 * Hero & SEO in wp-admin) win; otherwise the page's hero heading and
 * description are used, so every page has a description even if nobody
 * fills the SEO fields in.
 */

if (!defined('ABSPATH')) {
    exit;
}

/** ['title' => ?string, 'description' => string, 'image' => string] for the current request. */
function ndses_seo(): array
{
    static $seo = null;
    if ($seo !== null) {
        return $seo;
    }

    $title = null;
    $description = '';
    $image = ndses_asset('home-hero-truck.jpg');

    $community_slug = get_query_var('ndses_community');
    if ($community_slug) {
        $community = ndses_get_communities()[$community_slug] ?? null;
        $post = get_page_by_path($community_slug, OBJECT, 'community');
        if ($community) {
            $title = ($post ? trim((string) ndses_acf('seo_title', $post->ID)) : '') ?: $community['name'] . ' Trash & Recycling';
            $description = ($post ? trim((string) ndses_acf('seo_description', $post->ID)) : '') ?: (string) ($community['description'] ?? '');
        }
    } elseif (is_front_page() || is_page()) {
        $hero = ndses_page_hero();
        $seo_title = trim((string) ndses_page_field('seo_title', ''));
        $title = $seo_title ?: (is_front_page() ? null : get_the_title());
        $description = trim((string) ndses_page_field('seo_description', '')) ?: (string) ($hero['description'] ?? '');
        $hero_image = $hero['image'] ?? '';
        if (!is_front_page() && is_array($hero_image) && !empty($hero_image['url'])) {
            $image = $hero_image['url'];
        }
    } elseif (is_404()) {
        $title = 'Page not found';
    }

    if ($description === '') {
        $description = (string) (ndses_data()['pages']['home']['description'] ?? '');
    }

    return $seo = [
        'title' => $title,
        'description' => wp_strip_all_tags($description),
        'image' => $image,
    ];
}

// Brand the site name in titles with the company name from Site Settings
// rather than the short WordPress site title.
add_filter('document_title_parts', function (array $parts): array {
    $seo = ndses_seo();
    if ($seo['title']) {
        $parts['title'] = $seo['title'];
    }
    $company = (string) ndses_setting('company_name');
    if (isset($parts['site']) && $company) {
        $parts['site'] = $company;
    }
    if (is_front_page() && $company) {
        $parts['title'] = $seo['title'] ?: $company;
        $parts['tagline'] = 'Trash, Recycling & Dumpster Rentals in Delavan, WI';
        unset($parts['site']);
    }
    return $parts;
});

add_filter('document_title_separator', static fn () => '|');

add_action('wp_head', function () {
    $seo = ndses_seo();
    $title = html_entity_decode(wp_get_document_title(), ENT_QUOTES, "UTF-8");
    $request = $GLOBALS['wp']->request ?? '';
    $url = is_404() ? '' : home_url('/' . ($request ? trailingslashit($request) : ''));
    $tags = [
        ['name', 'description', $seo['description']],
        ['property', 'og:type', is_front_page() ? 'website' : 'article'],
        ['property', 'og:site_name', (string) ndses_setting('company_name')],
        ['property', 'og:title', $title],
        ['property', 'og:description', $seo['description']],
        ['property', 'og:url', $url],
        ['property', 'og:image', $seo['image']],
        ['property', 'og:locale', 'en_US'],
        ['name', 'twitter:card', 'summary_large_image'],
    ];
    foreach ($tags as [$attr, $key, $value]) {
        if ($value !== '') {
            printf('<meta %s="%s" content="%s">' . "\n", $attr, esc_attr($key), esc_attr($value));
        }
    }
}, 1);

/**
 * Content Library entries (dumpster sizes, FAQs, notices, …) are shown on
 * the site's real pages, not on pages of their own. WordPress still gives
 * each one a URL, which would render a bare page with the home hero, so
 * those URLs, the unused blog, author and category archives, and media
 * attachment pages all redirect to the page where the content lives.
 */
function ndses_redirect_target(): ?string
{
    if (is_singular('community')) {
        return '/residential/' . get_post_field('post_name') . '/';
    }
    if (is_singular('service_area')) {
        $slug = get_post_field('post_name');
        return isset(ndses_get_communities()[$slug]) ? '/residential/' . $slug . '/' : '/about/';
    }
    if (is_singular('service')) {
        return [
            'residential-trash-recycling' => '/residential/',
            'commercial-trash-recycling' => '/commercial/',
            'dumpster-rentals' => '/dumpster-rentals/',
        ][get_post_field('post_name')] ?? '/';
    }
    if (is_singular('dumpster_size')) {
        return '/dumpster-rentals/';
    }
    if (is_singular('commercial_container')) {
        return '/commercial/';
    }
    if (is_singular('faq')) {
        return '/faqs/';
    }
    if (is_singular('service_notice') || is_post_type_archive('service_notice')) {
        return '/whats-new/';
    }
    if (is_singular(['document', 'schedule_item'])) {
        return '/residential/';
    }
    if (is_singular('post') || is_attachment() || is_author() || is_category() || is_tag() || is_date()) {
        return '/';
    }
    return null;
}

add_action('template_redirect', function () {
    if (is_admin() || is_preview()) {
        return;
    }
    $target = ndses_redirect_target();
    if ($target !== null) {
        wp_safe_redirect(home_url($target), 301);
        exit;
    }
});

// The sitemap lists the site's pages and each community page, nothing else.
add_filter('wp_sitemaps_post_types', static fn (array $types): array => array_intersect_key($types, ['page' => true]));
add_filter('wp_sitemaps_taxonomies', '__return_empty_array');
add_filter('wp_sitemaps_add_provider', static fn ($provider, string $name) => $name === 'users' ? false : $provider, 10, 2);

add_action('wp_sitemaps_init', function (WP_Sitemaps $sitemaps) {
    $sitemaps->registry->add_provider('communities', new class extends WP_Sitemaps_Provider {
        public function __construct()
        {
            $this->name = 'communities';
            $this->object_type = 'community';
        }

        public function get_url_list($page_num, $object_subtype = ''): array
        {
            return array_map(
                static fn (string $slug): array => ['loc' => home_url('/residential/' . $slug . '/')],
                array_keys(ndses_get_communities())
            );
        }

        public function get_max_num_pages($object_subtype = ''): int
        {
            return 1;
        }
    });
});

// No comments or pingbacks anywhere on the site.
add_filter('comments_open', '__return_false', 20);
add_filter('pings_open', '__return_false', 20);
add_filter('comments_array', '__return_empty_array', 20);
add_filter('xmlrpc_methods', static function (array $methods): array {
    unset($methods['pingback.ping'], $methods['pingback.extensions.getPingbacks']);
    return $methods;
});
add_filter('wp_headers', static function (array $headers): array {
    unset($headers['X-Pingback']);
    return $headers;
});

// Don't publish the admin login name: the public REST user list is only
// available to signed-in editors (the block editor uses it).
add_filter('rest_endpoints', static function (array $endpoints): array {
    if (!is_user_logged_in()) {
        unset($endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $endpoints;
});
