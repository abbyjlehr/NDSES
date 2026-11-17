<?php
/**
 * Google Tag Manager. The container ID is entered in Site Settings >
 * Analytics. It only loads on the real domain, so visits to the Flywheel
 * test address (and local copies) never reach Google Analytics.
 *
 * The site itself only pushes two events to the data layer (see theme.js):
 * ndses_form_submit and ndses_payment_complete. GA4 tags for them live in
 * the GTM container, not here.
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Hosts where analytics is allowed to load. */
function ndses_analytics_hosts(): array
{
    return apply_filters('ndses_analytics_hosts', ['ndses.com', 'www.ndses.com']);
}

/** The GTM container ID when it should load on this request, otherwise ''. */
function ndses_gtm_id(): string
{
    if (is_admin() || is_preview() || is_customize_preview()) {
        return '';
    }
    $id = strtoupper(trim((string) ndses_setting('gtm_id', '')));
    if (!preg_match('/^GTM-[A-Z0-9]{4,12}$/', $id)) {
        return '';
    }
    $host = strtolower((string) wp_parse_url(home_url(), PHP_URL_HOST));

    return in_array($host, ndses_analytics_hosts(), true) ? $id : '';
}

add_action('wp_head', function () {
    $id = ndses_gtm_id();
    if ($id === '') {
        return;
    }
    ?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',<?php echo wp_json_encode($id); ?>);</script>
<!-- End Google Tag Manager -->
    <?php
}, 2);

add_action('wp_body_open', function () {
    $id = ndses_gtm_id();
    if ($id === '') {
        return;
    }
    printf(
        '<noscript><iframe src="%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n",
        esc_url('https://www.googletagmanager.com/ns.html?id=' . rawurlencode($id))
    );
});
