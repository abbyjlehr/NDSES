export type CTA = {
  label: string;
  href: string;
  description?: string;
  children?: CTA[];
};

export type SEOFields = {
  title: string;
  description: string;
  canonical?: string;
  noIndex?: boolean;
  ogImage?: string;
};

export type GlobalSettings = {
  companyName: string;
  phone: string;
  email: string;
  address: string;
  mailingAddress?: string;
  businessHours: string;
  serviceArea: string;
  footerDescription: string;
  copyrightText: string;
  navigation: CTA[];
  socialLinks?: Array<{ label: string; href: string }>;
};

export type FAQ = {
  question: string;
  answer: string;
  category: "residential" | "commercial" | "dumpster" | "event" | "general";
};

export type DocumentLink = {
  title: string;
  href: string;
  description?: string;
};

export type Hero = {
  eyebrow: string;
  heading: string;
  description: string;
  actions: CTA[];
};

export type PageSection =
  | {
      id: string;
      type: "textImage";
      eyebrow: string;
      heading: string;
      body: string;
      image?: string;
      imageAlt?: string;
    }
  | {
      id: string;
      type: "serviceGrid";
      eyebrow: string;
      heading: string;
      services: Array<{ title: string; description: string; href: string; ctaLabel: string }>;
    }
  | {
      id: string;
      type: "cta";
      eyebrow: string;
      heading: string;
      body: string;
      label: string;
      href: string;
    }
  | {
      id: string;
      type: "faq";
      heading: string;
      faqs: FAQ[];
    }
  | {
      id: string;
      type: "items";
      eyebrow: string;
      heading: string;
      items: Array<{ title: string; body: string }>;
    };

export type CMSPage = {
  slug: string;
  hero: Hero;
  sections: PageSection[];
  seo: SEOFields;
};

export type Community = {
  name: string;
  municipalityType: string;
  slug: string;
  summary: string;
  heroDescription: string;
  serviceDay: string;
  trashSchedule: string;
  recyclingSchedule: string;
  guidelines: string[];
  acceptedRecycling: string[];
  recyclingNotAccepted: string[];
  recyclingNeverAccepted: string[];
  bulkAccepted: string[];
  bulkNotAccepted: string[];
  bulkPolicy: string;
  documents: DocumentLink[];
  faqs: FAQ[];
  seo: SEOFields;
};

export type DumpsterSize = {
  name: string;
  slug: string;
  containerType: string;
  capacity: string;
  truckLoads?: string;
  dimensions: string;
  recommendedUses: string;
  idealUses: string[];
  included: string[];
  rentalPeriod: string;
  exampleProjects: string[];
  restrictions: string;
  acceptedMaterials: string[];
  prohibitedMaterials: string[];
  image?: string;
  availability: string;
  cta: CTA;
};

export type CommercialContainer = {
  name: string;
  slug: string;
  description: string;
  dimensions?: string;
  capacity?: string;
  image?: string;
  idealFor: string[];
};

export type ServiceArea = {
  name: string;
  county: string;
  serviceTypes: Array<"residential" | "commercial" | "rollOff">;
  /** Decimal degrees. Only towns pinned on the Walworth County detail map carry them. */
  latitude?: number;
  longitude?: number;
};

export type ScheduleItem = {
  title: string;
  date?: string;
  community?: string;
  description: string;
  type: "regular" | "holiday" | "delay" | "special";
};

export type NoticePriority = "Emergency" | "Urgent" | "Important" | "Standard" | "Informational";
export type NoticeStatus = "Draft" | "Scheduled" | "Active" | "Resolved" | "Expired";
export type NoticeLocation = "siteWide" | "popup" | "home" | "residential" | "commercial" | "dumpster" | "events" | "community" | "contact" | "payment" | "archive";

export type ServiceNotice = {
  id: string;
  slug: string;
  enabled: boolean;
  status: NoticeStatus;
  priority: NoticePriority;
  type: string;
  title: string;
  summary: string;
  details: string;
  publicUpdate: string;
  lastVerifiedAt: string;
  displayStartAt: string;
  displayEndAt?: string;
  showUntilResolved: boolean;
  appliesSiteWide: boolean;
  affectedCommunities: string[];
  affectedServices: string[];
  locations: NoticeLocation[];
  updatedAt: string;
  version: string;
  announcement: {
    enabled: boolean;
    text?: string;
    label?: string;
    href?: string;
    dismissible: boolean;
    sticky: boolean;
  };
  popup: {
    enabled: boolean;
    heading?: string;
    message?: string;
    label?: string;
    href?: string;
    dismissible: boolean;
    showOnce: boolean;
    repeatBehavior: "Every visit" | "Once per browser session" | "Once per day" | "Once per notice version" | "Until the notice changes";
    delayMs: number;
    requiresAcknowledgment: boolean;
    acknowledgmentLabel: string;
  };
};
