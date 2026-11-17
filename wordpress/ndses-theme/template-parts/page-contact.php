<?php ndses_render_hero('contact'); ?>
<section class="section">
    <div class="container contact-grid">
        <?php ndses_render_inquiry_form('general'); ?>
        <aside class="quote-panel contact-aside">
            <h2><?php echo esc_html(ndses_sec('card', 'heading', 'contact')); ?></h2>
            <dl class="contact-list">
                <div>
                    <dt>Office</dt>
                    <dd><?php echo esc_html(ndses_setting('legal_name')); ?><br><?php echo esc_html(ndses_setting('address')); ?></dd>
                </div>
                <?php if (ndses_setting('mailing_address')) : ?>
                    <div>
                        <dt>Mailing</dt>
                        <dd><?php echo esc_html(ndses_setting('mailing_address')); ?></dd>
                    </div>
                <?php endif; ?>
                <div>
                    <dt>Phone</dt>
                    <dd><a href="<?php echo esc_url(ndses_phone_href()); ?>"><?php echo esc_html(ndses_setting('phone')); ?></a></dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd><a href="mailto:<?php echo esc_attr(ndses_setting('email')); ?>"><?php echo esc_html(ndses_setting('email')); ?></a></dd>
                </div>
            </dl>
            <h3><?php echo esc_html(ndses_sec('card', 'hours_heading', 'contact')); ?></h3>
            <ul class="hours-list">
                <?php foreach (ndses_hours_lines() as $line) : ?>
                    <li><?php echo esc_html($line); ?></li>
                <?php endforeach; ?>
            </ul>
        </aside>
    </div>
</section>

