<?php ndses_render_hero('whats-new'); ?>
<section class="section">
    <div class="container">
        <div class="card-grid notice-grid">
            <?php foreach (ndses_get_notices() as $notice) : ?>
                <article class="feature-card notice-card">
                    <?php
                    $date = (string) ($notice['date'] ?? '');
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                        $date = wp_date('F j, Y', strtotime($date));
                    }
                    ?>
                    <p class="kicker"><?php echo esc_html($notice['type']); ?><?php if ($date !== '') : ?> · <?php echo esc_html($date); ?><?php endif; ?></p>
                    <h2><?php echo esc_html($notice['title']); ?></h2>
                    <p><?php echo esc_html($notice['body']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

