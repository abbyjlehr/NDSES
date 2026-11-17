# NDSES Website Handoff

For NDS Environmental Solutions staff. This sheet covers what you own, what to do once before launch, and where to go for everyday updates. Step-by-step instructions for every screen are in the [Website Guide](EDITOR_GUIDE.md).

## Your website

- **Site:** `https://ndses.flywheelsites.com`
- **Admin (where you edit):** `https://ndses.flywheelsites.com/wp-admin`
- **Built on:** WordPress, hosted on Flywheel, with the free Advanced Custom Fields (ACF) plugin for the edit forms.
- **Changes appear on the live site within about 5 minutes** of clicking Update or Publish.

## Accounts NDSES should own

Make sure an NDSES owner (not only the developer) can sign in to each of these. Keep passwords in a password manager, never in email or this document.

- [ ] WordPress admin (at least one NDSES Administrator account)
- [ ] Flywheel hosting account
- [ ] Domain registrar and DNS (Cloudflare, if used)
- [ ] PayEngine merchant dashboard
- [ ] Google account used for reCAPTCHA, Analytics, and Search Console
- [ ] Facebook and Instagram pages

## One-time setup before launch

1. **Site Settings** (left menu → Site Settings). Check phone, email, both addresses, office hours (one line per row), footer text, Facebook and Instagram links, Mission Statement, and About Page: Our Story. The **Email** field is where every website form submission is sent.
2. **Dumpster Sizes and Commercial Containers.** The site currently shows its built-in sizes, which are already up to date. To manage them yourself, enter **all** sizes first, then publish them together. As soon as one entry is published, the site shows only published entries.
3. **Open content questions:** confirm the 2 Yard dimensions (spreadsheet and graphic disagree), send the correct 8 Yard "Ideal For" text, and send a 96 Gallon graphic if you have one.
4. **Spam protection:** add the reCAPTCHA site key and secret key in Site Settings.
5. **Payments:** PayEngine confirmed the partner certification checklist on Nov 5, 2026. The site stays in test mode until the production keys are entered. Follow "Accepting Payments" in the Website Guide to switch to live keys.
   - **Certification checklist status (Oct 22, 2026):** every row is filled in, and all 8 sandbox test payments passed on the live page: $100 and $0.20 by card and by bank, a declined card, and a failed bank payment. One item is still open: before switching to live keys, confirm in the PayEngine dashboard that the live public key only works on the NDS website's domain.
6. **Legal pages:** replace the placeholder Privacy Policy and Terms text with your reviewed versions.
7. **Test it:** submit the Contact form yourself, confirm the email arrives, and confirm it's listed under **Form Submissions**.

## Everyday updates

| To change… | Go to |
| --- | --- |
| Phone, email, address, hours | Site Settings |
| Mission or company story | Site Settings → Mission Statement / About Page: Our Story |
| Weather delay, holiday, or closure alert | Service Notices → Add New (check "Site-wide announcement bar") |
| A community's pickup day or schedule | Communities → open the town |
| Dumpster sizes, dimensions, graphics | Dumpster Sizes |
| Commercial container options | Commercial Containers |
| FAQs | FAQs |
| Coverage map towns and counties | Service Areas |
| Banner at the top of a page | Pages → open the page → Hero |
| See who contacted you | Form Submissions (also emailed to you) |

## Good to know

- **Built-in content.** FAQs, Service Areas, Dumpster Sizes, and Commercial Containers each show built-in content until you publish your first entry of that kind. After that, only your entries show, so enter the full set before publishing.
- **Menus you can ignore:** Services, Documents, Schedule Items, and the "Page Sections" box on pages. They aren't used by the site.
- **Secret keys** (PayEngine Secret Key, reCAPTCHA Secret Key) work like passwords. Don't share them or paste them anywhere else.
- **Calculator logic** on the Dumpster Calculator page is set in code. Ask your developer to change which size it recommends.

## When to contact your developer

New pages or layout changes, adding a new community, changing recycling or bulk-item lists, payment or PayEngine problems, error messages, or access problems.

**Developer:** Abby Lehr, abby.lehr@radicalsurfaces.com
