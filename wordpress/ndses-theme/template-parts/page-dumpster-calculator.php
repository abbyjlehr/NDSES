<?php ndses_render_hero('dumpster-calculator'); ?>
<section class="section">
    <div class="container calculator-shell" data-calculator>
        <form class="calculator-form">
            <label>Service address <input type="text" name="address" placeholder="Enter service address"></label>
            <label>Project type
                <select name="project">
                    <option>Home/Garage Cleanout</option>
                    <option>Home Remodel</option>
                    <option>Bathroom/Kitchen Demo</option>
                    <option>Roofing/Siding Teardown</option>
                    <option>Concrete, Dirt, and Rocks</option>
                    <option>Other</option>
                </select>
            </label>
            <fieldset>
                <legend>Materials</legend>
                <?php foreach (['Mixed Household Trash', 'Construction Debris', 'Wood', 'Other Heavy Materials'] as $material) : ?>
                    <label class="check-row"><input type="checkbox" name="materials" value="<?php echo esc_attr($material); ?>"> <?php echo esc_html($material); ?></label>
                <?php endforeach; ?>
            </fieldset>
            <button class="button button-primary" type="submit">Recommend a Size</button>
        </form>
        <aside class="calculator-result" aria-live="polite">
            <p class="result-label"><?php echo esc_html(ndses_sec('result', 'eyebrow', 'dumpster-calculator')); ?></p>
            <h2><?php echo esc_html(ndses_sec('result', 'heading', 'dumpster-calculator')); ?></h2>
            <p><?php echo esc_html(ndses_sec('result', 'body', 'dumpster-calculator')); ?></p>
            <p class="small-note"><?php echo esc_html(ndses_sec('result', 'note', 'dumpster-calculator')); ?></p>
            <?php ndses_button(ndses_sec('result', 'button', 'dumpster-calculator'), ndses_dumpster_request_url()); ?>
        </aside>
    </div>
</section>
<section class="section soft-section">
    <div class="container">
        <div class="section-heading">
            <h2><?php echo esc_html(ndses_sec('compare', 'heading', 'dumpster-calculator')); ?></h2>
        </div>
        <?php ndses_render_dumpster_cards(); ?>
    </div>
</section>

