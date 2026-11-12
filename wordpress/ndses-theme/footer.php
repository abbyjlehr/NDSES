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
                <h2>Need trash service, recycling, or a dumpster?</h2>
                <p>Call the office or send a request, and NDS will confirm availability and pricing.</p>
            </div>
            <div class="footer-cta-actions">
                <?php ndses_button(ndses_setting('phone'), ndses_phone_href(), 'button button-gold'); ?>
                <?php ndses_button('Request a Quote', home_url('/contact'), 'button button-on-dark'); ?>
            </div>
        </div>
    </div>

    <div class="container footer-grid">
        <div class="footer-brand">
            <?php if (has_custom_logo()) : ?>
                <div class="footer-logo"><?php the_custom_logo(); ?></div>
            <?php else : ?>
                <img class="footer-logo" src="<?php echo ndses_asset('nds-logo-primary.png'); ?>" alt="NDS Environmental Solutions" width="600" height="373" loading="lazy" decoding="async">
            <?php endif; ?>
            <p><?php echo esc_html(ndses_setting('footer_description', 'Local waste management for homes, businesses, communities, projects, and events in Southern Wisconsin.')); ?></p>
        </div>

        <div>
            <h3>Contact</h3>
            <ul class="footer-contact">
                <li><?php echo $ndses_icon('<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>'); ?> <span><?php echo esc_html(ndses_setting('address')); ?><?php if (ndses_setting('mailing_address')) : ?><br>Mailing: <?php echo esc_html(ndses_setting('mailing_address')); ?><?php endif; ?></span></li>
                <li><?php echo $ndses_icon('<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/>'); ?> <a href="<?php echo esc_url(ndses_phone_href()); ?>"><?php echo esc_html(ndses_setting('phone')); ?></a></li>
                <li><?php echo $ndses_icon('<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>'); ?> <a href="mailto:<?php echo esc_attr(ndses_setting('email')); ?>"><?php echo esc_html(ndses_setting('email')); ?></a></li>
                <li><?php echo $ndses_icon('<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>'); ?> <span><?php foreach (ndses_hours_lines() as $line) : ?><span class="footer-hours-line"><?php echo esc_html($line); ?></span><?php endforeach; ?></span></li>
            </ul>
        </div>

        <div>
            <h3>Services</h3>
            <ul class="footer-links">
                <li><a href="<?php echo esc_url(home_url('/residential')); ?>">Residential</a></li>
                <li><a href="<?php echo esc_url(home_url('/commercial')); ?>">Commercial</a></li>
                <li><a href="<?php echo esc_url(home_url('/dumpster-rentals')); ?>">Dumpster Rentals</a></li>
                <li><a href="<?php echo esc_url(home_url('/dumpster-calculator')); ?>">Dumpster Calculator</a></li>
                <li><a href="<?php echo esc_url(home_url('/special-events')); ?>">Special Events</a></li>
            </ul>
        </div>

        <div>
            <h3>Customers</h3>
            <ul class="footer-links">
                <li><a href="<?php echo esc_url(home_url('/make-a-payment')); ?>">Pay Your Bill</a></li>
                <li><a href="<?php echo esc_url(home_url('/whats-new')); ?>">Service Alerts</a></li>
                <li><a href="<?php echo esc_url(home_url('/faqs')); ?>">FAQs</a></li>
                <li><a href="<?php echo esc_url(home_url('/about')); ?>">About NDS</a></li>
                <li><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
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
                <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a></li>
                <li><a href="<?php echo esc_url(home_url('/terms')); ?>">Terms &amp; Content Sources</a></li>
                <li><a href="<?php echo esc_url(home_url('/photo-credits')); ?>">Photo Credits</a></li>
            </ul>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
