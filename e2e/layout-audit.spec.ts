import { expect, test, type Page } from "@playwright/test";
import { mkdirSync, writeFileSync } from "node:fs";
import { routes, widths } from "./routes";

type Finding = { route: string; width: number; kind: string; detail: string };

// Measures the rendered page: horizontal overflow, text boxes that overlap
// other text, and text clipped by its container.
async function measure(page: Page) {
  return page.evaluate(() => {
    const out: { kind: string; detail: string }[] = [];
    const doc = document.documentElement;
    if (doc.scrollWidth > doc.clientWidth + 1) {
      const wide = [...document.querySelectorAll<HTMLElement>("body *")]
        .filter((el) => el.getBoundingClientRect().right > doc.clientWidth + 1 && getComputedStyle(el).position !== "fixed")
        .slice(0, 3)
        .map((el) => `${el.tagName.toLowerCase()}.${[...el.classList].join(".")}`);
      out.push({ kind: "horizontal-overflow", detail: `${doc.scrollWidth}px > ${doc.clientWidth}px (${wide.join(", ")})` });
    }

    const label = (el: Element) => `${el.tagName.toLowerCase()}${el.className && typeof el.className === "string" ? "." + el.className.trim().split(/\s+/).join(".") : ""} "${(el.textContent ?? "").trim().slice(0, 40)}"`;
    const visible = (el: Element) => {
      // Content of a closed <details> (other than its summary) isn't shown,
      // even though Chrome still reports layout boxes for it.
      for (let d = el.closest("details"); d; d = d.parentElement?.closest("details") ?? null) {
        const inOwnSummary = [...d.children].some((c) => c.tagName === "SUMMARY" && c.contains(el));
        if (!d.open && !inOwnSummary) return false;
      }
      if (el.closest("[inert]")) return false;
      const cs = getComputedStyle(el);
      return cs.visibility !== "hidden" && cs.display !== "none" && Number(cs.opacity) > 0.05;
    };

    // One box per text node, measured with a Range so it covers the glyphs.
    const boxes: { el: Element; r: DOMRect }[] = [];
    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
    while (walker.nextNode()) {
      const node = walker.currentNode as Text;
      const parent = node.parentElement;
      if (!parent || !node.textContent?.trim() || !visible(parent) || parent.closest("script,style,noscript,[hidden],[aria-hidden='true'],.sr-only")) continue;
      const range = document.createRange();
      range.selectNodeContents(node);
      for (const r of range.getClientRects()) if (r.width > 2 && r.height > 2) boxes.push({ el: parent, r });
    }
    const seen = new Set<string>();
    for (let i = 0; i < boxes.length; i++) {
      for (let j = i + 1; j < boxes.length; j++) {
        const a = boxes[i], b = boxes[j];
        if (a.el === b.el) continue;
        const w = Math.min(a.r.right, b.r.right) - Math.max(a.r.left, b.r.left);
        const h = Math.min(a.r.bottom, b.r.bottom) - Math.max(a.r.top, b.r.top);
        if (w > 3 && h > 3) {
          const key = `${label(a.el)}|${label(b.el)}`;
          if (!seen.has(key)) {
            seen.add(key);
            out.push({ kind: "text-overlap", detail: `${label(a.el)} ⟷ ${label(b.el)}` });
          }
        }
      }
    }

    for (const el of document.querySelectorAll<HTMLElement>("h1,h2,h3,h4,p,a,button,li,label,dt,dd,span,strong,summary")) {
      if (!visible(el) || !el.textContent?.trim() || el.closest(".sr-only")) continue;
      const cs = getComputedStyle(el);
      const clips = cs.overflow === "hidden" || cs.overflowX === "hidden" || cs.textOverflow === "ellipsis";
      if (clips && (el.scrollWidth > el.clientWidth + 2 || el.scrollHeight > el.clientHeight + 2)) {
        out.push({ kind: "clipped-text", detail: label(el) });
      }
    }

    // Cards: content must not touch the card's top edge. Only an image frame
    // that bleeds to the edge (dumpster/container graphics) may sit flush.
    for (const card of document.querySelectorAll<HTMLElement>("main .feature-card, main .process-step, main .quote-panel, main .site-form, main .map-list, main .autopay-card")) {
      if (!visible(card)) continue;
      const first = [...card.children].find((c) => visible(c) && c.getBoundingClientRect().height > 0);
      if (!first || first.matches("img, .container-image")) continue;
      const gap = first.getBoundingClientRect().top - card.getBoundingClientRect().top;
      if (gap < 16) out.push({ kind: "card-padding", detail: `${label(card)}: content ${Math.round(gap)}px from top` });
    }
    return out.slice(0, 40);
  });
}

const findings: Finding[] = [];
const shotWidths = new Set([390, 1440]);
const phase = process.env.AUDIT_PHASE ?? "current";

test.describe.configure({ mode: "parallel" });

for (const route of routes) {
  test(`layout ${route}`, async ({ page }) => {
    // Nine page loads plus measurements per route.
    test.setTimeout(240_000);
    const errors: string[] = [];
    page.on("console", (msg) => { if (msg.type() === "error") errors.push(`${msg.text().slice(0, 120)} @ ${msg.location().url}`); });
    page.on("pageerror", (err) => errors.push(err.message.slice(0, 160)));
    // Prefetches cancelled by the next navigation (net::ERR_ABORTED) are expected.
    page.on("requestfailed", (req) => {
      if (req.failure()?.errorText.includes("ERR_ABORTED")) return;
      errors.push(`request failed: ${req.url()} ${req.failure()?.errorText ?? ""}`);
    });
    page.on("response", (res) => { if (res.status() >= 400 && !res.url().includes("/_next/webpack-hmr")) errors.push(`HTTP ${res.status()}: ${res.url()}`); });

    for (const width of widths) {
      await page.setViewportSize({ width, height: 900 });
      // Reduced motion before load, so scroll-reveal never hides content.
      await page.emulateMedia({ reducedMotion: "reduce" });
      const started = Date.now();
      // Not "networkidle": Next.js link prefetches can stay open in Chrome
      // across navigations. Wait for what affects layout instead.
      await page.goto(route, { waitUntil: "load" });
      await page.evaluate(async () => {
        await document.fonts.ready;
        for (let y = 0; y < document.body.scrollHeight; y += 500) window.scrollTo(0, y);
        window.scrollTo(0, 0);
        // Eager images must finish; lazy ones load when scrolled into view, and
        // reserve their box via width/height, so they don't change layout.
        const pending = [...document.images].filter((img) => !img.complete && img.loading !== "lazy");
        await Promise.all(pending.map((img) => new Promise((r) => { img.onload = img.onerror = r; setTimeout(r, 5000); })));
      });
      if (process.env.AUDIT_DEBUG) console.log(route, width, "loaded in", Date.now() - started, "ms");
      await page.waitForTimeout(150);
      const measured = Date.now();
      for (const f of await measure(page)) findings.push({ route, width, ...f });
      if (process.env.AUDIT_DEBUG) console.log(route, width, "measured in", Date.now() - measured, "ms");
      if (shotWidths.has(width)) {
        const dir = `test-results/screens/${phase}`;
        mkdirSync(dir, { recursive: true });
        await page.screenshot({ path: `${dir}/${route === "/" ? "home" : route.replace(/^\/|\/$/g, "").replace(/\//g, "_")}-${width}.png`, fullPage: true });
      }
    }
    for (const e of [...new Set(errors)]) findings.push({ route, width: 0, kind: "console-or-network", detail: e });
    mkdirSync("test-results/audit", { recursive: true });
    writeFileSync(`test-results/audit/${phase}-${route === "/" ? "home" : route.replace(/^\/|\/$/g, "").replace(/\//g, "_")}.json`, JSON.stringify(findings.filter((f) => f.route === route), null, 2));
    expect.soft(findings.filter((f) => f.route === route), `layout findings on ${route}`).toEqual([]);
  });
}
