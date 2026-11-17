<?php
$slug = $args['community_slug'] ?? '';
$community = ndses_get_communities()[$slug] ?? null;

if (!$community) {
    ndses_render_hero('residential');
    echo '<section class="section"><div class="container"><p>We couldn&rsquo;t find that community. See the full list on the Residential page or call NDS.</p></div></section>';
    return;
}
?>
<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy">
            <h1><?php echo esc_html($community['name']); ?></h1>
            <p class="hero-text"><?php echo esc_html($community['description']); ?></p>
            <div class="hero-actions">
                <?php ndses_button('Request Service', home_url('/contact?service=residential')); ?>
                <?php ndses_button('Call NDS', ndses_phone_href(), 'button button-secondary'); ?>
            </div>
        </div>
        <div class="quote-panel schedule-card">
            <h2>Service Information</h2>
            <p><strong>Service Day:</strong> <?php echo esc_html($community['service_day']); ?></p>
            <p><strong>Trash:</strong> <?php echo esc_html($community['trash_schedule']); ?></p>
            <p><strong>Recycling:</strong> <?php echo esc_html($community['recycling_schedule']); ?></p>
        </div>
    </div>
</section>
<?php if (!empty($community['documents'])) : ?>
<section class="section soft-section">
    <div class="container">
        <div class="section-heading">
            <h2>Printable schedules & guides</h2>
        </div>
        <div class="card-grid three">
            <?php foreach ($community['documents'] as $document) : ?>
                <article class="feature-card">
                    <h3><?php echo esc_html($document['title']); ?></h3>
                    <a class="text-link" href="<?php echo esc_url($document['url']); ?>" download>Download</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<section class="section">
    <div class="container split">
        <article class="feature-card">
            <h2>Service Guidelines</h2>
            <?php ndses_list($community['guidelines']); ?>
        </article>
        <article class="feature-card">
            <h2>Bulk Item Guidance</h2>
            <p><?php echo esc_html($community['bulk_policy']); ?></p>
            <div class="two-column-list">
                <div>
                    <h3>Accepted</h3>
                    <?php ndses_list($community['bulk_accepted'], 'compact-list'); ?>
                </div>
                <div>
                    <h3>Not accepted</h3>
                    <?php ndses_list($community['bulk_not_accepted'], 'compact-list danger-list'); ?>
                </div>
            </div>
        </article>
    </div>
</section>
<section class="section soft-section">
    <div class="container">
        <div class="section-heading">
            <h2>What belongs in recycling</h2>
        </div>
        <div class="card-grid three">
            <article class="feature-card">
                <h3>Accepted</h3>
                <?php ndses_list($community['accepted_recycling'], 'compact-list'); ?>
            </article>
            <article class="feature-card">
                <h3>Not accepted</h3>
                <?php ndses_list($community['recycling_not_accepted'], 'compact-list danger-list'); ?>
            </article>
            <article class="feature-card">
                <h3>Never accepted</h3>
                <?php ndses_list($community['recycling_never_accepted'], 'compact-list danger-list'); ?>
            </article>
        </div>
    </div>
</section>

