<?php
/**
 * The About template file
 *
 * @package JustMusicians
 */
get_header();
?>

<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40"><?php the_title(); ?></h1>
        <p class="text-20 mt-4 max-w-2xl">Hire Musicians is a free online marketplace for live musicians.</p>
    </div>
</header>

<?php
get_template_part('template-parts/global/schema/about-schema');
?>

<div class="container max-w-3xl mx-auto py-8 md:py-12">
    <section class="mb-10">
        <h2 class="font-sun-motter text-25 mb-4">What is Hire Musicians?</h2>
        <p class="text-16 leading-relaxed mb-4">Hire Musicians is a free online marketplace for live musicians. At its core, Hire Musicians is a musician directory. There are no paywalls and no fees for connecting with musicians.</p>
    </section>

    <section class="mb-10">
        <h2 class="font-sun-motter text-25 mb-6">Philosophy</h2>
        <div class="mb-6">
            <h3 class="font-bold text-18 mb-2">The Mission</h3>
            <p class="text-16 leading-relaxed">Our mission is to help people Hire More Musicians. We are building the go-to marketplace for live musicians in the United States and Canada. Hire Musicians strives to have the highest inventory of musicians for buyers to choose from, and we put our money where our mouth is. There are no compulsory platform fees on Hire Musicians. That way, the platform is accessible to everyone.</p>
        </div>
        <div class="mb-6">
            <h3 class="font-bold text-18 mb-2">Merit Over Popularity</h3>
            <p class="text-16 leading-relaxed">Talent should rise to the top. We put the music and media front and center on every listing instead of follower counts and listener counts. There's no paying to climb the rankings, no lead fees, and no pay-per-click. Every musician competes on an equal playing field.</p>
        </div>
        <div>
            <h3 class="font-bold text-18 mb-2">No Gatekeeping</h3>
            <p class="text-16 leading-relaxed">We stay out of the way of real relationships. If a buyer and a musician want to share contact information directly on the platform, that's their call. We never block it. There are no compulsory fees to be listed and no fees to read inquiries from buyers. We're here to support musicians as they develop and advance their careers, not to make a quick buck off them as they get started.</p>
        </div>
    </section>

    <section class="mb-10">
        <h2 class="font-sun-motter text-25 mb-6">About the Founder</h2>
        <div class="flex flex-col sm:flex-row gap-6">
            <img class="w-full sm:w-56 h-auto object-cover border border-black/20 shrink-0" src="https://hiremusicians.com/wp-content/uploads/2026/08/headshot-large.jpg" alt="John Filippone, founder of Hire Musicians" />
            <div>
                <p class="text-16 leading-relaxed mb-4">Hire Musicians was founded by John Filippone, a musician and software engineer based in Austin, Texas. John spent 5 years working as a professional software engineer before going full-time with Austin Music Love, the music promotion company he founded in Austin, Texas. Over the next 5 years, John promoted local shows and released his own music under the name Johnny Fantana.</p>
                <p class="text-16 leading-relaxed mb-4">Throughout his years of playing, promoting, and booking across the Austin scene, John noticed one core problem that inspired him to start Hire Musicians. Musicians were sending hundreds of emails per week to get gigs, and talent buyers were spending countless hours reading, organizing, and responding to those emails. It was a ton of work on both sides that could be solved by an elegant solution, a directory. He saw the opportunity to solve the problem and decided to be the one to build it. The relationships he had developed as a music promoter, with artists and talent buyers in the local scene, became the foundation he leveraged to get the platform started.</p>
            </div>
        </div>
    </section>
</div>

<?php
get_footer();
