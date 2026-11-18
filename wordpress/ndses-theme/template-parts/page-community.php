<?php
$slug = $args['community_slug'] ?? '';
$community = ndses_get_communities()[$slug] ?? null;

if (!$community) {
    ndses_render_hero('residential');
    echo '<section class="section"><div class="container"><p>We couldn&rsquo;t find that community. See the full list on the Residential page or call NDS.</p></div></section>';
    return;
}
// Headings shared by every town page: Pages → Residential → Town Pages.
$t = static fn (string $field): string => ndses_sec('town', $field, 'residential');
$schedule = array_filter([
    $t('day_label') => $community['service_day'] ?? '',
    $t('trash_label') => $community['trash_schedule'] ?? '',
    $t('recycling_label') => $community['recycling_schedule'] ?? '',
], static fn ($value) => trim((string) $value) !== '');
?>
<section class="hero-section">
    <div class="container hero-grid<?php echo $schedule ? '' : ' hero-grid--no-media'; ?>">
        <div class="hero-copy">
            <h1><?php echo esc_html($community['name']); ?></h1>
            <?php if (!empty($community['description'])) : ?><p class="hero-text"><?php echo esc_html($community['description']); ?></p><?php endif; ?>
            <div class="hero-actions">
                <?php ndses_button($t('request_button'), home_url('/contact/?service=residential')); ?>
                <?php ndses_button($t('call_button'), ndses_phone_href(), 'button button-secondary'); ?>
            </div>
        </div>
        <?php if ($schedule) : ?>
        <div class="quote-panel schedule-card">
            <h2><?php echo esc_html($t('info_heading')); ?></h2>
            <?php foreach ($schedule as $label => $value) : ?>
                <p><strong><?php echo esc_html($label); ?></strong> <?php echo esc_html($value); ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php if (!empty($community['documents'])) : ?>
<section class="section soft-section">
    <div class="container">
        <div class="section-heading">
            <h2><?php echo esc_html($t('docs_heading')); ?></h2>
        </div>
        <div class="card-grid three">
            <?php foreach ($community['documents'] as $document) : ?>
                <article class="feature-card">
                    <h3><?php echo esc_html($document['title']); ?></h3>
                    <a class="text-link" href="<?php echo esc_url($document['url']); ?>" download><?php echo esc_html($t('download_label')); ?></a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php
$has_guidelines = !empty($community['guidelines']);
$has_bulk = !empty($community['bulk_policy']) || !empty($community['bulk_accepted']) || !empty($community['bulk_not_accepted']);
if ($has_guidelines || $has_bulk) : ?>
<section class="section">
    <div class="container<?php echo ($has_guidelines && $has_bulk) ? ' split' : ''; ?>">
        <?php if ($has_guidelines) : ?>
        <article class="feature-card">
            <h2><?php echo esc_html($t('guidelines_heading')); ?></h2>
            <?php ndses_list($community['guidelines']); ?>
        </article>
        <?php endif; ?>
        <?php if ($has_bulk) : ?>
        <article class="feature-card">
            <h2><?php echo esc_html($t('bulk_heading')); ?></h2>
            <?php if (!empty($community['bulk_policy'])) : ?><p><?php echo esc_html($community['bulk_policy']); ?></p><?php endif; ?>
            <div class="two-column-list">
                <?php if (!empty($community['bulk_accepted'])) : ?>
                <div>
                    <h3><?php echo esc_html($t('accepted_label')); ?></h3>
                    <?php ndses_list($community['bulk_accepted'], 'compact-list'); ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($community['bulk_not_accepted'])) : ?>
                <div>
                    <h3><?php echo esc_html($t('not_accepted_label')); ?></h3>
                    <?php ndses_list($community['bulk_not_accepted'], 'compact-list danger-list'); ?>
                </div>
                <?php endif; ?>
            </div>
        </article>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
<?php
$recycling = array_filter([
    ['accepted_label', $community['accepted_recycling'] ?? [], 'compact-list'],
    ['not_accepted_label', $community['recycling_not_accepted'] ?? [], 'compact-list danger-list'],
    ['never_label', $community['recycling_never_accepted'] ?? [], 'compact-list danger-list'],
], static fn (array $box) => !empty($box[1]));
if ($recycling) : ?>
<section class="section soft-section">
    <div class="container">
        <div class="section-heading">
            <h2><?php echo esc_html($t('recycling_heading')); ?></h2>
        </div>
        <div class="card-grid three">
            <?php foreach ($recycling as [$label, $items, $class]) : ?>
            <article class="feature-card">
                <h3><?php echo esc_html($t($label)); ?></h3>
                <?php ndses_list($items, $class); ?>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
