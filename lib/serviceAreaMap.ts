// Pins on the Walworth County detail map, public/nds-assets/service-area-detail.svg.
// The bounds file is written by scripts/build-service-area-map.cjs next to
// the SVG and holds the map's pixel size and the longitude/latitude range it
// was drawn with. Each Service Area's latitude/longitude is projected onto
// it with the same linear projection the script uses, so the theme and this
// app place a pin at the same spot and a town added in WordPress just works.
import bounds from "@/public/nds-assets/service-area-detail-bounds.json";
import type { ServiceArea } from "@/types/cms";

export type MapBounds = {
  width: number;
  height: number;
  minLon: number;
  maxLon: number;
  minLat: number;
  maxLat: number;
};

export const DETAIL_MAP: MapBounds = bounds;

/** Pin position as percent of the map's width and height, or null for no pin. */
export function projectMapPin(
  area: Pick<ServiceArea, "latitude" | "longitude">,
  map: MapBounds = DETAIL_MAP
): { x: number; y: number } | null {
  const { latitude: lat, longitude: lon } = area;
  if (typeof lat !== "number" || typeof lon !== "number" || !Number.isFinite(lat) || !Number.isFinite(lon)) return null;
  if (lon < map.minLon || lon > map.maxLon || lat < map.minLat || lat > map.maxLat) return null;
  const x = ((lon - map.minLon) / (map.maxLon - map.minLon)) * 100;
  const y = ((map.maxLat - lat) / (map.maxLat - map.minLat)) * 100;
  return { x: Math.round(x * 100) / 100, y: Math.round(y * 100) / 100 };
}
