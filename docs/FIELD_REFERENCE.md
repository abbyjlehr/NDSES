# NDS Website — Field Reference

This is a box-by-box list of every editable field (ACF field) in the website's admin, what each one changes on the live site, and tips for filling it in. Use it alongside the **NDS Website Guide**, which walks through common tasks step by step, and the **How-To Videos**.

**How to use this:** find the screen you're on in the left-hand menu of WordPress, then look it up below. Field names here match the labels you'll see on screen exactly.

**Three rules that apply everywhere:**

- Nothing changes on the live site until you click **Update**, **Publish** or **Save Settings**.
- Changes show up on the live site within about 5 minutes. Hard-refresh the page (Cmd+Shift+R on Mac, Ctrl+Shift+R on Windows) to see them.
- If a box is left empty, the site shows the text it has now. You can't "break" a page by clearing a box, but check the page afterward.

## Contents

- [Site Settings](#site-settings)
- [Site-wide Text & Images](#site-wide-text--images)
- [Pages: the Page Hero & SEO box](#pages-the-page-hero--seo-box)
- [Pages: Page Sections, page by page](#pages-page-sections-page-by-page)
- [Communities (town pages)](#communities-town-pages)
- [Dumpster Sizes](#dumpster-sizes)
- [Commercial Containers](#commercial-containers)
- [FAQs](#faqs)
- [Service Areas (the map)](#service-areas-the-map)
- [Service Notices (alerts)](#service-notices-alerts)
- [Things to leave alone](#things-to-leave-alone)
- [Changes that need your developer](#changes-that-need-your-developer)

---

## Site Settings

**Where:** left menu → **Site Settings**. Click **Save Settings** at the bottom when done. These fields feed the header, footer and Contact page on every page of the site, so you only change them in one place.

| Field | What it changes | Tips |
|---|---|---|
| Company Name | The business name used in search results and when the site is shared on social media. | Keep as "NDS Environmental Solutions". |
| Legal Name | The name shown above the address on the Contact page. | e.g. "NDS Environmental Solutions, LLC". |
| Phone | Every phone number and "Call" button on the site: header, footer, Contact page, payment page. | Type it the way you want it shown, e.g. 262-233-6131. The tap-to-call link updates automatically. |
| Email | The email address in the footer and on the Contact page. | |
| Facility Address | The street address in the footer and on the Contact page. | |
| Mailing Address | The "Mailing:" line in the footer and Contact page. | Leave empty to hide it. |
| Office Hours | The hours in the green bar at the very top of every page and in the footer. | One line per row, e.g. `Monday - Thursday: 8:00am - 4:00pm CT`. |
| Service Area Summary | A short description of where you work, used by Google. Not shown as text on a page. | One sentence listing counties or towns is enough. |
| Facebook URL / Instagram URL | Adds a Facebook or Instagram link in the footer. | Paste the full address, starting with https://. Leave empty and the icon won't show. |
| Footer Description | The short sentence under the logo in the footer. | |
| Copyright Text | The line at the very bottom of every page. | **Keep the sentence about AI-generated images** (see [Things to leave alone](#things-to-leave-alone)). |
| Mission Statement | The "Our Mission" box on the Home and About pages. | |
| About Page: Our Story | The company history on the About page. | Leave blank to keep the current text. |
| Spam Protection (reCAPTCHA) | Two keys that stop spam on the contact forms. | Your developer sets these. Don't change them. |
| Analytics: Google Tag Manager ID | Turns on Google Analytics tracking. Already set to GTM-KFW62PL4. | Only works on ndses.com. Leave as is. |

| Header Logo | The logo at the top left of every page. | A PNG with a transparent background works best. Leave empty to keep the current logo. |
| Footer Logo | The logo in the footer. | Leave empty to use the Header Logo. |

## Site-wide Text & Images

**Where:** left menu → **Site Settings**, further down the screen, in the **Site-wide Text & Images** box. Click **Save Settings** at the bottom. Leave any box empty to go back to the built-in wording.

| Tab and field | What it changes | Tips |
|---|---|---|
| Logos & Icons → Browser tab icon | The small square icon in browser tabs and bookmarks. | Square image, at least 512 × 512 pixels. |
| Logos & Icons → Share picture | The picture shown when someone shares a link to the site on Facebook or in a text. | 1200 × 630 pixels works best. Empty = the home page truck photo. |
| Header → Top bar text | The short line in the dark green bar at the very top of every page. | The office hours beside it come from Office Hours. |
| Header → Quote button / Payment button | The two buttons at the top right ("Get a Quote", "Pay Bill"). | |
| Header → Alert banner link | The link at the end of the yellow alert banner. | Only shows while an alert is on. |
| Page Banner Buttons → First / Second button | The two buttons under the big title on each page ("Get a Quote", "Call NDS"). | |
| Page Banner Buttons → Dumpster link | The "Rent a Dumpster" link on the Home and Dumpster Rentals pages. | |
| Footer → Call-to-action heading / text / button | The dark green "Need trash service…" box at the top of the footer. | |
| Footer → Column headings | "Contact", "Services", "Customers". | |
| Footer → Services / Customers column links, Bottom links | The links in the footer. | One link per line, written as `Link text \| /page-address/`, e.g. `FAQs \| /faqs/`. |
| Page Not Found → all fields | The page visitors see if they follow a broken or old link. | Suggestion cards are one per line: `Title \| /page-address/ \| Short description`. |

## Pages: the Page Hero & SEO box

**Where:** left menu → **Pages** → click a page name. This box sits at the top of every main page.

| Field | What it changes | Tips |
|---|---|---|
| Heading | The big title at the top of the page. | Keep it short, ideally under 10 words. |
| Description | The paragraph under the big title. | Two or three sentences. |
| Image | The large picture beside the title. | Wide photos work best. They're cropped to a 16:10 shape. Use JPG or WebP, under 500 KB if you can. On the Home page, the truck photo shows until you upload one here. |
| Image Alt Text | A description of the picture for people using screen readers, and for Google. | Describe what's in the photo, e.g. "NDS truck collecting curbside recycling". |
| SEO Title | The page title shown in Google results and on the browser tab. | Leave empty to use the page name. Under 60 characters. |
| SEO Description | The grey description under the title in Google results. | Leave empty to use the page Description. Aim for 120–155 characters. |

## Pages: Page Sections, page by page

**Where:** under the Page Hero & SEO box on each main page there's a **[Page name] Page Sections** box with one tab per section of the page. Each tab's fields change only the words in that section. The name of each box matches the heading you see on the live page.

Fields marked **(one per line)** turn each line you type into a bullet point on the site. Press Enter between items.

**Home**
- **Our Services:** the section heading, the three service cards (title and text for Residential, Commercial and Dumpster Rentals) and the "Explore service" link text.
- **Automatic Payments:** heading, text, download-button text, and the **ACH authorization form (PDF)**. Upload a new PDF here to replace the form customers download.
- **Why NDS:** heading, text, and the heading of the mission box. The mission text itself is in Site Settings.
- **Dumpster Sizes:** heading and button text. The size cards themselves come from **Dumpster Sizes** in the left menu.
- **FAQs:** heading. The questions come from **FAQs** in the left menu (Dumpster Rentals category).

**Residential**
- **Communities:** heading and link text. The town cards come from **Communities** in the left menu.
- **Pickup Expectations:** heading and rules (one per line).
- **Accepted Recycling:** heading and accepted items (one per line).
- **Town Pages:** the headings, labels and buttons that every town page shares ("Service Information", "Printable schedules & guides", "Download", "Request Service" and so on). Each town's own content is under **Communities**.
- The **Communities** tab also has **Word after the service day** (cards read "Tuesday collection").

**Commercial**
- **Areas We Service:** heading, counties (one per line), the note under the list, and the county map image. Type `{phone}` where you want the office phone number to appear. It fills in automatically from Site Settings.
- **Collection Schedules:** heading and schedules (one per line).
- **Container Options:** heading. The cards come from **Commercial Containers** in the left menu.
- **Commercial Recycling:** heading, text, and materials (one per line).
- **Industries We Serve:** heading and industries (one per line).
- **Quote Form:** heading and text above the form.

**Dumpster Rentals**
- **Size Guide:** heading and button text. The cards come from **Dumpster Sizes**.
- **Materials:** the accepted and not-accepted headings and lists (one per line).
- **Rental Process:** heading, plus a title and text for each of the 4 steps.
- **Quote Form:** heading and text above the form.

**Dumpster Calculator**
- **Calculator Form:** the form's labels and button, the **projects** and the size each one suggests (one per line, written as `Project = Size`, e.g. `Home Remodel = 15 Yard Dumpster`; the size must match a Dumpster Sizes title exactly), the materials checkboxes, the heavy-materials checkbox with the size it always suggests and its note, and the text shown under the suggested size.
- **Recommendation Box:** label, the starting heading and text (shown before a visitor picks a project), the small print, and the button text.
- **Compare Sizes:** heading. The cards come from **Dumpster Sizes**.

**Special Events**
- **Event Options:** heading, plus a title and text for each of the 3 cards.
- **Quote Form:** heading and text above the form.

**About**
- **Our Story:** heading and mission-box heading. The story and mission text are in Site Settings.
- **Meet The Team:** heading, plus a name or role, text and photo for up to 3 people. This section stays hidden until at least one card has text, so you can add your team whenever you're ready.

**Contact**
- **Contact Box:** the heading and the hours heading. The address, phone, email and hours come from Site Settings.

**Make a Payment**
- **Payment Form:** the heading above the payment form. The payment boxes themselves can't be changed, so the card processor keeps working.
- **Side Box:** heading, text and small print in the box beside the form.

**Privacy Policy, Terms and Photo Credits** are ordinary text pages. Edit the words directly in the page editor.

## Communities (town pages)

**Where:** left menu → **Communities** → click a town. Each Community **is** a town page. The town name is the title at the top; everything else is in the **Town Page** box, in tabs.

| Tab and field | What it changes | Tips |
|---|---|---|
| Intro & Schedule → Town or city | The small label above the name on the town's card on the Residential page. | Town, City or Village. |
| Intro & Schedule → Intro paragraph | The paragraph under the town name at the top of the page. | |
| Intro & Schedule → Service day | The "Service Day" line, the town's card on the Residential page, and its line in the Residential menu. | e.g. `Tuesday`. |
| Intro & Schedule → Trash schedule / Recycling schedule | The "Trash:" and "Recycling:" lines. | e.g. `Weekly`. |
| Service Guidelines → Service guidelines | The Service Guidelines list. | One rule per line. |
| Bulk Items → Bulk item text / accepted / not accepted | The Bulk Item Guidance box. | One item per line. |
| Recycling → accepted / not accepted / never accepted | The three "What belongs in recycling" boxes. | One item per line. Empty a list to hide that box. |
| Downloads → Download 1–6: title and file | The "Printable schedules & guides" cards. | PDF or image. Leave a title empty to hide that download. |
| Search (Google) → SEO title / description | How the town page appears in Google. | Optional. |

**Adding a new town:** click **Communities → Add Post**, type the town name as the title, fill in the tabs, and click **Publish**. Its page, its card on the Residential page and its link in the Residential menu are created automatically. The lists start out with the standard recycling and bulk-item lists, so adjust anything that's different. To put it on the coverage map, also add it under **Service Areas**.

**Order:** towns are listed by the **Order** number in the right-hand sidebar (lower first).

## Dumpster Sizes

**Where:** left menu → **Dumpster Sizes** → click a size. Each one is a card on the Home, Dumpster Rentals and Calculator pages.

| Field | What it changes | Tips |
|---|---|---|
| Title (top of the screen) | The size name on the card, e.g. "20 Yard Dumpster". | Keep the format "15 Yard Dumpster". The calculator and the card drawings match on that name. |
| Truck Loads | The green line above the size name. | e.g. `8-10 pickup truck loads`. |
| Dimensions | The "Dimensions:" line. | e.g. `22' L x 7.5' W x 4.5' H`. |
| Image | The drawing at the top of the card. | Leave empty to keep the current drawing. |
| Ideal For | The bullet list on the card. | One project type per line. |
| Included | The "Includes: …" line. | One item per line, e.g. `15 day rental`. |

The cards are shown in **Order** (Page Attributes box on the right): lower numbers come first.

## Commercial Containers

**Where:** left menu → **Commercial Containers** → click a container. These are the cards on the Commercial page.

| Field | What it changes | Tips |
|---|---|---|
| Title | The container name, e.g. "4 Yard". | |
| Description | The short text on the card. | |
| Dimensions | The "Dimensions:" line. | e.g. `87" L x 66" W x 48" H`. |
| Capacity | The capacity line. | e.g. `3-4 pickup truck loads`. |
| Dimension Graphic | The drawing on the card. | |
| Ideal For | The bullet list on the card. | One business type per line, e.g. `Restaurants`. |

## FAQs

**Where:** left menu → **FAQs**.

| Field | What it changes | Tips |
|---|---|---|
| Title (top of the screen) | The question. | Type the question here **and** in the Question box. |
| Question | The question as shown on the site. | |
| Answer | The answer that opens when someone clicks the question. | You can use bold, lists and links. |
| Category | Which group the question appears in: Dumpster Rentals, Residential or Commercial. | Dumpster Rentals questions also show on the Home page. |
| Sort Order | The order within its group. | Lower numbers come first. |

## Service Areas (the map)

**Where:** left menu → **Service Areas**. Each entry is a numbered pin on the service-area map and a line in the list beside it (Home, About and Commercial pages).

| Field | What it changes | Tips |
|---|---|---|
| Title | The town name in the list. | |
| County | The county shown with the town. | e.g. `Walworth`. |
| Service Types | Which services you offer there. | Tick all that apply. |
| Latitude / Longitude | Where the pin sits on the map. | Right-click the town in Google Maps and click the numbers to copy them. Latitude is the first number (about 42.6). Longitude is the second and is negative (about -88.6). |

Pins are numbered alphabetically by town. Leave **Order** at 0.

## Service Notices (alerts)

**Where:** left menu → **Service Notices** → **Add Post**. Use these for weather delays, holiday schedule changes and closures. The fields are split across tabs at the top of the box.

| Tab and field | What it changes | Tips |
|---|---|---|
| Notice Content → Notice Title | The alert's name on the Service Alerts page. | Short, e.g. "Weather delay". |
| Notice Content → Short Summary | The one or two sentences shown on the Service Alerts page. | |
| Notice Content → Full Details | Longer details, if needed. | Optional. |
| Notice Content → Internal Notes | Notes for your team. **Never shown on the site.** | |
| Status and Priority → Notice Enabled | On = the alert can show. Off = hidden without deleting it. | |
| Status and Priority → Notice Status | Active = showing now. Resolved or Expired = hidden. | Set to Resolved when the issue is over. |
| Status and Priority → Notice Priority | The label shown on the alert, e.g. Important. | |
| Timing → Display Start / Display End | When the alert starts and stops showing. | Optional. Leave Start empty to show it right away. |
| Timing → Show Until Manually Resolved | On = ignore the end date and keep showing until you set the status to Resolved. | |
| Display Locations → Display Locations | Tick **Site-wide announcement bar** to show the yellow banner at the top of every page. | Use the banner for urgent, same-day news only. |
| Announcement Bar → Announcement Text | The one-line message in the yellow banner. | Leave empty to use the Notice Title. |

**Affected Communities** and **Popup Settings** are for future use. Leave them as they are.

## Things to leave alone

- **The AI-image disclosure.** The Copyright Text in Site Settings, the Photo Credits page and the Terms page say that some pictures on the site are AI-generated illustrations. Keep those sentences as long as the AI pictures are on the site. If you replace them all with your own photos, ask your developer to update the wording.
- **Integrations / PayEngine settings.** The payment keys and the "Live/Test" switch are set up with your developer and PayEngine. Changing them can stop online payments working.
- **reCAPTCHA keys and the Google Tag Manager ID.** These are set up once and don't need changing.
- **Field Groups** (under ACF in the left menu) and **Appearance → Themes / Plugins.** These control how the site is built, not what it says.

## Changes that need your developer

Every picture and every piece of wording on the site can be changed in the admin screens above. These few things are deliberately left to your developer:

- **The main menu at the top of the site.** Town links update themselves; other menu changes need your developer. (WordPress has a Menus screen under Appearance, but building a menu there replaces the whole top menu, so ask first.)
- **The card and bank boxes on the payment form.** PayEngine has to control those for security. The heading and side box above them are editable.
- **The service-area map drawing.** The site draws it automatically from the pins under Service Areas, so it's always up to date.
- **The look of the site:** colors, fonts and layout.

**Developer contact:** ajlehr123@gmail.com
