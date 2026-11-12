import { describe, expect, it } from "vitest";
import { serviceAreas } from "@/lib/cms/mock-data";
import { DETAIL_MAP, projectMapPin } from "@/lib/serviceAreaMap";

// Where the five seeded towns sat before pins were projected at render time:
// the positions scripts/build-service-area-map.cjs printed for them.
const previous: Record<string, { x: number; y: number }> = {
  "City of Delavan": { x: 31.1, y: 28.16 },
  "Town of Delavan": { x: 44.53, y: 47.15 },
  "Town of Walworth": { x: 39.47, y: 71.14 },
  "Town of Darien": { x: 19.05, y: 41.68 },
  "Town of Sharon": { x: 15.12, y: 83.35 }
};

describe("detail map pin projection", () => {
  it("reads the bounds the build script publishes", () => {
    expect(DETAIL_MAP.width).toBe(800);
    expect(DETAIL_MAP.height).toBe(486);
    expect(DETAIL_MAP.maxLon).toBeGreaterThan(DETAIL_MAP.minLon);
    expect(DETAIL_MAP.maxLat).toBeGreaterThan(DETAIL_MAP.minLat);
  });

  it("places the seeded towns where they were before, within one percent", () => {
    for (const [name, expected] of Object.entries(previous)) {
      const area = serviceAreas.find((item) => item.name === name);
      expect(area, name).toBeDefined();
      const pin = projectMapPin(area!);
      expect(pin, name).not.toBeNull();
      expect(Math.abs(pin!.x - expected.x), `${name} x`).toBeLessThanOrEqual(1);
      expect(Math.abs(pin!.y - expected.y), `${name} y`).toBeLessThanOrEqual(1);
    }
  });

  it("gives counties and towns without coordinates no pin", () => {
    for (const area of serviceAreas.filter((item) => /county$/i.test(item.name))) {
      expect(projectMapPin(area), area.name).toBeNull();
    }
    expect(projectMapPin({})).toBeNull();
    expect(projectMapPin({ latitude: 42.6 })).toBeNull();
    expect(projectMapPin({ latitude: Number.NaN, longitude: -88.6 })).toBeNull();
  });

  it("gives a point outside the detail map no pin", () => {
    // Milwaukee: north and east of the Walworth County frame.
    expect(projectMapPin({ latitude: 43.0389, longitude: -87.9065 })).toBeNull();
    // Just outside each edge.
    expect(projectMapPin({ latitude: DETAIL_MAP.maxLat + 0.001, longitude: -88.6 })).toBeNull();
    expect(projectMapPin({ latitude: 42.6, longitude: DETAIL_MAP.minLon - 0.001 })).toBeNull();
  });

  it("pins a new town inside the frame at a sensible spot", () => {
    // Village of Fontana-on-Geneva Lake, in southern Walworth County.
    const pin = projectMapPin({ latitude: 42.5494, longitude: -88.5706 });
    expect(pin).not.toBeNull();
    expect(pin!.x).toBeGreaterThan(0);
    expect(pin!.x).toBeLessThan(100);
    expect(pin!.y).toBeGreaterThan(0);
    expect(pin!.y).toBeLessThan(100);
    // East of the Town of Walworth and south of the Town of Delavan.
    const walworth = projectMapPin(serviceAreas.find((item) => item.name === "Town of Walworth")!)!;
    const delavan = projectMapPin(serviceAreas.find((item) => item.name === "Town of Delavan")!)!;
    expect(pin!.x).toBeGreaterThan(walworth.x);
    expect(pin!.y).toBeGreaterThan(delavan.y);
  });
});
