<?php ndses_render_hero('residential'); ?>
<section class="section">
    <div class="container">
        <div class="section-heading">
            <h2><?php echo esc_html(ndses_sec('communities', 'heading', 'residential')); ?></h2>
        </div>
        <div class="card-grid five">
            <?php foreach (ndses_get_communities() as $slug => $community) : ?>
                <article class="feature-card">
                    <p class="kicker"><?php echo esc_html($community['type']); ?></p>
                    <h3><?php echo esc_html($community['name']); ?></h3>
                    <p><strong><?php echo esc_html($community['service_day']); ?></strong> collection</p>
                    <a class="text-link" href="<?php echo esc_url(home_url('/residential/' . $slug)); ?>"><?php echo esc_html(ndses_sec('communities', 'link_label', 'residential')); ?></a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section soft-section">
    <div class="container split">
        <div>
            <h2><?php echo esc_html(ndses_sec('pickup', 'heading', 'residential')); ?></h2>
            <?php ndses_list(ndses_sec_lines('pickup', 'list', 'residential')); ?>
        </div>
        <div class="quote-panel">
            <h3><?php echo esc_html(ndses_sec('recycling', 'heading', 'residential')); ?></h3>
            <?php ndses_list(ndses_sec_lines('recycling', 'list', 'residential'), 'compact-list'); ?>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <?php ndses_render_faqs('Residential Trash & Recycling'); ?>
    </div>
</section>
