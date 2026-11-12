<?php
get_header();
ndses_render_hero('home');
ndses_render_service_cards();
?>
<section class="section section-tight">
    <div class="container">
        <div class="autopay-card">
            <div class="autopay-icon" aria-hidden="true">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10h18M5 6h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z"/><path d="m8 15 2 2 4-4"/></svg>
            </div>
            <div class="autopay-copy">
                <h2><?php echo esc_html(ndses_sec('autopay', 'heading', 'home')); ?></h2>
                <p><?php echo esc_html(ndses_sec('autopay', 'body', 'home')); ?></p>
            </div>
            <div class="autopay-actions">
                <a class="button" href="<?php echo esc_url(ndses_sec_file('autopay', 'file', 'home')); ?>" download>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12M7 10l5 5 5-5M5 21h14"/></svg>
                    <?php echo esc_html(ndses_sec('autopay', 'button', 'home')); ?>
                </a>
                <a class="text-link" href="<?php echo esc_url(home_url('/make-a-payment')); ?>"><?php echo esc_html(ndses_sec('autopay', 'link_label', 'home')); ?></a>
            </div>
        </div>
    </div>
</section>
<section class="section soft-section">
    <div class="container split">
        <div>
            <h2><?php echo esc_html(ndses_sec('why', 'heading', 'home')); ?></h2>
            <p><?php echo esc_html(ndses_sec('why', 'body', 'home')); ?></p>
        </div>
        <div class="quote-panel">
            <h3><?php echo esc_html(ndses_sec('why', 'mission_heading', 'home')); ?></h3>
            <p><?php echo esc_html(ndses_get_mission()); ?></p>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="section-heading row-heading">
            <div>
                <h2><?php echo esc_html(ndses_sec('sizes', 'heading', 'home')); ?></h2>
            </div>
            <?php ndses_button(ndses_sec('sizes', 'button', 'home'), home_url('/dumpster-calculator'), 'button button-secondary'); ?>
        </div>
        <?php ndses_render_dumpster_cards(); ?>
    </div>
</section>
<section class="section soft-section">
    <div class="container">
        <?php ndses_render_service_area_map(); ?>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="section-heading">
            <h2><?php echo esc_html(ndses_sec('faq', 'heading', 'home')); ?></h2>
        </div>
        <?php ndses_render_faqs('Dumpster Rentals'); ?>
    </div>
</section>
<?php get_footer(); ?>
