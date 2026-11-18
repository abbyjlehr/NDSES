<?php
ndses_render_hero('dumpster-calculator');
$c = static fn (string $field): string => ndses_sec('form', $field, 'dumpster-calculator');
$projects = [];
foreach (ndses_sec_lines('form', 'projects', 'dumpster-calculator') as $line) {
    [$project, $size] = array_pad(array_map('trim', explode('=', $line, 2)), 2, '');
    if ($project !== '') {
        $projects[$project] = $size;
    }
}
$config = [
    'rules' => $projects,
    'heavy' => $c('heavy_label'),
    'heavySize' => $c('heavy_size'),
    'heavyNote' => $c('heavy_note'),
    'resultText' => $c('result_text'),
];
?>
<section class="section">
    <div class="container calculator-shell" data-calculator data-calculator-config="<?php echo esc_attr(wp_json_encode($config)); ?>">
        <form class="calculator-form">
            <label><?php echo esc_html($c('address_label')); ?> <input type="text" name="address" placeholder="<?php echo esc_attr($c('address_placeholder')); ?>"></label>
            <label><?php echo esc_html($c('project_label')); ?>
                <select name="project">
                    <?php foreach (array_keys($projects) as $project) : ?>
                        <option><?php echo esc_html($project); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <fieldset>
                <legend><?php echo esc_html($c('materials_label')); ?></legend>
                <?php foreach (array_filter(array_merge(ndses_sec_lines('form', 'materials', 'dumpster-calculator'), [$c('heavy_label')])) as $material) : ?>
                    <label class="check-row"><input type="checkbox" name="materials" value="<?php echo esc_attr($material); ?>"> <?php echo esc_html($material); ?></label>
                <?php endforeach; ?>
            </fieldset>
            <button class="button button-primary" type="submit"><?php echo esc_html($c('submit')); ?></button>
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
