</main>
<?php
// Matches the Next.js footer (components/layout/Footer.tsx).
$ndses_social = array_filter([
    'Facebook' => ndses_setting('facebook'),
    'Instagram' => ndses_setting('instagram'),
]);
$ndses_icon = static fn (string $path): string => '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
?>
<footer class="site-footer">
    <div class="footer-cta">
        <div class="container footer-cta-inner">
            <div>
                <h2><?php echo esc_html(ndses_sw('footer', 'cta_heading')); ?></h2>
                <p><?php echo esc_html(ndses_sw('footer', 'cta_text')); ?></p>
            </div>
            <div class="footer-cta-actions">
                <?php ndses_button(ndses_setting('phone'), ndses_phone_href(), 'button button-gold'); ?>
                <?php ndses_button(ndses_sw('footer', 'cta_button'), home_url('/contact/'), 'button button-on-dark'); ?>
            </div>
        </div>
    </div>

    <div class="container footer-grid">
        <div class="footer-brand">
            <img class="footer-logo" src="<?php echo esc_url(ndses_logo_url('footer')); ?>" alt="<?php echo esc_attr(ndses_setting('company_name') ?: 'NDS Environmental Solutions'); ?>" width="600" height="373" loading="lazy" decoding="async">
            <p><?php echo esc_html(ndses_setting('footer_description', 'Local waste management for homes, businesses, communities, projects, and events in Southern Wisconsin.')); ?></p>
        </div>

        <div>
            <h3><?php echo esc_html(ndses_sw('footer', 'contact_heading')); ?></h3>
            <ul class="footer-contact">
                <li><?php echo $ndses_icon('<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>'); ?> <span><?php echo esc_html(ndses_setting('address')); ?><?php if (ndses_setting('mailing_address')) : ?><br>Mailing: <?php echo esc_html(ndses_setting('mailing_address')); ?><?php endif; ?></span></li>
                <li><?php echo $ndses_icon('<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/>'); ?> <a href="<?php echo esc_url(ndses_phone_href()); ?>"><?php echo esc_html(ndses_setting('phone')); ?></a></li>
                <li><?php echo $ndses_icon('<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>'); ?> <a href="mailto:<?php echo esc_attr(ndses_setting('email')); ?>"><?php echo esc_html(ndses_setting('email')); ?></a></li>
                <li><?php echo $ndses_icon('<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>'); ?> <span><?php foreach (ndses_hours_lines() as $line) : ?><span class="footer-hours-line"><?php echo esc_html($line); ?></span><?php endforeach; ?></span></li>
            </ul>
        </div>

        <div>
            <h3><?php echo esc_html(ndses_sw('footer', 'services_heading')); ?></h3>
            <ul class="footer-links">
                <?php foreach (ndses_sw_links('footer', 'services_links') as $link) : ?>
                    <li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div>
            <h3><?php echo esc_html(ndses_sw('footer', 'customers_heading')); ?></h3>
            <ul class="footer-links">
                <?php foreach (ndses_sw_links('footer', 'customers_links') as $link) : ?>
                    <li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a></li>
                <?php endforeach; ?>
            </ul>
            <?php if ($ndses_social) : ?>
                <ul class="footer-social">
                    <?php foreach ($ndses_social as $label => $url) : ?>
                        <li><a href="<?php echo esc_url($url); ?>" rel="noreferrer" target="_blank"><?php echo esc_html($label); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p><?php echo esc_html(ndses_setting('copyright_text', 'Copyright ' . date('Y') . ' NDS Environmental Solutions, LLC. All rights reserved.')); ?></p>
            <ul>
                <?php foreach (ndses_sw_links('footer', 'legal_links') as $link) : ?>
                    <li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
