<?php
get_header();
ndses_render_hero();
?>
<section class="section">
    <div class="container content-body">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('feature-card'); ?>>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php the_excerpt(); ?>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Nothing to show here. <a class="text-link" href="<?php echo esc_url(home_url('/')); ?>">Go to the home page</a></p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>

