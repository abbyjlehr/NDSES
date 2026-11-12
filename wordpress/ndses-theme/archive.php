<?php
get_header();
?>
<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy">
            <h1><?php the_archive_title(); ?></h1>
            <p class="hero-text"><?php the_archive_description(); ?></p>
        </div>
    </div>
</section>
<section class="section">
    <div class="container card-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('feature-card'); ?>>
                    <p class="kicker"><?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name ?? 'Update'); ?></p>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php the_excerpt(); ?>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No updates are available yet.</p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>

