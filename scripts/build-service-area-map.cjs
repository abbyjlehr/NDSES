// Builds the service-area map: projects county outlines from us-atlas
// (US Census cartographic boundaries) and computes marker positions.
const path = require("path");
const fs = require("fs");
// Run from a folder with us-atlas@3.0.1 and topojson-client@3.1.0 installed:
//   NODE_PATH=<that folder>/node_modules node scripts/build-service-area-map.cjs
const deps = process.env.NODE_PATH || path.join(__dirname, "deps/node_modules");
const topo = require(path.join(deps, "us-atlas/counties-10m.json"));
const { feature, mesh } = require(path.join(deps, "topojson-client"));

const SERVICE = { "55127": "Walworth", "55105": "Rock", "55055": "Jefferson", "55133": "Waukesha", "55059": "Kenosha" };
const CONTEXT = ["55101", "55079", "55025", "55045", "55027", "55131", "17007", "17111", "17097", "17201", "17177", "55089", "55117", "55065"];
const counties = feature(topo, topo.objects.counties).features;
const pick = (ids) => counties.filter((f) => ids.includes(f.id));
const service = pick(Object.keys(SERVICE));
const context = pick(CONTEXT);

// Frame: the service counties' bounds plus a margin.
const coords = [];
const walk = (c) => (typeof c[0] === "number" ? coords.push(c) : c.forEach(walk));
service.forEach((f) => walk(f.geometry.coordinates));
const lons = coords.map((c) => c[0]), lats = coords.map((c) => c[1]);
const pad = 0.08;
const minLon = Math.min(...lons) - pad, maxLon = Math.max(...lons) + pad;
const minLat = Math.min(...lats) - pad, maxLat = Math.max(...lats) + pad;
const k = Math.cos(((minLat + maxLat) / 2) * Math.PI / 180);
const W = 800;
const H = Math.round(W * (maxLat - minLat) / ((maxLon - minLon) * k));
const px = (lon) => ((lon - minLon) * k) / ((maxLon - minLon) * k) * W;
const py = (lat) => ((maxLat - lat) / (maxLat - minLat)) * H;
const pathOf = (geom) => {
  const rings = geom.type === "Polygon" ? [geom.coordinates] : geom.coordinates;
  return rings.map((poly) => poly.map((ring) => "M" + ring.map(([lo, la]) => `${px(lo).toFixed(1)},${py(la).toFixed(1)}`).join("L") + "Z").join("")).join("");
};
const lineOf = (geom) => geom.coordinates.map((line) => "M" + line.map(([lo, la]) => `${px(lo).toFixed(1)},${py(la).toFixed(1)}`).join("L")).join("");

// Visual centroid (mean of outline points) for county labels and markers.
const centroid = (f) => {
  const pts = []; const w = (c) => (typeof c[0] === "number" ? pts.push(c) : c.forEach(w)); w(f.geometry.coordinates);
  return [pts.reduce((s, p) => s + p[0], 0) / pts.length, pts.reduce((s, p) => s + p[1], 0) / pts.length];
};

const stateLine = mesh(topo, topo.objects.states, (a, b) => a !== b && [a.id, b.id].sort().join() === "17,55");

const places = {
  "City of Delavan": [-88.6437, 42.6331],
  "Town of Delavan": [-88.572, 42.588],
  "Town of Walworth": [-88.599, 42.531],
  "Town of Darien": [-88.708, 42.601],
  "Town of Sharon": [-88.729, 42.502]
};
const positions = {};
for (const [name, [lo, la]] of Object.entries(places)) positions[name] = { x: +(px(lo) / W * 100).toFixed(2), y: +(py(la) / H * 100).toFixed(2) };
const countyLabels = service.map((f) => {
  const [lo, la] = centroid(f);
  const name = SERVICE[f.id] + " County";
  positions[name] = { x: +(px(lo) / W * 100).toFixed(2), y: +(py(la) / H * 100).toFixed(2) };
  return { name, x: px(lo), y: py(la), id: f.id };
});

// Residential communities cluster in southwestern Walworth County; a soft
// outline marks that area on the map, and numbered pins (HTML overlay)
// sit on each community.
const placePts = Object.values(places).map(([lo, la]) => [px(lo), py(la)]);
const cx = (Math.min(...placePts.map((p) => p[0])) + Math.max(...placePts.map((p) => p[0]))) / 2;
const cy = (Math.min(...placePts.map((p) => p[1])) + Math.max(...placePts.map((p) => p[1]))) / 2;
const rx = (Math.max(...placePts.map((p) => p[0])) - Math.min(...placePts.map((p) => p[0]))) / 2 + 30;
const ry = (Math.max(...placePts.map((p) => p[1])) - Math.min(...placePts.map((p) => p[1]))) / 2 + 30;
const label = (txt, x, y, size, extra = "") => `<text x="${x.toFixed(1)}" y="${y.toFixed(1)}" font-size="${size}" ${extra}>${txt}</text>`;
const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}" role="img" aria-label="Map of the NDS service area: Walworth, Rock, Jefferson, Waukesha, and Kenosha counties in southern Wisconsin, with the residential service area in southwestern Walworth County">
<rect width="${W}" height="${H}" fill="#dce8ee"/>
<g fill="#f2f4f0" stroke="#dde3da" stroke-width="1">${context.map((f) => `<path d="${pathOf(f.geometry)}"/>`).join("")}</g>
<g stroke="#ffffff" stroke-width="2.5" stroke-linejoin="round">${service.map((f) => `<path fill="${f.id === "55127" ? "#c9e2cf" : "#e2eee1"}" d="${pathOf(f.geometry)}"/>`).join("")}</g>
<path d="${lineOf(stateLine)}" fill="none" stroke="#a3afa6" stroke-width="1.2" stroke-dasharray="5 5"/>
<ellipse cx="${cx.toFixed(1)}" cy="${cy.toFixed(1)}" rx="${rx.toFixed(1)}" ry="${ry.toFixed(1)}" fill="#1d6b3e" fill-opacity="0.08" stroke="#1d6b3e" stroke-opacity="0.55" stroke-width="1.5" stroke-dasharray="4 4"/>
<g font-family="Public Sans, Arial, sans-serif" font-weight="600" fill="#24382c" text-anchor="middle" paint-order="stroke" stroke="#ffffff" stroke-width="4" stroke-linejoin="round">${countyLabels.map((c) => label(c.name, c.x, c.id === "55127" ? c.y - 58 : c.y + 5, 15)).join("")}</g>
<g font-family="Public Sans, Arial, sans-serif" font-weight="600" fill="#6b7a70" letter-spacing="0.04em">
${label("Wisconsin", W - 16, py(42.4925) - 8, 12, 'text-anchor="end"')}
${label("Illinois", W - 16, py(42.4925) + 18, 12, 'text-anchor="end"')}
<text transform="translate(${W - 14} ${(H * 0.3).toFixed(1)}) rotate(90)" fill="#4f7486" text-anchor="middle" font-size="12" font-style="italic">Lake Michigan</text>
</g>
</svg>`;

// Walworth County detail: the residential communities sit within a few
// miles of each other, so their pins go on a zoomed map of the county.
const walworth = service.find((f) => f.id === "55127");
const wc = []; const ww = (c) => (typeof c[0] === "number" ? wc.push(c) : c.forEach(ww)); ww(walworth.geometry.coordinates);
const dPad = 0.03;
const dMinLon = Math.min(...wc.map((c) => c[0])) - dPad, dMaxLon = Math.max(...wc.map((c) => c[0])) + dPad;
// Crop to the southern part of the county, where the communities are.
const dMinLat = Math.min(...wc.map((c) => c[1])) - dPad, dMaxLat = 42.70;
const DW = 800;

const DH = Math.round(DW * (dMaxLat - dMinLat) / ((dMaxLon - dMinLon) * k));
const dx = (lon) => ((lon - dMinLon) / (dMaxLon - dMinLon)) * DW;
const dy = (lat) => ((dMaxLat - lat) / (dMaxLat - dMinLat)) * DH;
const miles5 = (5 / (69.17 * k)) / (dMaxLon - dMinLon) * DW;
const dPath = (geom) => {
  const rings = geom.type === "Polygon" ? [geom.coordinates] : geom.coordinates;
  return rings.map((poly) => poly.map((ring) => "M" + ring.map(([lo, la]) => `${dx(lo).toFixed(1)},${dy(la).toFixed(1)}`).join("L") + "Z").join("")).join("");
};
const neighbors = [...service, ...context].filter((f) => f.id !== "55127");
const detailSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${DW} ${DH}" role="img" aria-label="Map of Walworth County, Wisconsin, with the residential communities NDS serves">
<rect width="${DW}" height="${DH}" fill="#e7ece4"/>
<g fill="#e7ece4" stroke="#c9d3c6" stroke-width="1.5">${neighbors.map((f) => `<path d="${dPath(f.geometry)}"/>`).join("")}</g>
<path d="${dPath(walworth.geometry)}" fill="#cfe1cb" stroke="#ffffff" stroke-width="3" stroke-linejoin="round"/>
<path d="${lineOf(stateLine).replace(/[\d.]+,[\d.]+/g, (pair) => { const [x, y] = pair.split(",").map(Number); const lon = minLon + (x / W) * (maxLon - minLon); const lat = maxLat - (y / H) * (maxLat - minLat); return `${dx(lon).toFixed(1)},${dy(lat).toFixed(1)}`; })}" fill="none" stroke="#8a9a8d" stroke-width="2" stroke-dasharray="8 6"/>
<g font-family="Archivo, Arial Narrow, Arial, sans-serif" font-weight="700" font-size="22" fill="#0f3d25" fill-opacity="0.5" letter-spacing="0.08em"><text x="${(dx(-88.765)).toFixed(1)}" y="${(dy(42.69) + 34).toFixed(1)}">WALWORTH COUNTY</text></g>
<g font-family="Public Sans, Arial, sans-serif" font-size="14" font-weight="700" fill="#56655b" letter-spacing="0.12em"><text x="${DW - 24}" y="${(dy(42.4925) + 26).toFixed(1)}" text-anchor="end">ILLINOIS</text></g>
<g transform="translate(${(DW - miles5 - 110).toFixed(1)} 34)" fill="#33433a" font-family="Public Sans, Arial, sans-serif" font-size="14" font-weight="700">
<rect x="0" y="0" width="${miles5.toFixed(1)}" height="6" fill="#33433a"/><rect x="${(miles5 / 2).toFixed(1)}" y="0" width="${(miles5 / 2).toFixed(1)}" height="6" fill="#ffffff" stroke="#33433a"/>
<text x="0" y="24">0</text><text x="${miles5.toFixed(1)}" y="24" text-anchor="end">5 mi</text>
<g transform="translate(${(miles5 + 40).toFixed(1)} -8)"><path d="M0 22 L9 0 L18 22 L9 16 Z" fill="#33433a"/><text x="9" y="40" text-anchor="middle">N</text></g>
</g>
</svg>`;
// The detail map's frame. The Next.js app (lib/serviceAreaMap.ts) reads this
// file and projects each Service Area's latitude/longitude onto the detail
// SVG with the same linear projection as dx()/dy() above, so a town added in
// WordPress lands at the right spot.
// Regenerating the map regenerates the bounds alongside it.
const bounds = {
  width: DW,
  height: DH,
  minLon: +dMinLon.toFixed(6),
  maxLon: +dMaxLon.toFixed(6),
  minLat: +dMinLat.toFixed(6),
  maxLat: +dMaxLat.toFixed(6)
};
const root = path.join(__dirname, "..");
for (const dir of ["wordpress/ndses-theme/assets/images", "public/nds-assets"]) {
  fs.writeFileSync(path.join(root, dir, "service-area-detail-bounds.json"), JSON.stringify(bounds, null, 2) + "\n");
}
console.log("detail bounds", JSON.stringify(bounds));

// Reference only: where the seeded towns land on the detail map, as percent
// of width/height. The app computes these at render time now.
const detail = {};
for (const [name, [lo, la]] of Object.entries(places)) detail[name] = { x: +(dx(lo) / DW * 100).toFixed(2), y: +(dy(la) / DH * 100).toFixed(2) };
fs.writeFileSync(path.join(__dirname, "service-area-detail.svg"), detailSvg);
console.log("detail", DW, "x", DH, JSON.stringify(detail));

// Frame of the main map above (service-area-map.svg), which the WordPress
// theme draws its pins on (inc/template-tags.php). Same shape as the
// detail bounds; px()/py() are linear in longitude/latitude.
const mapBounds = {
  width: W,
  height: H,
  minLon: +minLon.toFixed(6),
  maxLon: +maxLon.toFixed(6),
  minLat: +minLat.toFixed(6),
  maxLat: +maxLat.toFixed(6)
};
for (const dir of ["wordpress/ndses-theme/assets/images", "public/nds-assets"]) {
  fs.writeFileSync(path.join(root, dir, "service-area-map-bounds.json"), JSON.stringify(mapBounds, null, 2) + "\n");
}
console.log("map bounds", JSON.stringify(mapBounds));

// Copy the SVGs to public/nds-assets/ and wordpress/ndses-theme/assets/images/.
fs.writeFileSync(path.join(__dirname, "service-area-map.svg"), svg);
fs.writeFileSync(path.join(__dirname, "positions.json"), JSON.stringify({ width: W, height: H, positions }, null, 2));
console.log("size", W, "x", H, "svg bytes", svg.length);
console.log(JSON.stringify(positions));
