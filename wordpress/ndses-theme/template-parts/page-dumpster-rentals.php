<?php ndses_render_hero('dumpster-rentals'); ?>
<section class="section">
    <div class="container">
        <div class="section-heading row-heading">
            <div>
                <h2><?php echo esc_html(ndses_sec('sizes', 'heading', 'dumpster-rentals')); ?></h2>
            </div>
            <?php ndses_button(ndses_sec('sizes', 'button', 'dumpster-rentals'), home_url('/dumpster-calculator'), 'button button-secondary'); ?>
        </div>
        <?php ndses_render_dumpster_cards(); ?>
    </div>
</section>
<section class="section soft-section" id="materials">
    <div class="container split">
        <article class="feature-card">
            <h2><?php echo esc_html(ndses_sec('materials', 'accepted_heading', 'dumpster-rentals')); ?></h2>
            <?php ndses_list(ndses_sec_lines('materials', 'accepted_list', 'dumpster-rentals')); ?>
        </article>
        <article class="feature-card">
            <h2><?php echo esc_html(ndses_sec('materials', 'not_heading', 'dumpster-rentals')); ?></h2>
            <?php ndses_list(ndses_sec_lines('materials', 'not_list', 'dumpster-rentals'), 'danger-list'); ?>
        </article>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="section-heading">
            <h2><?php echo esc_html(ndses_sec('process', 'heading', 'dumpster-rentals')); ?></h2>
        </div>
        <div class="process-grid">
            <?php foreach ([1, 2, 3, 4] as $index => $n) :
                $step = [ndses_sec('process', "step{$n}_title", 'dumpster-rentals'), ndses_sec('process', "step{$n}_body", 'dumpster-rentals')];
                ?>
                <article class="process-step">
                    <span><?php echo esc_html((string) ($index + 1)); ?></span>
                    <h3><?php echo esc_html($step[0]); ?></h3>
                    <p><?php echo esc_html($step[1]); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section soft-section" id="quote">
    <div class="container split">
        <div>
            <h2><?php echo esc_html(ndses_sec('quote', 'heading', 'dumpster-rentals')); ?></h2>
            <p><?php echo esc_html(ndses_sec('quote', 'body', 'dumpster-rentals')); ?></p>
        </div>
        <?php ndses_render_inquiry_form('dumpster'); ?>
    </div>
</section>
