<?php
/**
 * Local structured fallback content.
 *
 * TODO: ACF - Replace these defaults with WordPress ACF fields and CPT content.
 */

if (!defined('ABSPATH')) {
    exit;
}

function ndses_data(): array
{
    static $data = null;

    if ($data !== null) {
        return $data;
    }

    $mission = "NDS Environmental Solutions is a locally owned and operated business. Our mission is to provide reliable, affordable, and eco-friendly waste collection services that strengthen Wisconsin's communities. We pride ourselves on delivering personalized service with a deep commitment to our neighbors.";
    $accepted_recycling = ['Aluminum and steel cans', 'Food and beverage cartons and containers, non-styrofoam', 'Glass bottles and jars', 'Flattened cardboard', 'Paper', 'Cereal boxes', 'Plastic bottles and containers'];
    $recycling_not_accepted = ['Scrap metal', 'Construction debris', 'Styrofoam'];
    $recycling_never = ['Flammables', 'Sharps', 'Wires', 'Batteries', 'Hoses', 'Diapers', 'Household hazardous waste', 'Compressed cylinders', 'Electronics of any kind'];
    $bulk_accepted = ['Household furniture', 'Mattresses', 'Appliances may require a fee'];
    $bulk_not = ['Pools', 'Hot tubs', 'Swing sets', 'Yard waste', 'Construction or remodeling debris'];
    $rolloff_accepted = ['Wood', 'Furniture', 'Construction debris', 'Drywall', 'Roofing shingles', 'Cardboard', 'Household junk'];
    $rolloff_prohibited = ['Paint', 'Batteries', 'Tires', 'Hazardous chemicals', 'Propane tanks', 'Electronics', 'Appliances', 'Yard waste', 'Mattresses'];
    $included = ['2 tons of trash', '15 day rental', 'Delivery and pickup', 'Disposal'];

    $data = [
        'settings' => [
            'company_name' => 'NDS Environmental Solutions',
            'legal_name' => 'NDS Environmental Solutions, LLC.',
            'phone' => '262-233-6131',
            'email' => 'info@ndses.com',
            'address' => '1635 Mound Rd, Delavan, WI 53115',
            'mailing_address' => 'PO Box 464, Delavan, WI 53115',
            'hours' => ['Monday - Thursday: 8:00am - 4:00pm', 'Friday: 8:00am - 2:00pm'],
            'service_area' => 'Southern Wisconsin, including Walworth County and select areas of Rock, Jefferson, Waukesha, and Kenosha counties.',
            'facebook' => '',
            'instagram' => '',
        ],
        'mission' => $mission,
        'our_story' => [
            'NDS Environmental Solutions has built its reputation on dependable service, strong relationships, and a commitment to the communities it serves.',
            'Founded in 2025 by Dustin Nieuwenhuis, NDS Environmental Solutions was built on a foundation of family legacy, industry experience, and hard work. Dustin has a deep family history in the waste management industry within Walworth County and had been active in the former family business until its sale in 2023. After a year and a half of reflection, Dustin felt the community was missing the local/neighborly waste collection service. What began as Nieuwenhuis Disposal Service has grown into NDS Environmental Solutions, a business dedicated to providing waste and disposal solutions for residential, commercial, and temporary and permanent construction dumpsters. Our team consists of long-time waste management professionals and family members who drive these values.',
            'Over the last two years, NDS/ES has continued to grow and evolve, expanding its services and capabilities to meet the changing needs of its customers. Through hard work, dedication, and a focus on doing the job right, the company has continued to build lasting relationships with the customers and communities it serves.',
            'As a family-owned/local business, NDS/ES takes pride in offering personal service and treating customers like neighbors rather than just account numbers. These values that helped establish the company continue to guide our approach to service, its relationships with customers, and its commitment to doing business the right way.',
            'Today, NDS Environmental Solutions continues to build on its history while looking toward the future. With a focus on reliable service, continued improvement, and strong community relationships, NDS/ES remains committed to finding practical disposal solutions and serving its customers for years to come.',
        ],
        // Mirrors the Next.js navigation (lib/cms/mock-data.ts). Used when no
        // Primary Navigation menu is assigned in Appearance > Menus.
        'nav' => [
            ['label' => 'Residential', 'url' => '/residential/', 'description' => 'Residential trash, recycling, schedules, and community pages.', 'children' => [
                ['label' => 'Residential Overview', 'url' => '/residential/', 'description' => 'Trash, recycling, bulk-item guidance, and FAQs.'],
                ['label' => 'Town of Walworth', 'url' => '/residential/town-of-walworth/', 'description' => 'Thursday collection and local notices.'],
                ['label' => 'Town of Delavan', 'url' => '/residential/town-of-delavan/', 'description' => 'Monday collection and local notices.'],
                ['label' => 'City of Delavan', 'url' => '/residential/city-of-delavan/', 'description' => 'Wednesday collection and local notices.'],
                ['label' => 'Town of Darien', 'url' => '/residential/town-of-darien/', 'description' => 'Tuesday collection.'],
                ['label' => 'Town of Sharon', 'url' => '/residential/town-of-sharon/', 'description' => 'Tuesday collection.'],
            ]],
            ['label' => 'Commercial', 'url' => '/commercial/', 'description' => 'Commercial trash, recycling, containers, and quote requests.', 'children' => [
                ['label' => 'Commercial Overview', 'url' => '/commercial/', 'description' => 'Permanent containers and business service plans.'],
                ['label' => 'Container Options', 'url' => '/commercial/#containers', 'description' => '96-gallon, 2-yard, 4-yard, 6-yard, and 8-yard options.'],
                ['label' => 'Commercial Recycling', 'url' => '/commercial/#recycling', 'description' => 'Cardboard, paper, plastic, aluminum, and mixed recycling.'],
            ]],
            ['label' => 'Dumpster Rentals', 'url' => '/dumpster-rentals/', 'description' => 'Temporary roll-off dumpsters and size guidance.', 'children' => [
                ['label' => 'Rental Overview', 'url' => '/dumpster-rentals/', 'description' => 'Temporary dumpsters for projects and cleanouts.'],
                ['label' => 'Dumpster Calculator', 'url' => '/dumpster-calculator/', 'description' => 'Choose the right roll-off size.'],
                ['label' => 'Accepted Materials', 'url' => '/dumpster-rentals/#materials', 'description' => 'What can and cannot go in a rental dumpster.'],
                ['label' => 'Special Events', 'url' => '/special-events/', 'description' => 'Containers and cleanup for community and private events.'],
            ]],
            ['label' => 'Resources', 'url' => '/whats-new/', 'description' => 'Updates, FAQs, contact, payment, and company information.', 'children' => [
                ['label' => "What's New", 'url' => '/whats-new/', 'description' => 'Alerts and service updates.'],
                ['label' => 'FAQs', 'url' => '/faqs/', 'description' => 'Dumpster, residential, and commercial questions.'],
                ['label' => 'About Us', 'url' => '/about/', 'description' => 'Our story and mission.'],
                ['label' => 'Contact Us', 'url' => '/contact/', 'description' => 'Quotes, estimates, and questions.'],
                ['label' => 'Make a Payment', 'url' => '/make-a-payment/', 'description' => 'Pay your account balance online by card or ACH.'],
            ]],
        ],
        'pages' => [
            'home' => [
                'eyebrow' => 'Southern Wisconsin Waste Management',
                'heading' => 'Reliable local trash, recycling, and dumpster service',
                'description' => 'A local waste management provider in Southern Wisconsin, delivering honest, reliable, and environmentally conscious solutions to individuals, businesses, and communities.',
                'image' => 'home-page-01-ea0d03de5d.png',
            ],
            'residential' => [
                'eyebrow' => 'Residential Communities Serviced',
                'heading' => 'Residential trash and recycling by community',
                'description' => 'Curbside and drive-up garbage and recycling services for Town of Walworth, Town of Delavan, City of Delavan, Town of Darien, and Town of Sharon.',
            ],
            'commercial' => [
                'eyebrow' => 'Commercial Waste Management',
                'heading' => 'Commercial waste and recycling tailored to your business',
                'description' => "At NDS Environmental Solutions, we understand the unique demands of businesses, which is why we offer the best commercial waste management solutions tailored to your needs. Our permanent dumpster services are ideal for businesses requiring ongoing waste and recycling solutions. For temporary needs, such as office cleanouts or construction projects, we provide roll-off dumpsters to keep your workspace efficient and organized. From small businesses to large industrial operations, our team delivers timely, cost-effective solutions designed to enhance productivity and reduce environmental impact. Whether you're in Walworth, Rock, Jefferson, Waukesha, or Kenosha County, NDS Environmental Solutions is your partner for professional waste solutions.",
            ],
            'dumpster-rentals' => [
                'eyebrow' => 'Dumpster Rentals',
                'heading' => 'Temporary roll-off dumpsters for cleanouts, remodels, and projects',
                'description' => 'Competitive pricing, prompt delivery, and responsive service for roll-off dumpster rentals in Walworth and surrounding counties.',
            ],
            'dumpster-calculator' => [
                'eyebrow' => 'Dumpster Calculator',
                'heading' => 'Find the right dumpster size before you call',
                'description' => 'Answer a few project questions and compare roll-off sizes before requesting a quote.',
            ],
            'faqs' => [
                'eyebrow' => 'FAQs',
                'heading' => 'Answers for homeowners, businesses, and project planners',
                'description' => 'Quick guidance for pickup schedules, recycling, dumpster rentals, and commercial service.',
            ],
            'about' => [
                'eyebrow' => 'About NDS',
                'heading' => 'Local waste service built around Wisconsin communities',
                'description' => 'Learn about the mission, history, and team behind NDS Environmental Solutions.',
            ],
            'contact' => [
                'eyebrow' => 'Contact NDS',
                'heading' => 'Request a quote, estimate, or service answer',
                'description' => 'Send a message and the NDS team will help route your request.',
            ],
            'make-a-payment' => [
                'eyebrow' => 'Make a Payment',
                'heading' => 'Pay your NDS account online',
                'description' => 'Pay by card or bank transfer using our secure PayEngine payment form.',
            ],
            'whats-new' => [
                'eyebrow' => 'What\'s New',
                'heading' => 'Service alerts, updates, and local notices',
                'description' => 'Weather delays, holiday updates, route notices, and announcements will appear here.',
            ],
            'special-events' => [
                'eyebrow' => 'Special Events',
                'heading' => 'Temporary waste service for events and gatherings',
                'description' => 'Plan containers, collection support, setup timing, and cleanup needs for community events, corporate events, weddings, and temporary service needs.',
            ],
            'privacy-policy' => [
                'eyebrow' => 'Legal',
                'heading' => 'Privacy Policy',
                'description' => 'How NDS Environmental Solutions handles information submitted through this website.',
            ],
            'terms' => [
                'eyebrow' => 'Legal',
                'heading' => 'Terms and content sources',
                'description' => 'Terms of use for this website, and attribution for its content and images.',
            ],
        ],
        // These previously pointed at stock photos -- a rusty unbranded
        // dumpster, and (worse) a competitor's roll-off container with
        // their own logo and phone number clearly visible. Replaced with
        // brand-styled icon cards (see ndses_render_service_cards()) so
        // the homepage doesn't advertise another waste company.
        'services' => [
            ['title' => 'Residential Trash & Recycling', 'body' => 'Curbside and drive-up garbage and recycling services depending on your community.', 'url' => '/residential/', 'icon' => 'residential'],
            ['title' => 'Commercial Trash & Recycling', 'body' => 'Permanent trash and recycling dumpsters with collection schedules matched to your business.', 'url' => '/commercial/', 'icon' => 'commercial'],
            ['title' => 'Dumpster Rentals', 'body' => 'Temporary roll-off dumpsters for renovations, construction debris, cleanouts, and events.', 'url' => '/dumpster-rentals/', 'icon' => 'rolloff'],
        ],
        'communities' => [
            'town-of-walworth' => array_merge(ndses_community_seed('Town of Walworth', 'Town', 'Thursday', 'Weekly', 'Every Other Week (see the service calendar below)', 'We will collect up to the equivalent of four 32-gallon cans each week for both trash and recycle.', $mission, $accepted_recycling, $recycling_not_accepted, $recycling_never, $bulk_accepted, $bulk_not), [
                'documents' => [
                    ['title' => '2026 Collection Schedule Calendar', 'url' => 'https://ndses.flywheelsites.com/wp-content/uploads/2026/09/walworth-1.webp'],
                    ['title' => '2026 Service Schedule Calendar', 'url' => 'https://ndses.flywheelsites.com/wp-content/uploads/2026/09/walworth-2.webp'],
                    ['title' => 'Resident Flyer - Welcome & Bulk Item Guidelines', 'url' => 'https://ndses.flywheelsites.com/wp-content/uploads/2026/09/walworth-3.webp'],
                    ['title' => 'Trash & Recycling Guide (Guidelines & Service Map)', 'url' => 'https://ndses.flywheelsites.com/wp-content/uploads/2026/09/walworth-4.webp'],
                ],
            ]),
            'town-of-delavan' => array_merge(ndses_community_seed('Town of Delavan', 'Town', 'Monday', 'Weekly, Every Other Week, Monthly', 'Weekly, Every Other Week, Monthly', 'We will collect up to the equivalent of three 32-gallon cans each week for both trash and recycle.', $mission, $accepted_recycling, $recycling_not_accepted, $recycling_never, $bulk_accepted, $bulk_not), [
                'documents' => [
                    ['title' => '2026 Service Schedule Calendar', 'url' => 'https://ndses.flywheelsites.com/wp-content/uploads/2026/09/nds-service-schedule-2026.png'],
                ],
            ]),
            'city-of-delavan' => array_merge(ndses_community_seed('City of Delavan', 'City', 'Wednesday', 'Weekly, Every Other Week, Monthly', 'Weekly, Every Other Week, Monthly', 'We will collect up to the equivalent of three 32-gallon cans each week for both trash and recycle.', $mission, $accepted_recycling, $recycling_not_accepted, $recycling_never, $bulk_accepted, $bulk_not), [
                'documents' => [
                    ['title' => '2026 Service Schedule Calendar', 'url' => 'https://ndses.flywheelsites.com/wp-content/uploads/2026/09/nds-service-schedule-2026.png'],
                ],
            ]),
            'town-of-darien' => array_merge(ndses_community_seed('Town of Darien', 'Town', 'Tuesday', 'Weekly, Every Other Week, Monthly', 'Weekly, Every Other Week, Monthly', 'We will collect up to the equivalent of three 32-gallon cans each week for both trash and recycle.', $mission, $accepted_recycling, $recycling_not_accepted, $recycling_never, $bulk_accepted, $bulk_not), [
                'documents' => [
                    ['title' => '2026 Service Schedule Calendar', 'url' => 'https://ndses.flywheelsites.com/wp-content/uploads/2026/09/nds-service-schedule-2026.png'],
                ],
            ]),
            'town-of-sharon' => array_merge(ndses_community_seed('Town of Sharon', 'Town', 'Tuesday', 'Weekly, Every Other Week, Monthly', 'Weekly, Every Other Week, Monthly', 'We will collect up to the equivalent of three 32-gallon cans each week for both trash and recycle.', $mission, $accepted_recycling, $recycling_not_accepted, $recycling_never, $bulk_accepted, $bulk_not), [
                'documents' => [
                    ['title' => '2026 Service Schedule Calendar', 'url' => 'https://ndses.flywheelsites.com/wp-content/uploads/2026/09/nds-service-schedule-2026.png'],
                ],
            ]),
        ],
        'dumpsters' => [
            ['name' => '10 Yard Dumpster', 'slug' => '10-yard', 'capacity' => '8-10 pickup truck loads', 'dimensions' => "11' L x 8' W x 3.5' H", 'uses' => ['Small remodeling projects', 'Home cleanouts', 'Concrete/heavy materials'], 'included' => $included, 'image' => 'dumpster-10-yard.png'],
            ['name' => '12 Yard Dumpster', 'slug' => '12-yard', 'capacity' => '11-14 pickup truck loads', 'dimensions' => "11' L x 8' W x 4' H", 'uses' => ['Medium home renovations', 'Estate cleanouts', 'Deck removals', 'Roofing projects'], 'included' => $included, 'image' => 'dumpster-12-yard.png'],
            ['name' => '15 Yard Dumpster', 'slug' => '15-yard', 'capacity' => '14-16 pickup truck loads', 'dimensions' => "15' L x 8' W x 4.5' H", 'uses' => ['Kitchen remodels', 'Multi-room renovations', 'Moderate construction debris'], 'included' => $included, 'image' => 'dumpster-15-yard.png'],
            ['name' => '20 Yard Dumpster', 'slug' => '20-yard', 'capacity' => '17-20 pickup truck loads', 'dimensions' => "22' L x 8' W x 4.5' H", 'uses' => ['Large home renovations', 'Whole-house cleanouts', 'Construction debris'], 'included' => $included, 'image' => 'dumpster-20-yard.png'],
            ['name' => '30 Yard Dumpster', 'slug' => '30-yard', 'capacity' => '20-26 pickup truck loads', 'dimensions' => "22' L x 8' W x 6' H", 'uses' => ['Major construction projects', 'Large demolition projects', 'Large-scale remodeling projects'], 'included' => $included, 'image' => 'dumpster-30-yard.png'],
        ],
        'commercial_containers' => [
            ['name' => '96 Gallon', 'body' => 'Compact container for lower-volume recurring trash or recycling service.', 'ideal' => ['Small offices', 'Low-volume retail', 'Churches']],
            ['name' => '2 Yard', 'body' => 'Small rear-load dumpster for businesses with steady weekly disposal needs.', 'dimensions' => '66" L x 38" W x 48" H', 'capacity' => '1-2 pickup truck loads', 'image' => 'dumpster-2-yard.webp', 'ideal' => ['Small offices & businesses', '1-4 unit properties']],
            ['name' => '4 Yard', 'body' => 'Mid-size commercial container for growing waste streams.', 'dimensions' => '87" L x 66" W x 48" H', 'capacity' => '3-4 pickup truck loads', 'image' => 'dumpster-4-yard.png', 'ideal' => ['Restaurants', 'Small apartment complexes', 'Medium retail stores']],
            ['name' => '6 Yard', 'body' => 'Larger container for busy sites that need more capacity.', 'dimensions' => '111" L x 66" W x 53" H', 'capacity' => '5-6 pickup truck loads', 'image' => 'dumpster-6-yard.png', 'ideal' => ['Large restaurants', 'Large apartment complexes', 'Schools', 'Small remodeling projects', 'Areas with space constraints']],
            ['name' => '8 Yard', 'body' => 'High-capacity permanent dumpster for demanding commercial accounts.', 'dimensions' => '116" L x 66" W x 73" H', 'capacity' => '7-8 pickup truck loads', 'image' => 'dumpster-8-yard.png', 'ideal' => ['Manufacturing', 'Schools', 'Large retail']],
        ],
        'faqs' => ndses_faq_seed(),
        'accepted_materials' => $rolloff_accepted,
        'prohibited_materials' => $rolloff_prohibited,
        'service_areas' => [
            // Town coordinates match the `places` table in scripts/build-service-area-map.cjs.
            ['name' => 'Town of Walworth', 'county' => 'Walworth County', 'types' => ['Residential', 'Commercial', 'Roll-off'], 'lat' => 42.531, 'lon' => -88.599],
            ['name' => 'Town of Delavan', 'county' => 'Walworth County', 'types' => ['Residential', 'Commercial', 'Roll-off'], 'lat' => 42.588, 'lon' => -88.572],
            ['name' => 'City of Delavan', 'county' => 'Walworth County', 'types' => ['Residential', 'Commercial', 'Roll-off'], 'lat' => 42.6331, 'lon' => -88.6437],
            ['name' => 'Town of Darien', 'county' => 'Walworth County', 'types' => ['Residential', 'Commercial', 'Roll-off'], 'lat' => 42.601, 'lon' => -88.708],
            ['name' => 'Town of Sharon', 'county' => 'Walworth County', 'types' => ['Residential', 'Commercial', 'Roll-off'], 'lat' => 42.502, 'lon' => -88.729],
            ['name' => 'Rock County', 'county' => 'Rock County', 'types' => ['Commercial', 'Roll-off'], 'lat' => null, 'lon' => null],
            ['name' => 'Jefferson County', 'county' => 'Jefferson County', 'types' => ['Commercial', 'Roll-off'], 'lat' => null, 'lon' => null],
            ['name' => 'Waukesha County', 'county' => 'Waukesha County', 'types' => ['Commercial', 'Roll-off'], 'lat' => null, 'lon' => null],
            ['name' => 'Kenosha County', 'county' => 'Kenosha County', 'types' => ['Commercial', 'Roll-off'], 'lat' => null, 'lon' => null],
        ],
        'notices' => [
            ['title' => 'Service alerts and updates', 'type' => 'Announcement', 'date' => '2026-08-10', 'body' => 'No urgent service alerts are active. Weather delays, holiday schedule changes, closures, and route updates will be posted here.'],
        ],
    ];

    return $data;
}

function ndses_community_seed(string $name, string $type, string $day, string $trash, string $recycle, string $cans, string $mission, array $accepted, array $not, array $never, array $bulk_accepted, array $bulk_not): array
{
    return [
        'name' => $name,
        'type' => $type,
        'service_day' => $day,
        'trash_schedule' => $trash,
        'recycling_schedule' => $recycle,
        'description' => $mission,
        'guidelines' => ['Trash is collected weekly.', 'Residents are required to provide their own trash cans, not exceeding 60 pounds.', $cans, 'Please bag all trash and place it at roadside no later than 5:30am.'],
        'accepted_recycling' => $accepted,
        'recycling_not_accepted' => $not,
        'recycling_never_accepted' => $never,
        'bulk_accepted' => $bulk_accepted,
        'bulk_not_accepted' => $bulk_not,
        'bulk_policy' => 'This is not an inclusive list. Please contact us to schedule a bulk pickup. Electronics and certain items can be collected by contacting our office; a fee may apply.',
        'documents' => [],
    ];
}

function ndses_faq_seed(): array
{
    return [
        'Dumpster Rentals' => [
            ['How do I choose the right dumpster size?', 'The best dumpster size depends on your project. Smaller dumpsters are great for garage cleanouts and minor renovations, while larger dumpsters are ideal for construction, demolition, and whole-home cleanouts. If you are unsure, our team is happy to help you choose the right size.'],
            ['How long can I keep the dumpster?', 'The rental period is 15 days. If you need accommodations to this, please let us know and we will work with you.'],
            ['Do I need to be home for delivery?', 'No. As long as you have provided clear placement instructions and the delivery area is accessible, you do not need to be present.'],
            ['How full can I load the dumpster?', 'Materials should never extend above the top edge of the dumpster. Overfilled dumpsters may require materials to be removed before pickup for safety reasons.'],
            ['How soon can my dumpster be delivered?', 'We strive to provide prompt service and can often deliver within one to two business days, depending on availability and your location. Contact us for current scheduling.'],
            ['How much does a dumpster rental cost?', 'Pricing depends on several factors, including dumpster size, rental length, location, and the type of debris being disposed of. Contact us for a free, no-obligation quote.'],
            ['What happens when I am finished with the dumpster?', 'Simply call us when you are ready, or schedule your pickup in advance. We will come collect the dumpster and properly dispose of the contents.'],
        ],
        'Residential Trash & Recycling' => [
            ['When is my trash and recycling pickup?', 'Town of Delavan: Monday. Town of Sharon/Darien: Tuesday. City of Delavan: Wednesday. Town of Walworth: Thursday.'],
            ['What time should I place my bins at the curb?', 'Please have your cart at the curb by 5:30 a.m. on your scheduled collection day.'],
            ['What can I put in my trash can?', 'Household waste such as food waste, packaging, paper products, clothing, and non-hazardous household items.'],
            ['What can I recycle?', 'Recycling items include cardboard, paper, aluminum, steel cans, plastic bottles and jugs, and glass bottles and jars.'],
            ['Do I need to bag my recyclables?', 'Yes, please place all recyclables in clear plastic recyclable bags.'],
            ['Do you offer bulk item pickups?', 'Yes. If you are a current residential customer, you get two free bulk item pickups per month for items such as furniture or mattresses. Contact us ahead for scheduling and guidelines.'],
            ['What happens if a holiday falls on my pickup day?', 'Please refer to our service calendar to see holidays that will result in a one-day delay in service.'],
            ['Does NDS supply the cans or do I?', 'NDS Environmental Solutions does not supply residential garbage cans. You may have up to three 32-gallon bins for both trash and recycle or one 96-gallon can for each. Anything beyond this will be considered extra and is subject to additional fees.'],
        ],
        'Commercial Trash & Recycling' => [
            ['What size dumpsters do you offer?', 'We offer 96-gallon containers, 2-yard dumpsters, 4-yard dumpsters, 6-yard dumpsters, and 8-yard dumpsters. Our team can help determine the best size for your business.'],
            ['How often can my dumpster be serviced?', 'We offer flexible schedules ranging from once per week to multiple collections per week depending on your needs.'],
            ['Can I change my dumpster size or frequency?', 'Absolutely. As your business grows or your needs change, we can adjust your container size or collection schedule. Give us a call at 262-233-6131 to arrange any changes.'],
            ['Do you offer temporary dumpsters for businesses?', 'Yes. In addition to regular commercial service, we offer temporary roll-off dumpsters for construction projects, renovations, office cleanouts, and special events.'],
            ['What happens if my dumpster is overflowing?', 'If your dumpster is consistently full before pickup, contact us about increasing your service frequency or upgrading to a larger container.'],
            ['How do I request service or get a quote?', 'Call us at 262-233-6131 or fill out our online quote request form. We will help you choose the right service and provide a customized quote.'],
        ],
    ];
}

