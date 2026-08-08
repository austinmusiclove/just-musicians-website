<?php
/**
 * The FAQ template file
 *
 * @package JustMusicians
 */
get_header();
?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40">Frequently Asked Questions</h1>
        <p class="text-20 text-brown-dark-1 mt-4 max-w-2xl">Everything you need to know about hiring live music and getting hired on HireMusicians.com.</p>
    </div>
</header>

<?php
$faq_categories = [
    [
        'heading' => 'General & How It Works',
        'items' => [
            [
                'question' => 'What is HireMusicians.com?',
                'answer'   => 'HireMusicians.com is a free online marketplace for live musicians.',
            ],
            [
                'question' => 'Is HireMusicians really 100% free to use?',
                'answer'   => 'Yes. HireMusicians is completely free for both buyers and musicians. There are no subscription fees, service charges, or lead fees charged to musicians.',
            ],
            [
                'question' => 'Why is the platform free? What\'s the catch?',
                'answer'   => 'No catch. HireMusicians was built to connect live talent directly with organizers without taking a percentage of musicians\' hard-earned gig pay or charging hosts extra service fees. In the future, the platform intends to offer optional escrow services for people who prefer to pay the platform instead of musicians directly. However, escrow and the fees the come with it will be noncompulsary.',
            ],
            [
                'question' => 'How does HireMusicians work?',
                'answer'   => 'Musicians set up a listing to appear on the platform. Event hosts browse or search for musicians by location, genre, ensemble size and more. They can then contact musicians directly outside the platform or send them an inquiry in the platform. When a musician gets an inquiry, they will get an email notification to respond to the inquiry on the platform. The buyer can then evaluate their responses and initiate a chat with musicians on the platform to discuss the job further. Musicians do not gain access to buyer contact information unless it is directly shared with them by the buyer. When a musician is booked on HireMusicians.com, payment must be handled outside the platform.',
            ],
        ],
    ],
    [
        'heading' => 'For Event Hosts & Buyers',
        'items' => [
            [
                'question' => 'How do I contact and book a musician?',
                'answer'   => 'Search by your location, genre, and other filters on the home page. Click on musician listings to open thier individual pages to learn more. When you find someone you like you can use the Send Inquiry button to enter your event details and send them off to the musician for a response. You can then send that same inquiry to multiple musicians to collect many quotes or hire multiple musicians for the same event.',
            ],
            [
                'question' => 'How do payments work? Do I pay through the website?',
                'answer'   => 'Payment terms and methods are arranged directly between you and the performer. Currently, HireMusicians.com does not handle payments and does not take any discovery fee for using the platform.',
            ],
            [
                'question' => 'What should I include in my initial booking message to a musician?',
                'answer'   => 'To get a fast, accurate quote, include the date and time of your event, the venue and its address, expected performance length, your budget, and any special requests. If possible, include information on parking and proximity to the stage. Specify who will be providing the equipment and sound set up for the show whether it is the musicians or a third party.',
            ],
            [
                'question' => 'Can I create favorites lists of musicians?',
                'answer'   => 'Yes. You can save musicians to a favorites list or create your own custom lists to keep track of the acts you\'re interested in.',
            ],
            [
                'question' => 'Can I make a job posting to collect applicants?',
                'answer'   => 'Yes. Create an application and share the link on your website or social media. Musicians who want to play at your venue, festival, or event can apply directly. You can review every applicant right on HireMusicians.com. You can even collect quotes, draw, and availability from your applicants for your events.',
            ],
        ],
    ],
    [
        'heading' => 'For Musicians & Performers',
        'items' => [
            [
                'question' => 'Does HireMusicians.com take any commission from the gigs I land?',
                'answer'   => 'No. You keep 100% of every dollar you earn from gigs booked through the platform.',
            ],
            [
                'question' => 'Who can join HireMusicians.com?',
                'answer'   => 'Any musician, full band, DJ, or other musical act in the United States or Canada offering live music services can create a listing.',
            ],
            [
                'question' => 'How do I make my musician profile stand out in search results?',
                'answer'   => 'Complete as many aspects of your listing as possible especially media.',
            ],
            [
                'question' => 'How do I rank higher in the search results?',
                'answer'   => '<a href="/search-algorithm/">Read this page</a> to uncover the secrets of our search rank algorithm.',
            ],
            [
                'question' => 'How do leads and messages reach me?',
                'answer'   => 'When a buyer sends an inquiry to your listing, you will get an email notification. You can then login to your account to respond. You will not get any buyer contact information unless they share it directly with you. The platform does not make an attempt to stop buyers and musicians from sharing contact information.',
            ],
        ],
    ],
];

$faq_items = [];
foreach ($faq_categories as $category) {
    foreach ($category['items'] as $item) {
        $faq_items[] = $item;
    }
}

get_template_part('template-parts/global/schema/faq-schema', '', [
    'items' => $faq_items,
]);
?>

<div class="container max-w-3xl mx-auto py-8 md:py-12">

    <?php foreach ($faq_categories as $category) : ?>
        <section class="mb-10">
            <h2 class="font-sun-motter text-25 mb-4"><?php echo esc_html($category['heading']); ?></h2>
            <div class="space-y-4">

                <?php foreach ($category['items'] as $item) : ?>
                    <div class="border border-black/20 bg-white" x-data="{ open: false }">
                        <button type="button"
                            class="w-full flex items-center justify-between gap-4 cursor-pointer px-4 py-4 font-bold text-16 text-left hover:bg-yellow-light transition-colors"
                            :class="{ 'bg-yellow-light': open }"
                            x-on:click="open = !open"
                            :aria-expanded="open">
                            <span><?php echo esc_html($item['question']); ?></span>
                            <span class="shrink-0 text-25 leading-none transition-transform" :class="open && 'rotate-45'">+</span>
                        </button>
                        <div x-show="open" x-collapse x-cloak>
                            <div class="px-4 pt-5 pb-5 text-16 leading-relaxed text-brown-dark-1 [&_a]:underline [&_a]:text-navy [&_a]:hover:text-yellow"><?php echo wp_kses($item['answer'], [
                                'a' => [
                                    'href'   => true,
                                    'target' => true,
                                    'rel'    => true,
                                    'title'  => true,
                                ],
                            ]); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </section>
    <?php endforeach; ?>

</div>

<?php
get_footer();
