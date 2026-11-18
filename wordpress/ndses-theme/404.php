<?php get_header(); ?>
<section class="hero-section">
    <div class="container hero-grid hero-grid--no-media">
        <div class="hero-copy">
            <h1><?php echo esc_html(ndses_sw('notfound', 'heading')); ?></h1>
            <p class="hero-text"><?php echo esc_html(ndses_sw('notfound', 'text')); ?></p>
            <div class="hero-actions">
                <?php ndses_button(ndses_sw('notfound', 'home_button'), home_url('/')); ?>
                <?php ndses_button(ndses_sw('notfound', 'call_button'), ndses_phone_href(), 'button button-secondary'); ?>
            </div>
        </div>
    </div>
</section>
<?php $ndses_cards = ndses_sw_links('notfound', 'cards'); ?>
<?php if ($ndses_cards) : ?>
<section class="section">
    <div class="container">
        <div class="card-grid three">
            <?php foreach ($ndses_cards as $card) : ?>
                <article class="feature-card">
                    <h3><?php echo esc_html($card['label']); ?></h3>
                    <?php if ($card['text'] !== '') : ?><p><?php echo esc_html($card['text']); ?></p><?php endif; ?>
                    <a class="text-link" href="<?php echo esc_url($card['url']); ?>"><?php echo esc_html(trim(ndses_sw('notfound', 'card_link') . ' ' . $card['label'])); ?></a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php get_footer(); ?>
