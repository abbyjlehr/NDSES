(function () {
  // Scroll-reveal: fade/slide in each section's content once it enters the
  // viewport. Skipped entirely when the visitor prefers reduced motion, or
  // when IntersectionObserver isn't available — content just stays visible.
  const prefersMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: no-preference)').matches;
  if (prefersMotion && 'IntersectionObserver' in window) {
    const revealTargets = document.querySelectorAll('.section > .container');
    const observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );
    revealTargets.forEach(function (target) {
      target.setAttribute('data-reveal', '');
      observer.observe(target);
    });
  }

  initNavigation();

  /**
   * Header navigation. Same behavior as the Next.js header
   * (components/layout/Header.tsx):
   * - Desktop dropdowns open on mouse hover and close shortly after the
   *   pointer leaves the trigger + panel region (no flicker crossing the
   *   gap); touch, pen, and keyboard toggle on click. Escape and clicks
   *   outside close; arrow keys, Home and End move through the links.
   * - Mobile sheet with scroll lock, accordion sections, Escape to close.
   */
  function initNavigation() {
    const CLOSE_DELAY_MS = 140;
    const nav = document.querySelector('[data-desktop-nav]');
    const items = nav ? [...nav.querySelectorAll('[data-nav-item]')] : [];
    const triggers = items.map((item) => item.querySelector('[data-nav-trigger]'));
    let openItem = null;
    let closeTimer;
    let lastPointer = '';

    const panelOf = (item) => item.querySelector('[data-nav-panel]');
    const triggerOf = (item) => item.querySelector('[data-nav-trigger]');
    const linksOf = (item) => [...panelOf(item).querySelectorAll('a')];

    function setOpen(item) {
      window.clearTimeout(closeTimer);
      items.forEach((other) => {
        const open = other === item;
        panelOf(other).hidden = !open;
        triggerOf(other).setAttribute('aria-expanded', String(open));
      });
      openItem = item;
    }

    function closeAll() {
      setOpen(null);
    }

    items.forEach((item, index) => {
      const trigger = triggerOf(item);

      item.addEventListener('pointerenter', (event) => {
        if (event.pointerType === 'mouse') setOpen(item);
      });
      item.addEventListener('pointerleave', (event) => {
        if (event.pointerType !== 'mouse') return;
        window.clearTimeout(closeTimer);
        closeTimer = window.setTimeout(closeAll, CLOSE_DELAY_MS);
      });

      trigger.addEventListener('pointerdown', (event) => {
        lastPointer = event.pointerType;
      });
      trigger.addEventListener('click', () => {
        // A mouse already opened the menu on hover, so its click keeps it open.
        if (lastPointer === 'mouse') setOpen(item);
        else setOpen(openItem === item ? null : item);
      });

      trigger.addEventListener('keydown', (event) => {
        lastPointer = 'keyboard';
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
          event.preventDefault();
          setOpen(item);
          const links = linksOf(item);
          (event.key === 'ArrowDown' ? links[0] : links[links.length - 1]).focus();
        } else if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
          event.preventDefault();
          const next = triggers[(index + (event.key === 'ArrowRight' ? 1 : -1) + triggers.length) % triggers.length];
          next.focus();
          if (openItem) setOpen(items[triggers.indexOf(next)]);
        }
      });

      panelOf(item).addEventListener('keydown', (event) => {
        const links = linksOf(item);
        const current = links.indexOf(document.activeElement);
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
          event.preventDefault();
          const step = event.key === 'ArrowDown' ? 1 : -1;
          links[(current + step + links.length) % links.length].focus();
        } else if (event.key === 'Home' || event.key === 'End') {
          event.preventDefault();
          links[event.key === 'Home' ? 0 : links.length - 1].focus();
        } else if (event.key === 'Tab' && !event.shiftKey && current === links.length - 1) {
          closeAll();
        } else if (event.key === 'Tab' && event.shiftKey && current === 0) {
          event.preventDefault();
          trigger.focus();
        }
      });
    });

    if (nav) {
      nav.addEventListener('focusout', (event) => {
        if (!nav.contains(event.relatedTarget)) closeAll();
      });
    }

    document.addEventListener('pointerdown', (event) => {
      if (openItem && nav && !nav.contains(event.target)) closeAll();
    });

    document.addEventListener('keydown', (event) => {
      if (event.key !== 'Escape') return;
      if (openItem) {
        const trigger = triggerOf(openItem);
        closeAll();
        trigger.focus();
      }
      if (sheet && !sheet.hidden) {
        setSheet(false);
        toggle.focus();
      }
    });

    // Mobile sheet.
    const toggle = document.querySelector('[data-menu-toggle]');
    const sheet = document.querySelector('[data-mobile-nav]');
    const label = toggle ? toggle.querySelector('[data-menu-label]') : null;

    function setSheet(open) {
      if (!toggle || !sheet) return;
      sheet.hidden = !open;
      toggle.setAttribute('aria-expanded', String(open));
      toggle.classList.toggle('is-open', open);
      if (label) label.textContent = open ? 'Close' : 'Menu';
      document.body.style.overflow = open ? 'hidden' : '';
      if (open) {
        const first = sheet.querySelector('a, button');
        if (first) first.focus();
      }
    }

    if (toggle && sheet) {
      toggle.addEventListener('click', () => setSheet(sheet.hidden));
      sheet.querySelectorAll('[data-mobile-group]').forEach((group) => {
        group.addEventListener('click', () => {
          const sub = document.getElementById(group.getAttribute('aria-controls'));
          const open = group.getAttribute('aria-expanded') !== 'true';
          group.setAttribute('aria-expanded', String(open));
          sub.hidden = !open;
        });
      });
      // Choosing a destination closes the sheet (including same-page #anchors).
      sheet.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setSheet(false)));
      window.matchMedia('(min-width: 1100px)').addEventListener('change', (event) => {
        if (event.matches) setSheet(false);
      });
    }

    // Leaving via the back/forward cache shouldn't restore an open menu.
    window.addEventListener('pageshow', () => {
      closeAll();
      setSheet(false);
    });
  }

  const calculator = document.querySelector('[data-calculator]');
  if (calculator) {
    const form = calculator.querySelector('form');
    const result = calculator.querySelector('.calculator-result');
    const rules = {
      'Home/Garage Cleanout': '10 Yard Dumpster',
      'Home Remodel': '15 Yard Dumpster',
      'Bathroom/Kitchen Demo': '12 Yard Dumpster',
      'Roofing/Siding Teardown': '20 Yard Dumpster',
      'Concrete, Dirt, and Rocks': '10 Yard Dumpster',
      Other: '20 Yard Dumpster',
    };

    // The eyebrow, small print, and button text are editable in wp-admin
    // (Dumpster Calculator Page Sections), so reuse what the page rendered.
    const resultText = function (selector, fallback) {
      const el = result.querySelector(selector);
      return el ? el.innerHTML : fallback;
    };
    const resultEyebrow = resultText('.eyebrow', 'Recommendation');
    const resultNote = resultText('.small-note', '');
    const resultButton = resultText('.button', 'Request Quote');

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      const data = new FormData(form);
      const project = data.get('project') || 'Home Remodel';
      const materials = data.getAll('materials');
      let recommendation = rules[project] || '15 Yard Dumpster';
      let materialsNote = '';

      // Heavy materials (concrete, dirt, roofing tear-off, etc.) hit a
      // dumpster's weight limit long before it's visually full, so the
      // safe size is capped regardless of project type. The note always
      // shows when this box is checked -- even for a project that already
      // maps to 10 Yard -- so the result doesn't silently match the
      // untouched default and read as the tool ignoring the selection.
      if (materials.includes('Other Heavy Materials')) {
        recommendation = '10 Yard Dumpster';
        materialsNote = ' Heavy materials (concrete, dirt, roofing tear-off, etc.) hit a dumpster’s weight limit well before it fills up, so we cap the recommended size for heavy loads regardless of project type.';
      }

      result.innerHTML = '<p class="eyebrow">' + resultEyebrow + '</p><h2>' + recommendation + '</h2><p>This size is a good starting point for your selected project.' + materialsNote + ' NDS can confirm final sizing, material restrictions, and availability when you request a quote.</p>' + (resultNote ? '<p class="small-note">' + resultNote + '</p>' : '') + '<a class="button button-primary" href="' + window.location.origin + '/dumpster-rentals/?service=dumpster&size=' + encodeURIComponent(recommendation.split(' ')[0] + '-yard') + '#quote">' + resultButton + '</a>';
    });

    ndsesInitAddressLookup(form.querySelector('input[name="address"]'));
  }

  /**
   * Live address autocomplete + service-county check, powered by the
   * ndses_geocode AJAX action (server-side proxy to OpenStreetMap Nominatim).
   */
  function ndsesInitAddressLookup(input) {
    if (!input || typeof ndsesData === 'undefined') return;

    const field = input.closest('label') || input.parentElement;
    field.style.position = 'relative';

    const list = document.createElement('ul');
    list.className = 'address-suggestions';
    list.hidden = true;
    field.appendChild(list);

    const status = document.createElement('small');
    status.className = 'address-status';
    field.appendChild(status);

    let debounceId = null;
    let lastSelected = '';

    function renderSuggestions(results) {
      list.innerHTML = '';
      if (!results.length) {
        list.hidden = true;
        return;
      }
      results.forEach(function (result) {
        const item = document.createElement('li');
        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = result.label;
        button.addEventListener('click', function () {
          input.value = result.label;
          lastSelected = result.label;
          list.hidden = true;
          showStatus(result.county);
        });
        item.appendChild(button);
        list.appendChild(item);
      });
      list.hidden = false;
    }

    // The county list comes from the published Service Areas (see
    // ndses_service_counties()); the home county is Walworth (see
    // ndses_home_county()). Walworth gets a definite yes, the other listed
    // counties a "likely, call to confirm", and anything else the outside
    // message.
    function showStatus(county) {
      const listed = county && (ndsesData.serviceCounties || []).indexOf(county) !== -1;
      const home = listed && county === ndsesData.homeCounty;
      status.classList.toggle('in-area', Boolean(listed));
      if (home) {
        status.textContent = '✓ ' + county + ' County is in the NDS service area.';
      } else if (listed) {
        status.textContent = '✓ ' + county + ' County is likely in the NDS service area — call ' + ndsesData.phone + ' to confirm service at your address.';
      } else if (county) {
        status.textContent = county + ' County is outside the core NDS service area — call ' + ndsesData.phone + ' to confirm.';
      } else {
        status.textContent = '';
      }
    }

    input.addEventListener('input', function () {
      if (input.value !== lastSelected) {
        status.textContent = '';
        status.classList.remove('in-area');
      }
      if (debounceId) clearTimeout(debounceId);
      const query = input.value.trim();
      if (query.length < 3 || query === lastSelected) {
        list.hidden = true;
        return;
      }
      debounceId = setTimeout(function () {
        fetch(ndsesData.ajaxUrl + '?action=ndses_geocode&q=' + encodeURIComponent(query))
          .then(function (response) { return response.json(); })
          .then(function (data) { renderSuggestions(data.results || []); })
          .catch(function () { list.hidden = true; });
      }, 400);
    });

    input.addEventListener('blur', function () {
      setTimeout(function () { list.hidden = true; }, 150);
    });
  }

  /**
   * PayEngine payment form. Card/bank details are tokenized client-side by
   * PayEngine's SecureFields JS SDK (hosted iframes — the raw numbers never
   * touch this page or NDS's server), then the resulting token is posted to
   * the ndses/v1/payments/charge REST route, which charges it server-side.
   */
  const payEngineForm = document.querySelector('[data-payengine-form]');
  if (payEngineForm) {
    const statusEl = payEngineForm.querySelector('.form-status');
    const submitButton = payEngineForm.querySelector('button[type="submit"]');
    const cardFields = payEngineForm.querySelector('[data-payment-fields="card"]');
    const achFields = payEngineForm.querySelector('[data-payment-fields="ach"]');
    // PayEngine's createCard()/createBankAccount() validate every field
    // registered on that SecureFields form instance, not just the ones
    // relevant to the call being made. Card and ACH fields must therefore
    // live on two separate instances, or submitting a card payment fails
    // validation against the empty, hidden ACH fields (and vice versa).
    let cardForm = null;
    let achForm = null;

    function syncPaymentFields() {
      const method = (payEngineForm.querySelector('input[name="paymentMethod"]:checked') || {}).value || 'card';
      cardFields.hidden = method !== 'card';
      achFields.hidden = method !== 'ach';
    }

    payEngineForm.querySelectorAll('input[name="paymentMethod"]').forEach(function (radio) {
      radio.addEventListener('change', syncPaymentFields);
    });

    function initSecureFields() {
      if (!window.PayEngine || (cardForm && achForm)) return;
      const css = { fontFamily: 'inherit', fontSize: '16px', width: '100%', height: '46px', padding: '10px 12px', color: '#14211a' };

      Promise.all([
        window.PayEngine.SecureFields.create(),
        window.PayEngine.SecureFields.create()
      ]).then(function (forms) {
        cardForm = forms[0];
        achForm = forms[1];

        cardForm.field('#pe-card-name', { type: 'text', name: 'card_holder', placeholder: 'Name on card', validations: ['required'], css: css });
        cardForm.field('#pe-card-number', { type: 'card-number', name: 'card_number', placeholder: 'Card number', showCardIcon: true, validations: ['required', 'validCardNumber'], css: css });
        cardForm.field('#pe-card-expiry', { type: 'card-expiration-date', name: 'card_exp', placeholder: 'MM / YY', validations: ['required', 'validCardExpirationDate'], css: css });
        cardForm.field('#pe-card-cvc', { type: 'card-security-code', name: 'card_cvc', placeholder: 'CVC', maxLength: 4, validations: ['required', 'validCardSecurityCode'], css: css });
        cardForm.field('#pe-card-zip', { type: 'zip-code', name: 'address_zip', placeholder: 'Billing ZIP', validations: ['required'], css: css });

        achForm.field('#pe-routing-number', { type: 'number', name: 'routing_number', placeholder: 'Routing number', validations: ['required'], css: css });
        achForm.field('#pe-account-number', { type: 'number', name: 'account_number', placeholder: 'Account number', validations: ['required'], css: css });
        achForm.field('#pe-ach-first-name', { type: 'text', name: 'first_name', placeholder: 'First name', validations: ['required'], css: css });
        achForm.field('#pe-ach-last-name', { type: 'text', name: 'last_name', placeholder: 'Last name', validations: ['required'], css: css });

        if (submitButton) submitButton.disabled = false;
      });
    }

    if (submitButton) submitButton.disabled = true;
    if (window.PayEngine) {
      initSecureFields();
    } else {
      const securefieldsScript = document.getElementById('payengine-securefields-js');
      if (securefieldsScript) securefieldsScript.addEventListener('load', initSecureFields);
    }

    payEngineForm.addEventListener('submit', function (event) {
      event.preventDefault();
      if (!payEngineForm.checkValidity()) {
        payEngineForm.reportValidity();
        return;
      }
      if (!cardForm || !achForm) {
        if (statusEl) {
          statusEl.textContent = 'Payment fields are still loading. Please wait a moment and try again.';
          statusEl.className = 'form-status is-error';
        }
        return;
      }

      const paymentMethod = (payEngineForm.querySelector('input[name="paymentMethod"]:checked') || {}).value || 'card';
      const values = Object.fromEntries(new FormData(payEngineForm).entries());

      if (submitButton) submitButton.disabled = true;
      if (statusEl) {
        statusEl.textContent = 'Processing your payment…';
        statusEl.className = 'form-status';
      }

      const tokenize = paymentMethod === 'ach'
        ? achForm.createBankAccount()
        : cardForm.createCard({ manuallyEntered: true });

      tokenize
        .then(function (tokenObj) {
          const token = tokenObj.token
            || (tokenObj.card && tokenObj.card.token)
            || (tokenObj.bank_account && tokenObj.bank_account.token);

          if (!token) {
            throw new Error('no-token');
          }

          return fetch(ndsesData.restUrl + 'payments/charge', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              token: token,
              paymentMethod: paymentMethod,
              accountNumber: values.accountNumber,
              amount: values.amount,
              email: values.email
            })
          });
        })
        .then(function (response) { return response.json().then(function (body) { return { ok: response.ok, body: body }; }); })
        .then(function (result) {
          const mode = result.body && result.body.mode;
          if (result.ok && mode === 'success') {
            // form.reset() puts the method back to Card without firing
            // 'change', and doesn't reach the SecureFields iframes, so
            // re-sync the visible fields and clear the card/bank details.
            payEngineForm.reset();
            syncPaymentFields();
            [cardForm, achForm].forEach(function (form) {
              try { form.reset(); } catch (error) { /* older SDKs lack reset() */ }
            });
            if (statusEl) {
              statusEl.textContent = result.body.message || 'Thank you! Your payment has been received.';
              statusEl.className = 'form-status is-success';
            }
          } else if (statusEl) {
            statusEl.textContent = (result.body && result.body.message) || 'Payment could not be completed. Please try again or contact NDS.';
            statusEl.className = 'form-status is-error';
          }
        })
        .catch(function () {
          if (statusEl) {
            statusEl.textContent = 'We could not process those payment details. Please check them and try again.';
            statusEl.className = 'form-status is-error';
          }
        })
        .finally(function () {
          if (submitButton) submitButton.disabled = false;
        });
    });
  }

  /**
   * Inquiry forms (contact, quote requests) submit to the ndses/v1/forms
   * REST route, which honeypot-checks, rate-limits, stores the submission
   * as a form_submission post, and emails the office.
   */
  const SERVICE_TYPE_BY_CODE = {
    residential: 'Residential Trash & Recycling',
    commercial: 'Commercial Trash & Recycling',
    dumpster: 'Dumpster Rental',
  };

  document.querySelectorAll('[data-inquiry-form]').forEach(function (form) {
    const status = form.querySelector('.form-status');
    const submitButton = form.querySelector('button[type="submit"]');

    // Cross-page handoff: "Request This Size"/"Request A Free Quote" links
    // elsewhere on the site navigate here with ?service=&size= in the URL.
    const params = new URLSearchParams(window.location.search);
    const serviceCode = params.get('service');
    if (serviceCode && SERVICE_TYPE_BY_CODE[serviceCode]) {
      const serviceSelect = form.querySelector('select[name="serviceType"]');
      if (serviceSelect) serviceSelect.value = SERVICE_TYPE_BY_CODE[serviceCode];
    }
    const size = params.get('size');
    if (size) {
      const sizeField = form.querySelector('[data-dumpster-size-field]');
      if (sizeField) sizeField.value = size;
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const data = Object.fromEntries(new FormData(form).entries());
      if (submitButton) submitButton.disabled = true;
      if (status) {
        status.textContent = 'Sending your request…';
        status.className = 'form-status';
      }

      // reCAPTCHA v3 runs invisibly -- no checkbox, no challenge. Resolves
      // to an empty token if it isn't configured yet, which the server
      // treats as "spam protection not enabled" rather than a failure.
      const getToken = ndsesData.recaptchaSiteKey && window.grecaptcha
        ? new Promise(function (resolve) {
            grecaptcha.ready(function () {
              grecaptcha.execute(ndsesData.recaptchaSiteKey, { action: 'submit' }).then(resolve);
            });
          })
        : Promise.resolve('');

      getToken
        .then(function (token) {
          data.captchaToken = token;
          return fetch(ndsesData.restUrl + 'forms', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
          });
        })
        .then(function (response) {
          return response.json().then(function (body) { return { ok: response.ok, body: body }; });
        })
        .then(function (result) {
          if (result.ok) {
            form.reset();
            if (status) {
              status.textContent = 'Thanks — your request has been sent. NDS will be in touch soon.';
              status.className = 'form-status is-success';
            }
          } else {
            if (status) {
              status.textContent = (result.body && result.body.message) || 'Something went wrong. Please try again or call NDS directly.';
              status.className = 'form-status is-error';
            }
          }
        })
        .catch(function () {
          if (status) {
            status.textContent = 'Something went wrong. Please try again or call NDS directly.';
            status.className = 'form-status is-error';
          }
        })
        .finally(function () {
          if (submitButton) submitButton.disabled = false;
        });
    });
  });
})();

