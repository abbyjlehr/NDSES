<?php ndses_render_hero('make-a-payment'); ?>
<section class="section">
    <div class="container payment-grid">
        <?php
        // Field ids, input names, data-payment-fields, .form-status, and the
        // submit button are what assets/js/theme.js (PayEngine) hooks into.
        ?>
        <form class="site-form payment-form" method="post" data-payengine-form novalidate>
            <h2><?php echo esc_html(ndses_sec('form', 'heading', 'make-a-payment')); ?></h2>

            <fieldset class="form-group">
                <legend>Payment method</legend>
                <div class="method-options">
                    <label class="method-option">
                        <input type="radio" name="paymentMethod" value="card" checked>
                        <span><strong>Card</strong><small>Credit or debit card</small></span>
                    </label>
                    <label class="method-option">
                        <input type="radio" name="paymentMethod" value="ach">
                        <span><strong>Bank account (ACH)</strong><small>Checking or savings</small></span>
                    </label>
                </div>
            </fieldset>

            <fieldset class="form-group">
                <legend>Account details</legend>
                <div class="field-row">
                    <label>Account number <input required minlength="3" type="text" name="accountNumber" autocomplete="off"></label>
                    <label>Payment amount
                        <span class="input-affix">
                            <span class="input-affix-symbol" aria-hidden="true">$</span>
                            <input required type="text" inputmode="decimal" pattern="^\d+(\.\d{1,2})?$" name="amount" placeholder="0.00">
                        </span>
                    </label>
                </div>
                <label>Receipt email <input required type="email" name="email" autocomplete="email"></label>
            </fieldset>

            <fieldset class="form-group" data-payment-fields="card">
                <legend>Card details</legend>
                <label>Name on card <div id="pe-card-name" class="secure-field"></div></label>
                <label>Card number <div id="pe-card-number" class="secure-field"></div></label>
                <div class="field-row field-row--3">
                    <label>Expiration <div id="pe-card-expiry" class="secure-field"></div></label>
                    <label>CVC <div id="pe-card-cvc" class="secure-field"></div></label>
                    <label>Billing ZIP <div id="pe-card-zip" class="secure-field"></div></label>
                </div>
            </fieldset>

            <fieldset class="form-group" data-payment-fields="ach" hidden>
                <legend>Bank details</legend>
                <div class="field-row">
                    <label>First name <div id="pe-ach-first-name" class="secure-field"></div></label>
                    <label>Last name <div id="pe-ach-last-name" class="secure-field"></div></label>
                </div>
                <div class="field-row">
                    <label>Routing number <div id="pe-routing-number" class="secure-field"></div></label>
                    <label>Bank account number <div id="pe-account-number" class="secure-field"></div></label>
                </div>
            </fieldset>

            <p class="form-status" role="status"></p>
            <button class="button button-primary" type="submit">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                Submit Payment
            </button>
        </form>

        <aside class="quote-panel payment-aside">
            <h2><?php echo esc_html(ndses_sec('sidebar', 'heading', 'make-a-payment')); ?></h2>
            <p><?php echo esc_html(ndses_sec('sidebar', 'body', 'make-a-payment')); ?></p>
            <p class="small-note"><?php echo esc_html(ndses_sec('sidebar', 'note', 'make-a-payment')); ?></p>
            <p class="aside-help">Questions about your bill? <a href="<?php echo esc_url(ndses_phone_href()); ?>">Call <?php echo esc_html(ndses_setting('phone')); ?></a></p>
        </aside>
    </div>
</section>
