<?php ndses_render_hero('commercial'); ?>
<section class="section">
    <div class="container split">
        <div>
            <h2><?php echo esc_html(ndses_sec('areas', 'heading', 'commercial')); ?></h2>
            <?php ndses_list(ndses_sec_lines('areas', 'list', 'commercial')); ?>
            <p class="small-note"><?php echo esc_html(ndses_sec('areas', 'note', 'commercial')); ?></p>
            <?php ndses_sec_img('areas', 'image', 'zone-map-image', 'Map of the counties served by NDS Environmental Solutions', 'commercial'); ?>
        </div>
        <div class="quote-panel">
            <h3><?php echo esc_html(ndses_sec('schedules', 'heading', 'commercial')); ?></h3>
            <?php ndses_list(ndses_sec_lines('schedules', 'list', 'commercial'), 'compact-list'); ?>
        </div>
    </div>
</section>
<section class="section soft-section">
    <div class="container">
        <?php ndses_render_service_area_map(['Commercial', 'Roll-off']); ?>
    </div>
</section>
<section class="section soft-section" id="containers">
    <div class="container">
        <div class="section-heading">
            <h2><?php echo esc_html(ndses_sec('containers', 'heading', 'commercial')); ?></h2>
        </div>
        <div class="card-grid five">
            <?php foreach (ndses_get_commercial_containers() as $container) : ?>
                <article class="feature-card dumpster-card">
                    <?php if (!empty($container['image'])) : ?>
                        <img class="container-image" src="<?php echo ndses_image_url($container['image']); ?>" alt="<?php echo esc_attr($container['name'] . ' dumpster dimensions'); ?>" loading="lazy" decoding="async">
                    <?php elseif (preg_match('/\b96\b/', $container['name'])) : ?>
                        <img class="container-image" src="<?php echo ndses_asset('container-96-gallon.svg'); ?>" alt="96 gallon wheeled cart" loading="lazy" decoding="async">
                    <?php else : ?>
                        <?php // Same-size frame when there's no graphic yet, so every card's heading lines up. ?>
                        <div class="container-image container-image--empty" aria-hidden="true">
                            <svg width="40" height="40" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><?php echo ndses_service_icon('rolloff'); ?></svg>
                        </div>
                    <?php endif; ?>
                    <h3><?php echo esc_html($container['name']); ?></h3>
                    <?php if (!empty($container['capacity'])) : ?>
                        <p class="kicker"><?php echo esc_html($container['capacity']); ?></p>
                    <?php else : ?>
                        <?php // Keeps the description level with neighboring cards. ?>
                        <p class="kicker kicker--empty" aria-hidden="true">&nbsp;</p>
                    <?php endif; ?>
                    <p class="card-body"><?php echo esc_html($container['body']); ?></p>
                    <?php ndses_list($container['ideal'], 'compact-list'); ?>
                    <?php if (!empty($container['dimensions'])) : ?><p class="small-note"><strong>Dimensions:</strong> <?php echo esc_html($container['dimensions']); ?></p><?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section" id="recycling">
    <div class="container split">
        <article class="feature-card">
            <h2><?php echo esc_html(ndses_sec('recycling', 'heading', 'commercial')); ?></h2>
            <p><?php echo esc_html(ndses_sec('recycling', 'body', 'commercial')); ?></p>
            <?php ndses_list(ndses_sec_lines('recycling', 'list', 'commercial')); ?>
        </article>
        <article class="feature-card">
            <h2><?php echo esc_html(ndses_sec('industries', 'heading', 'commercial')); ?></h2>
            <?php ndses_list(ndses_sec_lines('industries', 'list', 'commercial')); ?>
        </article>
    </div>
</section>
<section class="section soft-section" id="quote">
    <div class="container split">
        <div>
            <h2><?php echo esc_html(ndses_sec('quote', 'heading', 'commercial')); ?></h2>
            <p><?php echo esc_html(ndses_sec('quote', 'body', 'commercial')); ?></p>
        </div>
        <?php ndses_render_inquiry_form('commercial'); ?>
    </div>
</section>

