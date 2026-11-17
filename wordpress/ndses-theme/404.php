<?php get_header(); ?>
<section class="hero-section">
    <div class="container hero-grid hero-grid--no-media">
        <div class="hero-copy">
            <h1>Page not found</h1>
            <p class="hero-text">The page you're looking for has moved or doesn't exist. Try one of these instead, or call NDS and we'll help.</p>
            <div class="hero-actions">
                <?php ndses_button('Go to the home page', home_url('/')); ?>
                <?php ndses_button('Call NDS', ndses_phone_href(), 'button button-secondary'); ?>
            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="card-grid three">
            <?php foreach ([
                ['Residential Trash & Recycling', '/residential/', 'Pickup days and schedules for your community.'],
                ['Dumpster Rentals', '/dumpster-rentals/', 'Sizes, pricing questions, and rental requests.'],
                ['Make a Payment', '/make-a-payment/', 'Pay your NDS bill online.'],
            ] as [$label, $path, $text]) : ?>
                <article class="feature-card">
                    <h3><?php echo esc_html($label); ?></h3>
                    <p><?php echo esc_html($text); ?></p>
                    <a class="text-link" href="<?php echo esc_url(home_url($path)); ?>">Go to <?php echo esc_html($label); ?></a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>
