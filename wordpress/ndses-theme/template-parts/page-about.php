<?php ndses_render_hero('about'); ?>
<section class="section">
    <div class="container split">
        <div>
            <h2><?php echo esc_html(ndses_sec('story', 'heading', 'about')); ?></h2>
            <?php echo ndses_get_our_story_html(); ?>
        </div>
        <div class="quote-panel">
            <h3><?php echo esc_html(ndses_sec('story', 'mission_heading', 'about')); ?></h3>
            <p><?php echo esc_html(ndses_get_mission()); ?></p>
        </div>
    </div>
</section>
<section class="section soft-section">
    <div class="container">
        <?php ndses_render_service_area_map(); ?>
    </div>
</section>
<?php
// Only cards with real copy are shown; the built-in "check back soon" text
// is a stand-in for the editor, never something visitors should see. With
// no real cards the whole Meet the Team section is left out.
$ndses_team = array_values(array_filter([1, 2, 3], static function (int $n): bool {
    $body = trim((string) ndses_sec('team', "member{$n}_body", 'about'));
    return $body !== '' && stripos($body, 'check back soon') === false;
}));
$ndses_team_heading = ndses_sec('team', 'heading', 'about');
?>
<?php if ($ndses_team) : ?>
<section class="section soft-section">
    <div class="container">
        <div class="section-heading">
            <h2><?php echo esc_html(stripos($ndses_team_heading, 'coming soon') === false ? $ndses_team_heading : 'Meet the team'); ?></h2>
        </div>
        <div class="card-grid three">
            <?php foreach ($ndses_team as $n) :
                $name = ndses_sec('team', "member{$n}_name", 'about');
                $photo = ndses_sec_image('team', "member{$n}_photo", 'about');
                ?>
                <article class="feature-card<?php echo $photo ? ' team-card' : ''; ?>">
                    <?php ndses_sec_img('team', "member{$n}_photo", 'team-photo', $name, 'about'); ?>
                    <h3><?php echo esc_html($name); ?></h3>
                    <p><?php echo esc_html(ndses_sec('team', "member{$n}_body", 'about')); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
