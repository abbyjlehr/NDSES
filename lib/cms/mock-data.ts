import type {
  CMSPage,
  CommercialContainer,
  Community,
  DumpsterSize,
  FAQ,
  GlobalSettings,
  ScheduleItem,
  ServiceArea,
  ServiceNotice
} from "@/types/cms";

// TODO: ACF - Replace these structured objects with WordPress ACF field groups.
const mission =
  "NDS Environmental Solutions is a locally owned and operated business. Our mission is to provide reliable, affordable, and eco-friendly waste collection services that strengthen Wisconsin's communities. We pride ourselves on delivering personalized service with a deep commitment to our neighbors.";

export const globalSettings: GlobalSettings = {
  companyName: "NDS Environmental Solutions",
  phone: "262-233-6131",
  email: "info@ndses.com",
  address: "1635 Mound Rd, Delavan, WI 53115",
  mailingAddress: "PO Box 464, Delavan, WI 53115",
  businessHours: "Monday - Thursday: 8:00am - 4:00pm; Friday: 8:00am - 2:00pm",
  serviceArea: "Southern Wisconsin, including Walworth County and select areas of Rock, Jefferson, Waukesha, and Kenosha counties.",
  footerDescription: "Local waste management for homes, businesses, communities, projects, and events in Southern Wisconsin.",
  copyrightText: "Copyright 2026 NDS Environmental Solutions, LLC. All rights reserved.",
  socialLinks: [
    { label: "Facebook", href: "https://www.facebook.com/" },
    { label: "Instagram", href: "https://www.instagram.com/" }
  ],
  navigation: [
    {
      label: "Residential",
      href: "/residential",
      description: "Residential trash, recycling, schedules, and community pages.",
      children: [
        { label: "Residential Overview", href: "/residential", description: "Trash, recycling, bulk-item guidance, and FAQs." },
        { label: "Town of Walworth", href: "/residential/town-of-walworth", description: "Thursday collection and local notices." },
        { label: "Town of Delavan", href: "/residential/town-of-delavan", description: "Monday collection and local notices." },
        { label: "City of Delavan", href: "/residential/city-of-delavan", description: "Wednesday collection and local notices." },
        { label: "Town of Darien", href: "/residential/town-of-darien", description: "Tuesday collection." },
        { label: "Town of Sharon", href: "/residential/town-of-sharon", description: "Tuesday collection." }
      ]
    },
    {
      label: "Commercial",
      href: "/commercial",
      description: "Commercial trash, recycling, containers, and quote requests.",
      children: [
        { label: "Commercial Overview", href: "/commercial", description: "Permanent containers and business service plans." },
        { label: "Container Options", href: "/commercial#containers", description: "96-gallon, 2-yard, 4-yard, 6-yard, and 8-yard options." },
        { label: "Commercial Recycling", href: "/commercial#recycling", description: "Cardboard, paper, plastic, aluminum, and mixed recycling." }
      ]
    },
    {
      label: "Dumpster Rentals",
      href: "/dumpster-rentals",
      description: "Temporary roll-off dumpsters and size guidance.",
      children: [
        { label: "Rental Overview", href: "/dumpster-rentals", description: "Temporary dumpsters for projects and cleanouts." },
        { label: "Dumpster Calculator", href: "/dumpster-calculator", description: "Choose the right roll-off size." },
        { label: "Accepted Materials", href: "/dumpster-rentals#materials", description: "What can and cannot go in a rental dumpster." }
      ]
    },
    {
      label: "Resources",
      href: "/whats-new",
      description: "Updates, FAQs, contact, payment, and company information.",
      children: [
        { label: "What's New", href: "/whats-new", description: "Alerts and service updates." },
        { label: "FAQs", href: "/faqs", description: "Dumpster, residential, and commercial questions." },
        { label: "About Us", href: "/about", description: "Mission, history, and team structure." },
        { label: "Contact Us", href: "/contact", description: "Quotes, estimates, and questions." },
        { label: "Make a Payment", href: "/make-a-payment", description: "Pay your account balance online by card or ACH." }
      ]
    }
  ]
};

export const faqs: FAQ[] = [
  { category: "dumpster", question: "How do I choose the right dumpster size?", answer: "The best dumpster size depends on your project. Smaller dumpsters are great for garage cleanouts and minor renovations, while larger dumpsters are ideal for construction, demolition, and whole-home cleanouts. If you're unsure, our team is happy to help you choose the right size." },
  { category: "dumpster", question: "How long can I keep the dumpster?", answer: "The rental period is 15 days. If you need accommodations to this, please let us know and we will work with you." },
  { category: "dumpster", question: "Do I need to be home for delivery?", answer: "No. As long as you've provided clear placement instructions and the delivery area is accessible, you do not need to be present." },
  { category: "dumpster", question: "How full can I load the dumpster?", answer: "Materials should never extend above the top edge of the dumpster. Overfilled dumpsters may require materials to be removed before pickup for safety reasons." },
  { category: "dumpster", question: "How soon can my dumpster be delivered?", answer: "We strive to provide prompt service and can often deliver within one to two business days, depending on availability and your location. Contact us for current scheduling." },
  { category: "dumpster", question: "How much does a dumpster rental cost?", answer: "Pricing depends on several factors, including dumpster size, rental length, location, and the type of debris being disposed of. Contact us for a free, no-obligation quote." },
  { category: "dumpster", question: "What happens when I'm finished with the dumpster?", answer: "Simply call us when you're ready, or schedule your pickup in advance. We'll come collect the dumpster and properly dispose of the contents." },
  { category: "dumpster", question: "Still have questions?", answer: "Our team is here to help. Contact us by phone, email, or through our online form, and we'll be happy to answer any questions or help you choose the right service for your home or business." },
  { category: "residential", question: "When is my trash and recycling pickup?", answer: "Town of Delavan: Monday. Town of Sharon/Darien: Tuesday. City of Delavan: Wednesday. Town of Walworth: Thursday." },
  { category: "residential", question: "What time should I place my bins at the curb?", answer: "Please have your cart at the curb by 5:30 a.m. on your scheduled collection day." },
  { category: "residential", question: "What can I put in my trash can?", answer: "Household waste such as food waste, packaging, paper products, clothing, and non-hazardous household items." },
  { category: "residential", question: "What can I recycle?", answer: "Recycling items include cardboard, paper, aluminum, steel cans, plastic bottles and jugs, and glass bottles and jars." },
  { category: "residential", question: "Do I need to bag my recyclables?", answer: "Yes, please place all recyclables in clear plastic recyclable bags." },
  { category: "residential", question: "Do you offer bulk item pickups?", answer: "Yes. If you are a current residential customer, you get two free bulk item pickups per month for items such as furniture or mattresses. Contact us ahead for scheduling and guidelines." },
  { category: "residential", question: "What happens if a holiday falls on my pickup day?", answer: "Please refer to our service calendar to see holidays that will result in a one-day delay in service." },
  { category: "residential", question: "Does NDS supply the cans or do I?", answer: "NDS Environmental Solutions does not supply residential garbage cans. You may have up to three 32-gallon bins for both trash and recycle or one 96-gallon can for each. Anything beyond this will be considered extra and is subject to additional fees." },
  { category: "residential", question: "Still have questions?", answer: "Our team is here to help. Contact us by phone, email, or through our online form, and we'll be happy to answer any questions or help you choose the right service for your home or business." },
  { category: "commercial", question: "What size dumpsters do you offer?", answer: "We offer 96-gallon containers, 2-yard dumpsters, 4-yard dumpsters, 6-yard dumpsters, and 8-yard dumpsters. Our team can help determine the best size for your business." },
  { category: "commercial", question: "How often can my dumpster be serviced?", answer: "We offer flexible schedules ranging from once per week to multiple collections per week depending on your needs." },
  { category: "commercial", question: "Can I change my dumpster size or frequency?", answer: "Absolutely. As your business grows or your needs change, we can adjust your container size or collection schedule. Give us a call at 262-233-6131 to arrange any changes." },
  { category: "commercial", question: "Do you offer temporary dumpsters for businesses?", answer: "Yes. In addition to regular commercial service, we offer temporary roll-off dumpsters for construction projects, renovations, office cleanouts, and special events." },
  { category: "commercial", question: "What happens if my dumpster is overflowing?", answer: "If your dumpster is consistently full before pickup, contact us about increasing your service frequency or upgrading to a larger container." },
  { category: "commercial", question: "How do I request service or get a quote?", answer: "Call us at 262-233-6131 or fill out our online quote request form. We'll help you choose the right service and provide a customized quote." },
  { category: "commercial", question: "Still have questions?", answer: "Our team is here to help. Contact us by phone, email, or through our online form, and we'll be happy to answer any questions or help you choose the right service for your home or business." }
];

const serviceCards = [
  { title: "Residential Trash & Recycling", description: "Curbside and drive-up garbage and recycling services depending on your community.", href: "/residential", ctaLabel: "Find your community" },
  { title: "Commercial Trash & Recycling", description: "Permanent trash and recycling dumpsters with collection schedules matched to your business.", href: "/commercial", ctaLabel: "Request a quote" },
  { title: "Dumpster Rentals", description: "Temporary roll-off dumpsters for renovations, construction debris, cleanouts, and events.", href: "/dumpster-rentals", ctaLabel: "Choose a dumpster" }
];

export const homePage: CMSPage = {
  slug: "home",
  hero: {
    eyebrow: "Southern Wisconsin Waste Management",
    heading: "Reliable local trash, recycling, and dumpster service",
    description: "A local waste management provider in Southern Wisconsin, delivering honest, reliable, and environmentally conscious solutions to individuals, businesses, and communities.",
    actions: [
      { label: "Get a Quote", href: "/contact" },
      { label: "Rent a Dumpster", href: "/dumpster-rentals" },
      { label: "Call NDS", href: "tel:262-233-6131" }
    ]
  },
  sections: [
    { id: "home-services", type: "serviceGrid", eyebrow: "Our Services", heading: "Choose the service you need", services: serviceCards },
    { id: "intro", type: "textImage", eyebrow: "Why NDS", heading: "Waste solutions from a local team that knows the routes", body: "We understand that effective waste management is critical to the quality of life in Wisconsin's towns and cities. Whether you're looking for dependable residential trash pickup or tailored solutions for commercial waste, NDS Environmental Solutions is committed to exceeding your expectations.", image: "/nds-assets/branding-03-bffd0f61cb.png", imageAlt: "NDS Environmental Solutions collection truck" },
    { id: "mission", type: "cta", eyebrow: "Our Mission", heading: "Reliable, affordable, and eco-friendly service", body: mission, label: "Learn About NDS", href: "/about" }
  ],
  seo: { title: "NDS Environmental Solutions", description: "Residential trash, recycling, commercial waste, and dumpster rentals in Southern Wisconsin." }
};

export const residentialPage: CMSPage = {
  slug: "residential",
  hero: {
    eyebrow: "Residential Communities Serviced",
    heading: "Residential trash and recycling by community",
    description: "Curbside and drive-up garbage and recycling services for Town of Walworth, Town of Delavan, City of Delavan, Town of Darien, and Town of Sharon.",
    actions: [{ label: "Request Service", href: "/contact" }, { label: "View FAQs", href: "/faqs#residential" }]
  },
  sections: [
    { id: "residential-faqs", type: "faq", heading: "Residential Trash & Recycling FAQs", faqs: faqs.filter((faq) => faq.category === "residential") }
  ],
  seo: { title: "Residential Trash & Recycling", description: "Residential garbage and recycling schedules, container rules, recycling lists, and bulk item guidance from NDS." }
};

export const commercialPage: CMSPage = {
  slug: "commercial",
  hero: {
    eyebrow: "Commercial Waste Management",
    heading: "Commercial waste and recycling service tailored to your business",
    description: "Permanent trash and recycling dumpsters with flexible collection schedules for businesses in Walworth County and select nearby areas.",
    actions: [{ label: "Request a Free Quote", href: "/contact" }, { label: "Call 262-233-6131", href: "tel:262-233-6131" }]
  },
  sections: [
    { id: "commercial-faqs", type: "faq", heading: "Commercial Trash & Recycling FAQs", faqs: faqs.filter((faq) => faq.category === "commercial") }
  ],
  seo: { title: "Commercial Trash & Recycling", description: "Commercial waste management, permanent dumpsters, recycling, and flexible pickup schedules from NDS Environmental Solutions." }
};

export const dumpsterPage: CMSPage = {
  slug: "dumpster-rentals",
  hero: {
    eyebrow: "Dumpster Rentals",
    heading: "Temporary roll-off dumpsters for cleanouts, remodels, and projects",
    description: "Competitive pricing, prompt delivery, and responsive service for roll-off dumpster rentals in Walworth and surrounding counties.",
    actions: [{ label: "Find the Right Size", href: "/dumpster-calculator" }, { label: "Request a Quote", href: "/contact" }]
  },
  sections: [
    { id: "dumpster-faqs", type: "faq", heading: "Dumpster Rental FAQs", faqs: faqs.filter((faq) => faq.category === "dumpster") }
  ],
  seo: { title: "Dumpster Rentals", description: "Temporary dumpster rentals for home cleanouts, renovations, construction debris, roofing, and projects in Southern Wisconsin." }
};

export const specialEventsPage: CMSPage = {
  slug: "special-events",
  hero: {
    eyebrow: "Special Events",
    heading: "Temporary waste service for events and gatherings",
    description: "Plan containers, collection support, setup timing, and cleanup needs for community events, corporate events, weddings, and temporary service needs.",
    actions: [{ label: "Request Event Quote", href: "/contact" }]
  },
  sections: [
    { id: "event-options", type: "items", eyebrow: "Options", heading: "Event service support", items: [
      { title: "Community events", body: "Temporary container arrangements and collection planning." },
      { title: "Corporate events and weddings", body: "Clean, organized waste collection support for guest-facing events." },
      { title: "Cleanup support", body: "Post-event collection needs and removal timing managed through quote requests." }
    ] }
  ],
  seo: { title: "Special Event Waste Services", description: "Temporary waste collection and cleanup support for events." }
};

export const calculatorPage: CMSPage = {
  slug: "dumpster-calculator",
  hero: {
    eyebrow: "Dumpster Calculator",
    heading: "Choose the right roll-off dumpster",
    description: "Use project type and material information to compare available 10, 12, 15, 20, and 30 yard dumpster rentals.",
    actions: []
  },
  sections: [],
  seo: { title: "Dumpster Calculator", description: "Choose the right NDS roll-off dumpster size for cleanouts, remodels, roofing, construction debris, and heavy materials." }
};

export const faqsPage: CMSPage = {
  slug: "faqs",
  hero: {
    eyebrow: "FAQs",
    heading: "Questions about NDS service",
    description: "Find quick answers for dumpster rentals, residential trash and recycling, and commercial waste service.",
    actions: []
  },
  sections: [],
  seo: { title: "FAQs", description: "Answers to common NDS dumpster rental, residential trash and recycling, and commercial waste questions." }
};

export const aboutPage: CMSPage = {
  slug: "about",
  hero: {
    eyebrow: "About NDS",
    heading: "Local waste service built around Wisconsin communities",
    description: "Learn about the mission, history, and team behind NDS Environmental Solutions.",
    actions: []
  },
  sections: [],
  seo: { title: "About Us", description: "Learn about NDS Environmental Solutions, a locally owned waste management provider serving Southern Wisconsin." }
};

export const contactPage: CMSPage = {
  slug: "contact",
  hero: {
    eyebrow: "Contact NDSES",
    heading: "Questions, quotes, and service support",
    description: "Send a message for free quotes, estimates, service questions, or account support.",
    actions: []
  },
  sections: [],
  seo: { title: "Contact", description: "Contact NDS Environmental Solutions for service, quotes, questions, and urgent service updates." }
};

export const paymentPage: CMSPage = {
  slug: "make-a-payment",
  hero: {
    eyebrow: "Secure Payment",
    heading: "Make a payment",
    description: "Pay your NDS account balance securely online by card or ACH bank transfer.",
    actions: []
  },
  sections: [],
  seo: { title: "Make a Payment", description: "Secure payment entry point for NDS Environmental Solutions accounts." }
};

export const whatsNewPage: CMSPage = {
  slug: "whats-new",
  hero: {
    eyebrow: "What's New",
    heading: "Alerts and service updates",
    description: "Weather delays, holiday schedule changes, closures, announcements, and route updates will appear here.",
    actions: []
  },
  sections: [],
  seo: { title: "What's New", description: "Service alerts, holiday schedule updates, weather notices, closures, and announcements from NDS Environmental Solutions." }
};

const acceptedRecycling = ["Aluminum and steel cans", "Food and beverage cartons and containers, non-styrofoam", "Glass bottles and jars", "Flattened cardboard", "Paper", "Cereal boxes", "Plastic bottles and containers"];
const recyclingNotAccepted = ["Scrap metal", "Construction debris", "Styrofoam"];
const recyclingNeverAccepted = ["Flammables", "Sharps", "Wires", "Batteries", "Hoses", "Diapers", "Household hazardous waste", "Compressed cylinders", "Electronics of any kind"];
const bulkAccepted = ["Household furniture", "Mattresses", "Appliances (fees may apply)"];
const bulkNotAccepted = ["Pools", "Hot tubs", "Swing sets", "Yard waste", "Construction or remodeling debris"];

function community(name: string, municipalityType: string, slug: string, serviceDay: string, trashSchedule: string, recyclingSchedule: string, cans: string): Community {
  return {
    name,
    municipalityType,
    slug,
    summary: `${serviceDay} residential collection.`,
    heroDescription: mission,
    serviceDay,
    trashSchedule,
    recyclingSchedule,
    guidelines: ["Trash is collected weekly.", "Residents are required to provide their own trash cans, not exceeding 60 pounds.", cans, "Please bag all trash and place it at roadside no later than 5:30am."],
    acceptedRecycling,
    recyclingNotAccepted,
    recyclingNeverAccepted,
    bulkAccepted,
    bulkNotAccepted,
    bulkPolicy: "This is not an inclusive list. Please contact us to schedule a bulk pickup. Electronics and certain items can be collected by contacting our office; a fee may apply.",
    documents: [],
    faqs: faqs.filter((faq) => faq.category === "residential"),
    seo: { title: `${name} Collection Schedule`, description: `Trash, recycling, bulk pickup, and service information for ${name}.` }
  };
}

export const communities: Community[] = [
  community("Town of Walworth", "Town", "town-of-walworth", "Thursday", "Weekly", "Every Other Week (see service calendar and map below)", "We will collect up to the equivalent of four 32-gallon cans each week for both trash and recycle."),
  community("Town of Delavan", "Town", "town-of-delavan", "Monday", "Weekly, Every Other Week, Monthly", "Weekly, Every Other Week, Monthly", "We will collect up to the equivalent of three 32-gallon cans each week for both trash and recycle."),
  community("City of Delavan", "City", "city-of-delavan", "Wednesday", "Weekly, Every Other Week, Monthly", "Weekly, Every Other Week, Monthly", "We will collect up to the equivalent of three 32-gallon cans each week for both trash and recycle."),
  community("Town of Darien", "Town", "town-of-darien", "Tuesday", "Weekly, Every Other Week, Monthly", "Weekly, Every Other Week, Monthly", "We will collect up to the equivalent of three 32-gallon cans each week for both trash and recycle."),
  community("Town of Sharon", "Town", "town-of-sharon", "Tuesday", "Weekly, Every Other Week, Monthly", "Weekly, Every Other Week, Monthly", "We will collect up to the equivalent of three 32-gallon cans each week for both trash and recycle.")
];

const rollOffAccepted = ["Wood", "Furniture", "Construction debris", "Drywall", "Roofing shingles", "Cardboard", "Household junk"];
const rollOffProhibited = ["Paint", "Batteries", "Tires", "Hazardous chemicals", "Propane tanks", "Electronics", "Appliances", "Yard waste", "Mattresses"];
const included = ["2 tons of trash", "15 day rental", "Delivery and pickup", "Disposal"];
const dimensionsNote = " Dumpster dimensions may vary based on different manufacturers.";

export const dumpsterSizes: DumpsterSize[] = [
  {
    name: "10 Yard Dumpster",
    slug: "10-yard",
    containerType: "Roll-off",
    capacity: "8-10 pickup truck loads",
    truckLoads: "8-10 pickup truck loads",
    dimensions: `11' L x 8' W x 3.5' H.${dimensionsNote}`,
    recommendedUses: "Small remodeling projects, home cleanouts, and concrete or other heavy materials.",
    idealUses: ["Small remodeling projects", "Home cleanouts", "Concrete/heavy materials"],
    included,
    rentalPeriod: "15 days",
    exampleProjects: ["Home/Garage Cleanout", "Concrete, Dirt, and Rocks"],
    restrictions: "Materials must remain below the top edge. Credit card payments include a 3% fee.",
    acceptedMaterials: rollOffAccepted,
    prohibitedMaterials: rollOffProhibited,
    image: "/nds-assets/dumpster-10-yard.png",
    availability: "Call for availability",
    cta: { label: "Request This Size", href: "/dumpster-rentals?service=dumpster&size=10-yard#quote" }
  },
  {
    name: "12 Yard Dumpster",
    slug: "12-yard",
    containerType: "Roll-off",
    capacity: "11-14 pickup truck loads",
    truckLoads: "11-14 pickup truck loads",
    dimensions: `11' L x 8' W x 4' H.${dimensionsNote}`,
    recommendedUses: "Medium home renovations, estate cleanouts, deck removals, and roofing projects.",
    idealUses: ["Medium home renovations", "Estate cleanouts", "Deck removals", "Roofing projects"],
    included,
    rentalPeriod: "15 days",
    exampleProjects: ["Home/Garage Cleanout", "Bathroom/Kitchen Demo"],
    restrictions: "Materials must remain below the top edge. Credit card payments include a 3% fee.",
    acceptedMaterials: rollOffAccepted,
    prohibitedMaterials: rollOffProhibited,
    image: "/nds-assets/dumpster-12-yard.png",
    availability: "Call for availability",
    cta: { label: "Request This Size", href: "/dumpster-rentals?service=dumpster&size=12-yard#quote" }
  },
  {
    name: "15 Yard Dumpster",
    slug: "15-yard",
    containerType: "Roll-off",
    capacity: "14-16 pickup truck loads",
    truckLoads: "14-16 pickup truck loads",
    dimensions: `15' L x 8' W x 4.5' H.${dimensionsNote}`,
    recommendedUses: "Kitchen remodels, multi-room renovations, and moderate construction debris.",
    idealUses: ["Kitchen remodels", "Multi-room renovations", "Moderate construction debris"],
    included,
    rentalPeriod: "15 days",
    exampleProjects: ["Home Remodel", "Bathroom/Kitchen Demo", "Construction Debris"],
    restrictions: "Materials must remain below the top edge. Credit card payments include a 3% fee.",
    acceptedMaterials: rollOffAccepted,
    prohibitedMaterials: rollOffProhibited,
    image: "/nds-assets/dumpster-15-yard.png",
    availability: "Call for availability",
    cta: { label: "Request This Size", href: "/dumpster-rentals?service=dumpster&size=15-yard#quote" }
  },
  {
    name: "20 Yard Dumpster",
    slug: "20-yard",
    containerType: "Roll-off",
    capacity: "17-20 pickup truck loads",
    truckLoads: "17-20 pickup truck loads",
    dimensions: `22' L x 8' W x 4.5' H.${dimensionsNote}`,
    recommendedUses: "Large home renovations, whole-house cleanouts, and construction debris.",
    idealUses: ["Large home renovations", "Whole-house cleanouts", "Construction debris"],
    included,
    rentalPeriod: "15 days",
    exampleProjects: ["Home Remodel", "Roofing/Siding Teardown", "Construction Debris"],
    restrictions: "Materials must remain below the top edge. Credit card payments include a 3% fee.",
    acceptedMaterials: rollOffAccepted,
    prohibitedMaterials: rollOffProhibited,
    image: "/nds-assets/dumpster-20-yard.png",
    availability: "Call for availability",
    cta: { label: "Request This Size", href: "/dumpster-rentals?service=dumpster&size=20-yard#quote" }
  },
  {
    name: "30 Yard Dumpster",
    slug: "30-yard",
    containerType: "Roll-off",
    capacity: "20-26 pickup truck loads",
    truckLoads: "20-26 pickup truck loads",
    dimensions: `22' L x 8' W x 6' H.${dimensionsNote}`,
    recommendedUses: "Major construction projects, large demolition projects, and large-scale remodeling projects.",
    idealUses: ["Major construction projects", "Large demolition projects", "Large-scale remodeling projects"],
    included,
    rentalPeriod: "15 days",
    exampleProjects: ["Roofing/Siding Teardown", "Construction Debris", "Other"],
    restrictions: "Materials must remain below the top edge. Credit card payments include a 3% fee.",
    acceptedMaterials: rollOffAccepted,
    prohibitedMaterials: rollOffProhibited,
    image: "/nds-assets/dumpster-30-yard.png",
    availability: "Call for availability",
    cta: { label: "Request This Size", href: "/dumpster-rentals?service=dumpster&size=30-yard#quote" }
  }
];

export const commercialContainers: CommercialContainer[] = [
  { name: "96 Gallon", slug: "96-gallon", description: "Compact container for lower-volume recurring trash or recycling service.", idealFor: ["Small offices", "Low-volume retail", "Churches"] },
  { name: "2 Yard", slug: "2-yard", description: "Small rear-load dumpster for businesses with steady weekly disposal needs.", dimensions: `66" L x 38" W x 48" H`, capacity: "1-2 pickup truck loads", image: "/nds-assets/dumpster-2-yard.png", idealFor: ["Small offices & businesses", "1-4 unit properties"] },
  { name: "4 Yard", slug: "4-yard", description: "Mid-size commercial container for growing waste streams.", dimensions: `87" L x 66" W x 48" H`, capacity: "3-4 pickup truck loads", image: "/nds-assets/dumpster-4-yard.png", idealFor: ["Restaurants", "Small apartment complexes", "Medium retail stores"] },
  { name: "6 Yard", slug: "6-yard", description: "Larger container for busy sites that need more capacity.", dimensions: `111" L x 66" W x 53" H`, capacity: "5-6 pickup truck loads", image: "/nds-assets/dumpster-6-yard.png", idealFor: ["Large restaurants", "Large apartment complexes", "Schools", "Small remodeling projects", "Areas with space constraints"] },
  { name: "8 Yard", slug: "8-yard", description: "High-capacity permanent dumpster for demanding commercial accounts.", dimensions: `116" L x 66" W x 73" H`, capacity: "7-8 pickup truck loads", image: "/nds-assets/dumpster-8-yard.png", idealFor: ["Manufacturing", "Schools", "Large retail"] }
];

export const serviceAreas: ServiceArea[] = [
  // Town coordinates match the `places` table in scripts/build-service-area-map.cjs.
  { name: "Town of Walworth", county: "Walworth County", serviceTypes: ["residential", "commercial", "rollOff"], latitude: 42.531, longitude: -88.599 },
  { name: "Town of Delavan", county: "Walworth County", serviceTypes: ["residential", "commercial", "rollOff"], latitude: 42.588, longitude: -88.572 },
  { name: "City of Delavan", county: "Walworth County", serviceTypes: ["residential", "commercial", "rollOff"], latitude: 42.6331, longitude: -88.6437 },
  { name: "Town of Darien", county: "Walworth County", serviceTypes: ["residential", "commercial", "rollOff"], latitude: 42.601, longitude: -88.708 },
  { name: "Town of Sharon", county: "Walworth County", serviceTypes: ["residential", "commercial", "rollOff"], latitude: 42.502, longitude: -88.729 },
  { name: "Rock County", county: "Rock County", serviceTypes: ["commercial", "rollOff"] },
  { name: "Jefferson County", county: "Jefferson County", serviceTypes: ["commercial", "rollOff"] },
  { name: "Waukesha County", county: "Waukesha County", serviceTypes: ["commercial", "rollOff"] },
  { name: "Kenosha County", county: "Kenosha County", serviceTypes: ["commercial", "rollOff"] }
];

export const scheduleItems: ScheduleItem[] = [
  { title: "Town of Delavan pickup", community: "Town of Delavan", description: "Regular residential trash pickup is Monday.", type: "regular" },
  { title: "Town of Darien and Town of Sharon pickup", community: "Town of Darien/Sharon", description: "Regular residential trash pickup is Tuesday.", type: "regular" },
  { title: "City of Delavan pickup", community: "City of Delavan", description: "Regular residential trash pickup is Wednesday.", type: "regular" },
  { title: "Town of Walworth pickup", community: "Town of Walworth", description: "Regular residential trash pickup is Thursday; recycling is every other week.", type: "regular" },
  { title: "Holiday service delays", description: "Please refer to the service calendar for holidays that result in a one-day delay in service.", type: "holiday" }
];

export const serviceNotices: ServiceNotice[] = [
  {
    id: "local-alerts-placeholder",
    slug: "local-alerts-placeholder",
    enabled: true,
    status: "Active",
    priority: "Informational",
    type: "announcement",
    title: "Service alerts and updates",
    summary: "Weather delays, holiday schedule changes, closures, and route updates will be posted here.",
    details: "NDS posts alerts here directly as they come up.",
    publicUpdate: "No urgent service alerts are active.",
    lastVerifiedAt: "2026-08-10T12:00:00.000Z",
    displayStartAt: "2026-08-10T12:00:00.000Z",
    showUntilResolved: true,
    appliesSiteWide: false,
    affectedCommunities: [],
    affectedServices: [],
    locations: ["home", "archive"],
    updatedAt: "2026-08-10T12:00:00.000Z",
    version: "1",
    announcement: { enabled: false, dismissible: true, sticky: false },
    popup: { enabled: false, dismissible: true, showOnce: true, repeatBehavior: "Once per notice version", delayMs: 0, requiresAcknowledgment: false, acknowledgmentLabel: "I understand" }
  }
];

export const aboutContent = {
  hero: {
    eyebrow: "About NDS",
    heading: "Local waste service built around Wisconsin communities",
    description: "Learn about the mission, history, and team behind NDS Environmental Solutions."
  },
  history: [
    "NDS Environmental Solutions has built its reputation on dependable service, strong relationships, and a commitment to the communities it serves.",
    "Founded in 2025 by Dustin Nieuwenhuis, NDS Environmental Solutions was built on a foundation of family legacy, industry experience, and hard work. Dustin has a deep family history in the waste management industry within Walworth County and had been active in the former family business until its sale in 2023. After a year and a half of reflection, Dustin felt the community was missing the local/neighborly waste collection service. What began as Nieuwenhuis Disposal Service has grown into NDS Environmental Solutions, a business dedicated to providing waste and disposal solutions for residential, commercial, and temporary and permanent construction dumpsters. Our team consists of long-time waste management professionals and family members who drive these values.",
    "Over the last two years, NDS/ES has continued to grow and evolve, expanding its services and capabilities to meet the changing needs of its customers. Through hard work, dedication, and a focus on doing the job right, the company has continued to build lasting relationships with the customers and communities it serves.",
    "As a family-owned/local business, NDS/ES takes pride in offering personal service and treating customers like neighbors rather than just account numbers. These values that helped establish the company continue to guide our approach to service, its relationships with customers, and its commitment to doing business the right way.",
    "Today, NDS Environmental Solutions continues to build on its history while looking toward the future. With a focus on reliable service, continued improvement, and strong community relationships, NDS/ES remains committed to finding practical disposal solutions and serving its customers for years to come."
  ],
  mission,
  teamPlaceholder: "Team member names, roles, and photos can be added through the future CMS."
};

export const paymentContent = {
  note: "Payments are processed securely by PayEngine. Card and bank details are entered directly into PayEngine's secure fields and never touch NDS's servers.",
  methods: ["Card payment", "ACH payment"]
};
