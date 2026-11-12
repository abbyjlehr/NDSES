<?php
/**
 * Site header. Markup and class names match the Next.js header
 * (components/layout/Header.tsx); dropdown and mobile-sheet behavior lives
 * in assets/js/theme.js (initNavigation).
 */
$ndses_nav = ndses_nav_items();
$ndses_announcement = ndses_get_announcement();
$ndses_hours = implode(' · ', ndses_hours_lines());
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>
<?php if ($ndses_announcement) : ?>
    <div class="announcement" role="status" data-announcement="<?php echo esc_attr(substr(md5($ndses_announcement), 0, 12)); ?>">
        <div class="container announcement-inner">
            <svg class="announcement-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
            <p class="announcement-text"><?php echo esc_html($ndses_announcement); ?></p>
            <a class="announcement-link" href="<?php echo esc_url(home_url('/whats-new')); ?>">View updates</a>
            <button class="announcement-close" type="button" aria-label="Dismiss this alert" data-announcement-close>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
<?php endif; ?>
<header class="site-header">
    <div class="utility-bar">
        <div class="container utility-inner">
            <span>Locally owned waste collection, recycling, and rentals</span>
            <?php if ($ndses_hours) : ?><span class="utility-hours"><?php echo esc_html($ndses_hours); ?></span><?php endif; ?>
        </div>
    </div>
    <div class="header-main">
        <div class="container header-inner">
            <?php if (has_custom_logo()) : ?>
                <div class="brand"><?php the_custom_logo(); ?></div>
            <?php else : ?>
                <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="NDS Environmental Solutions home">
                    <img class="brand-logo" src="<?php echo ndses_asset('nds-logo-primary.png'); ?>" alt="" width="600" height="373">
                </a>
            <?php endif; ?>

            <nav class="desktop-nav" aria-label="Primary" data-desktop-nav>
                <ul class="desktop-nav-list">
                    <?php foreach ($ndses_nav as $index => $item) :
                        $active = ndses_nav_is_active($item);
                        if (empty($item['children'])) : ?>
                            <li><a class="nav-trigger<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a></li>
                        <?php else : ?>
                            <li class="nav-item" data-nav-item>
                                <button class="nav-trigger<?php echo $active ? ' is-active' : ''; ?>" type="button" aria-expanded="false" aria-controls="nav-menu-<?php echo (int) $index; ?>" data-nav-trigger>
                                    <?php echo esc_html($item['label']); ?>
                                    <svg class="nav-caret" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div class="nav-panel" id="nav-menu-<?php echo (int) $index; ?>" hidden data-nav-panel>
                                    <?php if (!empty($item['description'])) : ?><p class="nav-panel-intro"><?php echo esc_html($item['description']); ?></p><?php endif; ?>
                                    <ul class="nav-panel-links">
                                        <?php foreach ($item['children'] as $child) : ?>
                                            <li>
                                                <a href="<?php echo esc_url($child['url']); ?>">
                                                    <strong><?php echo esc_html($child['label']); ?></strong>
                                                    <?php if (!empty($child['description'])) : ?><span><?php echo esc_html($child['description']); ?></span><?php endif; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </li>
                        <?php endif;
                    endforeach; ?>
                </ul>
            </nav>

            <div class="header-actions">
                <a class="header-phone" href="<?php echo esc_url(ndses_phone_href()); ?>">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/></svg>
                    <span><?php echo esc_html(ndses_setting('phone')); ?></span>
                </a>
                <?php ndses_button('Get a Quote', home_url('/contact'), 'button button-outline button-small'); ?>
                <?php ndses_button('Pay Bill', home_url('/make-a-payment'), 'button button-primary button-small'); ?>
            </div>

            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" data-menu-toggle>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path class="menu-icon-open" d="M4 6h16M4 12h16M4 18h16"/><path class="menu-icon-close" d="M18 6 6 18M6 6l12 12"/></svg>
                <span data-menu-label>Menu</span>
            </button>
        </div>
    </div>

    <div class="mobile-nav" id="mobile-nav" hidden data-mobile-nav>
        <nav aria-label="Mobile">
            <ul class="mobile-nav-list">
                <?php foreach ($ndses_nav as $index => $item) : ?>
                    <li>
                        <?php if (empty($item['children'])) : ?>
                            <a class="mobile-nav-link" href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
                        <?php else : ?>
                            <button class="mobile-nav-group" type="button" aria-expanded="<?php echo ndses_nav_is_active($item) ? 'true' : 'false'; ?>" aria-controls="mobile-group-<?php echo (int) $index; ?>" data-mobile-group>
                                <?php echo esc_html($item['label']); ?>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <ul class="mobile-nav-sub" id="mobile-group-<?php echo (int) $index; ?>"<?php echo ndses_nav_is_active($item) ? '' : ' hidden'; ?>>
                                <?php foreach ($item['children'] as $child) : ?>
                                    <li><a href="<?php echo esc_url($child['url']); ?>"><?php echo esc_html($child['label']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="mobile-nav-actions">
            <?php ndses_button('Call ' . ndses_setting('phone'), ndses_phone_href(), 'button button-outline'); ?>
            <?php ndses_button('Get a Quote', home_url('/contact'), 'button button-outline'); ?>
            <?php ndses_button('Pay Bill', home_url('/make-a-payment'), 'button button-primary'); ?>
        </div>
    </div>
</header>
<main id="main">
