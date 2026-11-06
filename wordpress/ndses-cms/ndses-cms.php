<?php
/**
 * Plugin Name: NDSES CMS Structure
 * Description: Registers NDSES custom post types, ACF options pages, and local field groups.
 * Version: 0.2.4
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    $types = [
        'service' => ['Services', 'Service'],
        'community' => ['Communities', 'Community'],
        'dumpster_size' => ['Dumpster Sizes', 'Dumpster Size'],
        'commercial_container' => ['Commercial Containers', 'Commercial Container'],
        'faq' => ['FAQs', 'FAQ'],
        'document' => ['Documents', 'Document'],
        'service_notice' => ['Service Notices', 'Service Notice'],
        'service_area' => ['Service Areas', 'Service Area'],
        'schedule_item' => ['Schedule Items', 'Schedule Item'],
    ];

    foreach ($types as $slug => $labels) {
        register_post_type($slug, [
            'labels' => ['name' => $labels[0], 'singular_name' => $labels[1]],
            'public' => true,
            'show_in_rest' => true,
            'show_in_graphql' => true,
            'graphql_single_name' => str_replace('_', '', $slug),
            'graphql_plural_name' => str_replace('_', '', $slug) . 's',
            'menu_icon' => $slug === 'service_notice' ? 'dashicons-warning' : 'dashicons-admin-page',
            // page-attributes adds the "Order" box the theme sorts by.
            'supports' => ['title', 'editor', 'thumbnail', 'revisions', 'page-attributes'],
            'has_archive' => $slug === 'service_notice',
            'rewrite' => ['slug' => str_replace('_', '-', $slug)],
            // A public post type auto-registers its own slug as a global
            // query var (e.g. ?service=...), which silently hijacks any page
            // using that word as an ordinary URL param — as the inquiry
            // forms' ?service=dumpster&size=... prefill links did, 404ing
            // instead of reaching /contact. The post type's own permalinks
            // (/service/post-name/) still resolve correctly without it.
            'query_var' => false,
        ]);
    }

    register_post_type('form_submission', [
        'labels' => ['name' => 'Form Submissions', 'singular_name' => 'Form Submission'],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => ['title'],
        'capability_type' => 'post',
    ]);
});

/**
 * form_submission only supports('title'), so by default its edit screen
 * shows nothing but the auto-generated title -- the submitted message,
 * email, phone, etc. (stored in post_content / postmeta by
 * ndses_handle_form_submission()) are otherwise invisible in wp-admin,
 * findable only via the notification email or a database query. This adds
 * a read-only details box with everything that was actually submitted.
 */
add_action('add_meta_boxes_form_submission', function (WP_Post $post) {
    add_meta_box(
        'ndses_submission_details',
        'Submission Details',
        'ndses_render_submission_details_metabox',
        'form_submission',
        'normal',
        'high'
    );
});

function ndses_render_submission_details_metabox(WP_Post $post): void
{
    $rows = [
        'Name' => get_post_meta($post->ID, 'name', true),
        'Email' => get_post_meta($post->ID, 'email', true),
        'Phone' => get_post_meta($post->ID, 'Phone', true),
        'Form' => get_post_meta($post->ID, 'form_type', true),
        'Service type' => get_post_meta($post->ID, 'Service type', true),
        'Service address' => get_post_meta($post->ID, 'Service address', true),
        'Selected dumpster size' => get_post_meta($post->ID, 'Selected dumpster size', true),
        'Event date' => get_post_meta($post->ID, 'Event date', true),
    ];
    ?>
    <table class="widefat" style="border: none; box-shadow: none;">
        <tbody>
            <?php foreach ($rows as $label => $value) :
                if ($value === '') {
                    continue;
                }
                ?>
                <tr>
                    <th style="width: 180px; text-align: left; padding: 8px 12px 8px 0;"><?php echo esc_html($label); ?></th>
                    <td style="padding: 8px 0;">
                        <?php if ($label === 'Email') : ?>
                            <a href="mailto:<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></a>
                        <?php elseif ($label === 'Phone') : ?>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $value)); ?>"><?php echo esc_html($value); ?></a>
                        <?php else : ?>
                            <?php echo esc_html($value); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h4 style="margin-bottom: 6px;">Message</h4>
    <p style="white-space: pre-wrap; max-width: 640px;"><?php echo esc_html($post->post_content); ?></p>
    <?php
}

/**
 * Surface the key fields as list-table columns so submissions can be
 * triaged at a glance without opening each one.
 */
add_filter('manage_form_submission_posts_columns', function (array $columns): array {
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ($key === 'title') {
            $new['ndses_email'] = 'Email';
            $new['ndses_phone'] = 'Phone';
            $new['ndses_service_type'] = 'Service Type';
        }
    }

    return $new;
});

add_action('manage_form_submission_posts_custom_column', function (string $column, int $post_id) {
    switch ($column) {
        case 'ndses_email':
            echo esc_html((string) get_post_meta($post_id, 'email', true));
            break;
        case 'ndses_phone':
            echo esc_html((string) get_post_meta($post_id, 'Phone', true));
            break;
        case 'ndses_service_type':
            echo esc_html((string) get_post_meta($post_id, 'Service type', true));
            break;
    }
}, 10, 2);

/**
 * Site settings admin screens.
 *
 * `acf_add_options_page()` -- the usual way to give a set of ACF fields a
 * dedicated wp-admin screen -- is an ACF PRO feature. Only free ACF is
 * installed here, so that call was always a silent no-op (guarded by the
 * function_exists() check below): no menu item was ever registered, and
 * `/wp-admin/admin.php?page=ndses-site-settings` has never resolved to
 * anything. This isn't a regression, just a paid feature that was never
 * actually available.
 *
 * Free ACF does ship `acf_form()` -- a standalone field-group renderer
 * normally used for front-end forms -- which works just as well for a
 * custom admin screen. Field values still live in the same place either
 * way (stored under post_id 'ndses-site-settings' -- see the note in the
 * render callback below), which is what ndses_setting() and the rest of the
 * site read from, so no data migration is needed.
 */
function ndses_settings_pages(): array
{
    return [
        'ndses-site-settings' => [
            'page_title' => 'NDSES Site Settings',
            'menu_title' => 'Site Settings',
            'field_groups' => ['group_ndses_site_settings', 'group_ndses_recaptcha', 'group_ndses_integration_placeholders'],
        ],
        'ndses-site-settings-integrations' => [
            'page_title' => 'Integration Settings',
            'menu_title' => 'Integrations',
            'field_groups' => ['group_ndses_integration_placeholders'],
            'parent' => 'ndses-site-settings',
        ],
    ];
}

add_action('admin_menu', function () {
    if (!function_exists('acf_form_head')) {
        return;
    }

    $render = function (array $page) {
        return function () use ($page) {
            echo '<div class="wrap"><h1>' . esc_html($page['page_title']) . '</h1>';
            acf_form([
                'id' => 'ndses-settings-form',
                // Every field group here has a location rule of
                // options_page == 'ndses-site-settings', and ACF namespaces
                // options-page storage by that slug rather than the generic
                // 'option' post_id -- confirmed empirically via wp_options
                // rows like ndses-site-settings_phone. Passing 'option'
                // here made this form silently load every field empty
                // (while the live site read the real data fine via
                // get_field($key, 'ndses-site-settings') elsewhere) --
                // saving it as shown would have overwritten every real
                // value with blanks.
                'post_id' => 'ndses-site-settings',
                'field_groups' => $page['field_groups'],
                'submit_value' => 'Save Settings',
                'updated_message' => 'Settings saved.',
            ]);
            echo '</div>';
        };
    };

    foreach (ndses_settings_pages() as $slug => $page) {
        if (empty($page['parent'])) {
            $hook = add_menu_page($page['page_title'], $page['menu_title'], 'edit_posts', $slug, $render($page), 'dashicons-admin-generic', 59);
        } else {
            $hook = add_submenu_page($page['parent'], $page['page_title'], $page['menu_title'], 'edit_posts', $slug, $render($page));
        }

        // acf_form_head() enqueues ACF's admin assets and processes the
        // POST-back on save -- both need to happen before this screen's
        // own HTML is output, so it's hooked to the page's load event
        // rather than called inline in the render callback above.
        if ($hook) {
            add_action("load-{$hook}", 'acf_form_head');
        }
    }
});

/**
 * Office Hours used to be a repeater (ACF PRO only) and some installs saved it
 * as an array of lines. It's now a one-line-per-row textarea, and ACF's
 * textarea renderer fatals on an array value -- which took down the whole
 * Site Settings screen. Normalize any legacy array to newline-joined text on
 * load; the next save stores it as a plain string.
 */
add_filter('acf/load_value/key=field_ndses_hours', function ($value) {
    if (!is_array($value)) {
        return $value;
    }

    $lines = [];
    foreach ($value as $row) {
        if (is_array($row)) {
            $row = $row['line'] ?? reset($row);
        }
        $row = trim((string) $row);
        if ($row !== '') {
            $lines[] = $row;
        }
    }

    return implode("\n", $lines);
});

add_filter('acf/settings/save_json', function () {
    return __DIR__ . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = __DIR__ . '/acf-json';
    return $paths;
});

/**
 * ACF options-page REST exposure (acf/v3/options/...) requires ACF PRO's
 * REST integration, which isn't installed here. This site only has free
 * ACF, so expose the site-settings option fields through a small custom
 * route instead.
 */
add_action('rest_api_init', function () {
    register_rest_route('ndses/v1', '/settings', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function () {
            if (!function_exists('get_field')) {
                return new WP_Error('acf_unavailable', 'ACF is not active.', ['status' => 500]);
            }

            $keys = [
                'company_name', 'legal_name', 'phone', 'email', 'address',
                'mailing_address', 'hours', 'service_area', 'facebook',
                'instagram', 'header_logo', 'footer_logo',
                'footer_description', 'copyright_text', 'mission', 'our_story',
            ];

            $settings = [];
            foreach ($keys as $key) {
                $settings[$key] = get_field($key, 'ndses-site-settings');
            }

            return rest_ensure_response($settings);
        },
    ]);
});

/**
 * Quote/contact form intake. Stores each submission as a private
 * form_submission post (so NDS always has a record even if the email
 * bounces or lands in spam) and emails a notification.
 */
add_action('rest_api_init', function () {
    register_rest_route('ndses/v1', '/forms', [
        'methods' => 'POST',
        'permission_callback' => '__return_true',
        'callback' => 'ndses_handle_form_submission',
    ]);
});

/**
 * Reads an ACF field without fataling when ACF is inactive (e.g. mid-update),
 * so forms and payments degrade instead of returning a 500.
 */
function ndses_cms_field(string $key, $post_id = 'ndses-site-settings')
{
    return function_exists('get_field') ? get_field($key, $post_id) : null;
}

/**
 * Client IP for rate limiting. REMOTE_ADDR can't be spoofed, so it wins
 * whenever it's a public address. Forwarded headers are client-controlled
 * and only consulted when REMOTE_ADDR is a private/reserved proxy address
 * (i.e. the request reached PHP through a local reverse proxy) -- trusting
 * them first let anyone bypass the form and payment rate limits by sending
 * a random X-Forwarded-For.
 */
function ndses_get_client_ip(): string
{
    $remote = isset($_SERVER['REMOTE_ADDR']) ? trim(sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']))) : '';
    if ($remote !== '' && filter_var($remote, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        return $remote;
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $forwarded = trim(explode(',', sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR'])))[0]);
        if (filter_var($forwarded, FILTER_VALIDATE_IP)) {
            return $forwarded;
        }
    }

    return $remote !== '' ? $remote : '0.0.0.0';
}

/**
 * Verifies a reCAPTCHA v3 token with Google. Returns true when reCAPTCHA
 * isn't configured yet (Site Settings > reCAPTCHA Secret Key empty) so
 * forms keep working exactly as before until a real key is added -- this
 * is a defense-in-depth layer on top of the honeypot, not a replacement.
 */
function ndses_verify_recaptcha(string $token): bool
{
    $secret = ndses_cms_field('recaptcha_secret_key');
    if (!$secret) {
        return true;
    }

    if ($token === '') {
        return false;
    }

    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
        'timeout' => 10,
        'body' => ['secret' => $secret, 'response' => $token],
    ]);

    if (is_wp_error($response)) {
        error_log('reCAPTCHA verification request failed: ' . $response->get_error_message());

        return false;
    }

    $result = json_decode(wp_remote_retrieve_body($response), true);

    return !empty($result['success']) && ($result['score'] ?? 0) >= 0.5;
}

function ndses_handle_form_submission(WP_REST_Request $request)
{
    $body = $request->get_json_params();
    if (!is_array($body)) {
        return new WP_Error('invalid_body', 'Invalid request body.', ['status' => 400]);
    }

    // Honeypot: a field real visitors never see or fill in. Bots that
    // auto-fill every input trip it. Respond as if it succeeded so bots
    // don't learn the field is being checked.
    if (!empty($body['website'])) {
        return rest_ensure_response(['ok' => true]);
    }

    if (!ndses_verify_recaptcha(sanitize_text_field($body['captchaToken'] ?? ''))) {
        return new WP_Error('captcha_failed', 'We could not verify this submission. Please try again.', ['status' => 400]);
    }

    $rate_key = 'ndses_form_rl_' . md5(ndses_get_client_ip());
    $count = (int) get_transient($rate_key);
    if ($count >= 5) {
        return new WP_Error('rate_limited', 'Too many submissions. Please try again in a few minutes.', ['status' => 429]);
    }
    set_transient($rate_key, $count + 1, 10 * MINUTE_IN_SECONDS);

    $name = sanitize_text_field($body['name'] ?? '');
    $email = sanitize_email($body['email'] ?? '');
    $message = sanitize_textarea_field($body['message'] ?? '');
    $form_type = sanitize_text_field($body['formType'] ?? 'general');

    if (mb_strlen($name) < 2 || !is_email($email) || mb_strlen($message) < 10) {
        return new WP_Error('validation_failed', 'Please provide a valid name, email, and message.', ['status' => 400]);
    }

    $extra_fields = [
        'Phone' => sanitize_text_field($body['phone'] ?? ''),
        'Service type' => sanitize_text_field($body['serviceType'] ?? ''),
        'Service address' => sanitize_text_field($body['serviceAddress'] ?? ''),
        'Selected dumpster size' => sanitize_text_field($body['selectedDumpsterSize'] ?? ''),
        'Event date' => sanitize_text_field($body['eventDate'] ?? ''),
    ];

    $post_id = wp_insert_post([
        'post_type' => 'form_submission',
        'post_title' => sprintf('%s inquiry from %s (%s)', ucfirst($form_type), $name, current_time('Y-m-d H:i')),
        'post_status' => 'publish',
        'post_content' => $message,
        'meta_input' => array_merge(
            ['name' => $name, 'email' => $email, 'form_type' => $form_type],
            array_filter($extra_fields)
        ),
    ], true);

    if (is_wp_error($post_id)) {
        return new WP_Error('storage_failed', 'Could not store submission.', ['status' => 500]);
    }

    $notify_to = ndses_cms_field('email') ?: get_option('admin_email');
    $lines = ["Name: {$name}", "Email: {$email}"];
    foreach (array_filter($extra_fields) as $label => $value) {
        $lines[] = "{$label}: {$value}";
    }
    $lines[] = '';
    $lines[] = 'Message:';
    $lines[] = $message;

    wp_mail(
        $notify_to,
        sprintf('[NDS Website] New %s inquiry from %s', $form_type, $name),
        implode("\n", $lines),
        ['Reply-To: ' . $name . ' <' . $email . '>']
    );

    return rest_ensure_response(['ok' => true, 'id' => $post_id]);
}

/**
 * PayEngine payments. Card/bank details are tokenized client-side via
 * PayEngine's SecureFields JS SDK (see inc/payengine.php for the embed +
 * theme.js for the submit handler) — this endpoint only ever sees the
 * resulting token, never raw card or bank numbers.
 *
 * Auth against PayEngine is `Authorization: Basic <secret key>` (PayEngine's
 * own non-standard use of the "Basic" scheme — just the literal word
 * "Basic" plus the raw secret key), confirmed against the authenticated
 * merchant API reference at docs.payengine.co/merchant-api-reference.
 *
 * The secret key is stored as an ACF field on the Make a Payment page
 * itself (id 158) rather than the site's options page, because this
 * install's custom options-page admin menu entry isn't registering
 * (pre-existing bug, unrelated to this feature) — Pages > Make a Payment
 * has a "PayEngine Secret Key" field in the sidebar instead.
 */
add_action('rest_api_init', function () {
    register_rest_route('ndses/v1', '/payments/charge', [
        'methods' => 'POST',
        'permission_callback' => '__return_true',
        'callback' => 'ndses_handle_payment_charge',
    ]);
});

function ndses_payengine_credentials(): ?array
{
    $secret = ndses_cms_field('payengine_secret_key', 158);
    // Falls back to the wp_option (set once via WP-CLI during initial setup)
    // if the ACF Site Settings fields are empty, so an existing deploy keeps
    // working until someone re-enters these through the admin fields.
    $api_url = ndses_cms_field('payengine_api_url') ?: get_option('ndses_payengine_api_url');
    $merchant_id = ndses_cms_field('payengine_merchant_id') ?: get_option('ndses_payengine_merchant_id');

    if (!$secret || !$api_url || !$merchant_id) {
        return null;
    }

    return ['secret' => $secret, 'api_url' => rtrim((string) $api_url, '/'), 'merchant_id' => $merchant_id];
}

/** Upper bound for a single online payment, as a guard against typos (e.g. 15000 for 150.00). */
const NDSES_MAX_PAYMENT_AMOUNT = 50000;

/** PayEngine's order_number field only accepts [a-zA-Z0-9], max 20 chars. */
function ndses_payengine_order_number(string $account_number): string
{
    $clean = preg_replace('/[^a-zA-Z0-9]/', '', $account_number);
    $clean = substr((string) $clean, 0, 20);

    return $clean !== '' ? $clean : 'NDSES';
}

function ndses_handle_payment_charge(WP_REST_Request $request)
{
    $body = $request->get_json_params();
    if (!is_array($body)) {
        return new WP_Error('invalid_body', 'Invalid request body.', ['status' => 400]);
    }

    $token = sanitize_text_field($body['token'] ?? '');
    $payment_method = sanitize_text_field($body['paymentMethod'] ?? 'card');
    $amount = (string) ($body['amount'] ?? '');
    $account_number = sanitize_text_field($body['accountNumber'] ?? '');
    $email = sanitize_email($body['email'] ?? '');

    $amount_valid = preg_match('/^\d+(\.\d{1,2})?$/', $amount) && (float) $amount > 0 && (float) $amount <= NDSES_MAX_PAYMENT_AMOUNT;
    if ($token === '' || !$amount_valid || mb_strlen($account_number) < 3) {
        return new WP_Error('validation_failed', 'Payment information is incomplete.', ['status' => 400]);
    }

    $creds = ndses_payengine_credentials();
    if (!$creds) {
        return rest_ensure_response([
            'mode' => 'placeholder',
            'message' => ($payment_method === 'ach' ? 'ACH' : 'Card') . ' payments are not available yet. Please contact NDSES for current payment options.',
        ]);
    }

    $rate_key = 'ndses_pay_rl_' . md5(ndses_get_client_ip());
    $count = (int) get_transient($rate_key);
    if ($count >= 10) {
        return new WP_Error('rate_limited', 'Too many attempts. Please try again shortly.', ['status' => 429]);
    }
    set_transient($rate_key, $count + 1, 10 * MINUTE_IN_SECONDS);

    $order_number = ndses_payengine_order_number($account_number);
    $description = 'NDSES account ' . $account_number;
    $metadata = ['accountNumber' => $account_number, 'email' => $email];

    if ($payment_method === 'ach') {
        $endpoint = $creds['api_url'] . '/api/payment/ach';
        $data = [
            'transactionAmount' => number_format((float) $amount, 2, '.', ''),
            'accountToken' => $token,
            'order_number' => $order_number,
            'internalTransactionID' => $account_number,
            'description' => $description,
            'metadata' => $metadata,
        ];
    } else {
        $endpoint = $creds['api_url'] . '/api/payment/sale';
        $data = [
            'transactionAmount' => number_format((float) $amount, 2, '.', ''),
            'cardToken' => $token,
            'currencyCode' => 'USD',
            'order_number' => $order_number,
            'internalTransactionID' => $account_number,
            'description' => $description,
            'metadata' => $metadata,
        ];
    }

    $response = wp_remote_post($endpoint, [
        'timeout' => 20,
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $creds['secret'],
        ],
        'body' => wp_json_encode(['merchant_id' => $creds['merchant_id'], 'data' => $data]),
    ]);

    if (is_wp_error($response)) {
        error_log('PayEngine charge request failed: ' . $response->get_error_message());

        // A timeout doesn't mean the charge failed -- PayEngine may still
        // have completed it. Tell the customer not to retry (a retry could
        // double-charge) and to confirm with the office instead.
        return rest_ensure_response(['mode' => 'error', 'message' => 'We could not confirm whether your payment went through. Please do not submit it again. Call NDSES so we can check your account.']);
    }

    $status = wp_remote_retrieve_response_code($response);
    $json = json_decode(wp_remote_retrieve_body($response), true);

    if ($status < 200 || $status >= 300 || !is_array($json) || !empty($json['error'])) {
        error_log('PayEngine charge failed: HTTP ' . $status . ' ' . wp_remote_retrieve_body($response));

        // PayEngine answers declines (insufficient funds, invalid bank
        // account) with an error status too, so word this for both cases.
        return rest_ensure_response(['mode' => 'error', 'message' => 'This payment could not be completed. Please check your payment details and try again, use a different payment method, or call NDSES.']);
    }

    $sale_response = $json['data']['SaleResponse'] ?? $json['data']['AchResponse'] ?? null;
    $transaction_id = $json['data']['TransactionID'] ?? $json['data']['ID'] ?? null;

    if (($sale_response['status'] ?? '') === 'PASS' && $transaction_id) {
        return rest_ensure_response([
            'mode' => 'success',
            'message' => 'Thank you! Your payment has been received.',
            'transactionId' => $transaction_id,
        ]);
    }

    return rest_ensure_response([
        'mode' => 'declined',
        'message' => 'Payment failed. Please try again or contact NDSES.',
    ]);
}
