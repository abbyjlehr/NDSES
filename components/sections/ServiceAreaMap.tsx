import Image from "next/image";
import type { ServiceArea } from "@/types/cms";
import { DETAIL_MAP, projectMapPin } from "@/lib/serviceAreaMap";

const typeLabels: Record<string, string> = { residential: "Residential", commercial: "Commercial", rollOff: "Roll-off" };

const isCounty = (area: ServiceArea) => /county$/i.test(area.name);

/**
 * Real map of the service area (county outlines from US Census data, see
 * scripts/build-service-area-map.cjs). Counties are labeled on the map;
 * towns get numbered pins that match the numbered list, so every name is
 * always readable no matter how close the towns are.
 */
export function ServiceAreaMap({ areas }: { areas: ServiceArea[] }) {
  const towns = areas.filter((area) => !isCounty(area));
  const counties = areas.filter(isCounty);

  return (
    <div className="service-map-wrap">
      <div className="service-maps">
        <figure className="service-map">
          <Image
            alt="Map of southern Wisconsin showing the counties NDS serves: Walworth, Rock, Jefferson, Waukesha, and Kenosha"
            height={547}
            sizes="(max-width: 900px) 100vw, 640px"
            src="/nds-assets/service-area-map.svg"
            unoptimized
            width={800}
          />
        </figure>
        {towns.length ? (
          <figure className="service-map service-map-detail">
            {/* Pins are positioned against this box, which is exactly the map image. */}
            <div className="map-canvas">
            <Image
              alt="Map of southern Walworth County with the residential communities NDS serves"
              height={DETAIL_MAP.height}
              sizes="(max-width: 900px) 100vw, 640px"
              src="/nds-assets/service-area-detail.svg"
              unoptimized
              width={DETAIL_MAP.width}
            />
            {towns.map((area, index) => {
              // A town without coordinates, or outside the detail map, is listed without a pin.
              const position = projectMapPin(area);
              if (!position) return null;
              return (
                <span aria-hidden className="map-pin" key={area.name} style={{ left: `${position.x}%`, top: `${position.y}%` }}>
                  <b>{index + 1}</b>
                  <span className="map-pin-label">{area.name}</span>
                </span>
              );
            })}
            </div>
            <figcaption>Walworth County residential communities</figcaption>
          </figure>
        ) : null}
      </div>

      <div className="card map-list">
        <p className="eyebrow">Service Area</p>
        <h2>Southern Wisconsin coverage</h2>
        <p>Residential service is concentrated in listed communities. Commercial and temporary roll-off service is available in select areas of nearby counties.</p>

        <h3 className="map-list-heading">Residential communities</h3>
        <ol className="area-list">
          {towns.map((area, index) => (
            <li key={area.name}>
              <span className="map-pin static" aria-hidden><b>{index + 1}</b></span>
              <span>
                <strong>{area.name}</strong>
                <small>{area.serviceTypes.map((type) => typeLabels[type] ?? type).join(" · ")}</small>
              </span>
            </li>
          ))}
        </ol>

        {counties.length ? (
          <>
            <h3 className="map-list-heading">Counties</h3>
            <ul className="county-list">
              {counties.map((area) => (
                <li key={area.name}>
                  <strong>{area.name}</strong>
                  <small>{area.serviceTypes.map((type) => typeLabels[type] ?? type).join(" · ")}</small>
                </li>
              ))}
            </ul>
          </>
        ) : null}
      </div>
    </div>
  );
}
