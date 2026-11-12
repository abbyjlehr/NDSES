import { unstable_cache } from "next/cache";
import type {
  CMSPage,
  Community,
  CTA,
  DumpsterSize,
  FAQ,
  GlobalSettings,
  Hero,
  PageSection,
  ScheduleItem,
  ServiceArea,
  ServiceNotice
} from "@/types/cms";

type WPPost = {
  id: number;
  slug: string;
  title?: { rendered?: string };
  content?: { rendered?: string };
  acf?: Record<string, unknown>;
};

function wordpressRestUrl(path: string) {
  const base = process.env.WORDPRESS_API_URL?.replace(/\/$/, "");
  if (!base) return null;
  return `${base}/${path.replace(/^\//, "")}`;
}

async function rest<T>(path: string): Promise<T | null> {
  const endpoint = wordpressRestUrl(path);
  if (!endpoint) return null;

  try {
    const response = await fetch(endpoint, {
      headers: { accept: "application/json" },
      next: { revalidate: 300, tags: ["cms"] }
    });

    if (response.status === 401 || response.status === 403) {
      return null;
    }

    if (!response.ok) {
      console.error("WordPress REST request failed", response.status, path);
      return null;
    }

    return (await response.json()) as T;
  } catch (error) {
    console.error("WordPress REST request failed", error);
    return null;
  }
}

async function graphQL<T>(query: string, variables?: Record<string, unknown>): Promise<T | null> {
  const endpoint = process.env.WORDPRESS_GRAPHQL_URL;
  if (!endpoint) return null;

  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "content-type": "application/json" },
      body: JSON.stringify({ query, variables }),
      next: { revalidate: 300, tags: ["cms"] }
    });

    if (response.status === 401 || response.status === 403) {
      return null;
    }

    if (!response.ok) {
      console.error("WordPress GraphQL request failed", response.status);
      return null;
    }

    const json = await response.json();
    if (json.errors) {
      console.error("WordPress GraphQL returned errors", json.errors);
      return null;
    }
    return json.data as T;
  } catch (error) {
    console.error("WordPress GraphQL request failed", error);
    return null;
  }
}

function asRecord(value: unknown): Record<string, unknown> {
  return value && typeof value === "object" && !Array.isArray(value) ? (value as Record<string, unknown>) : {};
}

function asString(value: unknown, fallback = "") {
  if (typeof value !== "string") return fallback;
  const trimmed = value.trim();
  return trimmed || fallback;
}

function asStringArray(value: unknown, fallback: string[] = []) {
  if (!Array.isArray(value)) return fallback;
  const items = value.map((item) => asString(item)).filter(Boolean);
  return items.length ? items : fallback;
}

function stripHtml(value: string) {
  return value.replace(/<[^>]*>/g, "").replace(/\s+/g, " ").trim();
}

function titleOf(post: WPPost, fallback = "") {
  return stripHtml(asString(post.title?.rendered, fallback));
}

function mapImageUrl(value: unknown) {
  return asString(asRecord(value).url);
}

function mapCtas(value: unknown, fallback: CTA[]): CTA[] {
  if (!Array.isArray(value)) return fallback;
  const actions = value
    .map((item) => {
      const cta = asRecord(item);
      const label = asString(cta.label);
      const href = asString(cta.url) || asString(cta.href);
      return label && href ? { label, href } : null;
    })
    .filter(Boolean) as CTA[];
  return actions.length ? actions : fallback;
}

function mapHero(value: unknown, fallback: Hero): Hero {
  const hero = asRecord(value);
  return {
    eyebrow: asString(hero.eyebrow, fallback.eyebrow),
    heading: asString(hero.heading, fallback.heading),
    description: asString(hero.description, fallback.description),
    actions: mapCtas(hero.ctas, fallback.actions)
  };
}

function mapSections(value: unknown, fallback: PageSection[]): PageSection[] {
  if (!Array.isArray(value) || value.length === 0) return fallback;

  const sections = value
    .map((item, index) => {
      const section = asRecord(item);
      const layout = asString(section.acf_fc_layout);
      const id = `${layout || "section"}-${index}`;

      if (layout === "text_image") {
        return {
          id,
          type: "textImage" as const,
          eyebrow: asString(section.eyebrow, "NDS"),
          heading: asString(section.heading, "NDS Environmental Solutions"),
          body: stripHtml(asString(section.body)),
          imageAlt: asString(section.image_alt)
        };
      }

      if (layout === "process_steps") {
        const steps = Array.isArray(section.steps)
          ? section.steps
              .map((step) => {
                const record = asRecord(step);
                return { title: asString(record.title), body: asString(record.body) };
              })
              .filter((step) => step.title && step.body)
          : [];

        return steps.length
          ? {
              id,
              type: "items" as const,
              eyebrow: "Process",
              heading: asString(section.heading, "How it works"),
              items: steps
            }
          : null;
      }

      return null;
    })
    .filter(Boolean) as PageSection[];

  return sections.length ? sections : fallback;
}

export const getWordPressGlobals = unstable_cache(async (fallback?: GlobalSettings): Promise<GlobalSettings | null> => {
  // ACF's built-in REST exposure for options pages (acf/v3/options/...) requires
  // ACF PRO, which this site doesn't have. ndses-cms registers a small custom
  // route (ndses/v1/settings) that exposes the same fields without that dependency.
  const acf = asRecord(await rest<Record<string, unknown>>("ndses/v1/settings"));
  if (!Object.keys(acf).length) return null;

  const repeaterSocialLinks = Array.isArray(acf.social_links)
    ? acf.social_links
        .map((row) => ({ label: asString(asRecord(row).label), href: asString(asRecord(row).href) }))
        .filter((link) => link.label && link.href)
    : [];
  const socialLinks = repeaterSocialLinks.length
    ? repeaterSocialLinks
    : [
        { label: "Facebook", href: asString(acf.facebook) },
        { label: "Instagram", href: asString(acf.instagram) }
      ].filter((link) => link.href);

  return {
    companyName: asString(acf.company_name, fallback?.companyName),
    phone: asString(acf.phone, fallback?.phone),
    email: asString(acf.email, fallback?.email),
    address: asString(acf.address, fallback?.address),
    mailingAddress: asString(acf.mailing_address, fallback?.mailingAddress),
    businessHours: typeof acf.hours === "string" && acf.hours.trim()
      ? acf.hours.split(/\r?\n/).map((line) => line.trim()).filter(Boolean).join("; ")
      : Array.isArray(acf.hours) ? acf.hours.map((line) => asString(asRecord(line).line)).filter(Boolean).join("; ") : fallback?.businessHours ?? "",
    serviceArea: asString(acf.service_area, fallback?.serviceArea),
    footerDescription: asString(acf.footer_description, fallback?.footerDescription),
    copyrightText: asString(acf.copyright_text, fallback?.copyrightText),
    navigation: fallback?.navigation ?? [],
    socialLinks: socialLinks.length ? socialLinks : fallback?.socialLinks
  };
}, ["wordpress-globals"], { tags: ["cms", "globals"], revalidate: 300 });

export async function getWordPressPage(slug: string, fallback?: CMSPage): Promise<CMSPage | null> {
  const posts = await rest<WPPost[]>(`wp/v2/pages?slug=${encodeURIComponent(slug)}&_fields=id,slug,title,content,acf`);
  const post = posts?.[0];
  if (!post?.acf) return null;

  const acf = asRecord(post.acf);
  return {
    slug: post.slug || fallback?.slug || slug,
    hero: mapHero(acf.hero, fallback?.hero ?? { eyebrow: "", heading: titleOf(post, slug), description: "", actions: [] }),
    sections: mapSections(acf.sections, fallback?.sections ?? []),
    seo: {
      title: asString(acf.seo_title, fallback?.seo.title ?? titleOf(post, slug)),
      description: asString(acf.seo_description, fallback?.seo.description ?? stripHtml(asString(post.content?.rendered)))
    }
  };
}

export async function getWordPressCommunities(fallback: Community[] = []): Promise<Community[] | null> {
  const posts = await rest<WPPost[]>("wp/v2/community?per_page=100&_fields=id,slug,title,content,acf");
  if (!posts?.length) return null;

  return posts.map((post) => {
    const base = fallback.find((item) => item.slug === post.slug);
    const acf = asRecord(post.acf);
    return {
      name: titleOf(post, base?.name),
      municipalityType: base?.municipalityType ?? "",
      slug: post.slug,
      summary: asString(acf.summary, base?.summary),
      heroDescription: asString(acf.hero_description, base?.heroDescription ?? stripHtml(asString(post.content?.rendered))),
      serviceDay: asString(acf.service_day, base?.serviceDay),
      trashSchedule: asString(acf.trash_schedule, base?.trashSchedule),
      recyclingSchedule: asString(acf.recycling_schedule, base?.recyclingSchedule),
      guidelines: base?.guidelines ?? [],
      acceptedRecycling: base?.acceptedRecycling ?? [],
      recyclingNotAccepted: base?.recyclingNotAccepted ?? [],
      recyclingNeverAccepted: base?.recyclingNeverAccepted ?? [],
      bulkAccepted: base?.bulkAccepted ?? [],
      bulkNotAccepted: base?.bulkNotAccepted ?? [],
      bulkPolicy: base?.bulkPolicy ?? "",
      documents: base?.documents ?? [],
      faqs: base?.faqs ?? [],
      seo: {
        title: asString(acf.seo_title, base?.seo.title ?? titleOf(post)),
        description: asString(acf.seo_description, base?.seo.description ?? asString(acf.summary))
      }
    };
  });
}

export async function getWordPressDumpsterSizes(fallback: DumpsterSize[] = []): Promise<DumpsterSize[] | null> {
  const posts = await rest<WPPost[]>("wp/v2/dumpster_size?per_page=100&_fields=id,slug,title,content,acf");
  if (!posts?.length) return null;

  return posts.map((post) => {
    const base = fallback.find((item) => item.slug === post.slug);
    const acf = asRecord(post.acf);
    return {
      name: titleOf(post, base?.name),
      slug: post.slug,
      containerType: base?.containerType ?? "Roll-off",
      capacity: asString(acf.capacity, base?.capacity),
      truckLoads: asString(acf.truck_loads, base?.truckLoads),
      dimensions: asString(acf.dimensions, base?.dimensions),
      recommendedUses: asString(acf.summary, base?.recommendedUses ?? stripHtml(asString(post.content?.rendered))),
      idealUses: base?.idealUses ?? [],
      included: base?.included ?? [],
      rentalPeriod: asString(acf.rental_period, base?.rentalPeriod),
      exampleProjects: base?.exampleProjects ?? [],
      restrictions: asString(acf.credit_card_note, base?.restrictions),
      acceptedMaterials: base?.acceptedMaterials ?? [],
      prohibitedMaterials: base?.prohibitedMaterials ?? [],
      image: mapImageUrl(acf.image) || base?.image,
      availability: base?.availability ?? "Call for availability",
      cta: base?.cta ?? { label: "Request This Size", href: `/dumpster-rentals?service=dumpster&size=${post.slug}#quote` }
    };
  });
}

export async function getWordPressServiceAreas(fallback: ServiceArea[] = []): Promise<ServiceArea[] | null> {
  const posts = await rest<WPPost[]>("wp/v2/service_area?per_page=100&_fields=id,slug,title,acf");
  if (!posts?.length) return null;

  return posts.map((post) => {
    const base = fallback.find((item) => item.name === titleOf(post));
    const acf = asRecord(post.acf);
    // ACF returns an empty string for a blank number field; only a real number counts.
    const coordinate = (value: unknown) => (value === "" || value == null || !Number.isFinite(Number(value)) ? undefined : Number(value));
    const latitude = coordinate(acf.latitude);
    const longitude = coordinate(acf.longitude);
    return {
      name: titleOf(post, base?.name),
      county: asString(acf.county, base?.county),
      serviceTypes: asStringArray(acf.service_types, base?.serviceTypes) as ServiceArea["serviceTypes"],
      ...(latitude !== undefined && longitude !== undefined ? { latitude, longitude } : {})
    };
  });
}

export async function getWordPressScheduleItems(fallback: ScheduleItem[] = []): Promise<ScheduleItem[] | null> {
  const posts = await rest<WPPost[]>("wp/v2/schedule_item?per_page=100&_fields=id,slug,title,acf");
  if (!posts?.length) return null;

  return posts.map((post) => {
    const base = fallback.find((item) => item.title === titleOf(post));
    const acf = asRecord(post.acf);
    return {
      title: titleOf(post, base?.title),
      date: asString(acf.schedule_date, base?.date),
      community: asString(acf.schedule_community, base?.community),
      description: asString(acf.schedule_description, base?.description),
      type: asString(acf.schedule_type, base?.type ?? "regular") as ScheduleItem["type"]
    };
  });
}

export async function getWordPressServiceNotices(fallback: ServiceNotice[] = []): Promise<ServiceNotice[] | null> {
  const posts = await rest<WPPost[]>("wp/v2/service_notice?per_page=100&_fields=id,slug,title,content,acf");
  if (!posts?.length) return null;

  return posts.map((post) => {
    const base = fallback.find((item) => item.slug === post.slug);
    const acf = asRecord(post.acf);
    return {
      id: String(post.id),
      slug: post.slug,
      enabled: Boolean(acf.notice_enabled ?? base?.enabled),
      status: asString(acf.notice_status, base?.status ?? "Draft") as ServiceNotice["status"],
      priority: asString(acf.notice_priority, base?.priority ?? "Informational") as ServiceNotice["priority"],
      type: base?.type ?? "announcement",
      title: asString(acf.notice_title, base?.title ?? titleOf(post)),
      summary: asString(acf.short_summary, base?.summary),
      details: asString(acf.full_details, base?.details ?? stripHtml(asString(post.content?.rendered))),
      publicUpdate: base?.publicUpdate ?? "",
      lastVerifiedAt: base?.lastVerifiedAt ?? new Date().toISOString(),
      displayStartAt: asString(acf.display_start, base?.displayStartAt ?? new Date().toISOString()),
      displayEndAt: asString(acf.display_end, base?.displayEndAt),
      showUntilResolved: Boolean(acf.show_until_resolved ?? base?.showUntilResolved),
      appliesSiteWide: base?.appliesSiteWide ?? false,
      affectedCommunities: base?.affectedCommunities ?? [],
      affectedServices: base?.affectedServices ?? [],
      locations: asStringArray(acf.display_locations, base?.locations) as ServiceNotice["locations"],
      updatedAt: base?.updatedAt ?? new Date().toISOString(),
      version: base?.version ?? "1",
      announcement: {
        enabled: Boolean(acf.announcement_text || base?.announcement.enabled),
        text: asString(acf.announcement_text, base?.announcement.text),
        label: base?.announcement.label,
        href: base?.announcement.href,
        dismissible: base?.announcement.dismissible ?? true,
        sticky: base?.announcement.sticky ?? false
      },
      popup: base?.popup ?? {
        enabled: false,
        dismissible: true,
        showOnce: true,
        repeatBehavior: "Once per notice version",
        delayMs: 0,
        requiresAcknowledgment: false,
        acknowledgmentLabel: "I understand"
      }
    };
  });
}

export async function getWordPressFAQs(fallback: FAQ[] = []): Promise<FAQ[] | null> {
  const posts = await rest<WPPost[]>("wp/v2/faq?per_page=100&_fields=id,slug,acf");
  if (!posts?.length) return null;

  const mapped = posts.map((post) => {
    const acf = asRecord(post.acf);
    const base = fallback.find((item) => item.question === asString(acf.question));
    return {
      question: asString(acf.question, base?.question),
      answer: stripHtml(asString(acf.answer, base?.answer)),
      category: asString(acf.category, base?.category ?? "general") as FAQ["category"],
      sortOrder: Number(acf.sortOrder ?? 0)
    };
  });

  mapped.sort((a, b) => a.sortOrder - b.sortOrder);
  return mapped.map(({ question, answer, category }) => ({ question, answer, category }));
}
