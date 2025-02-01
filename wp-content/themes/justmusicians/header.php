<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package JustMusicians
 */


?>
<!doctype html>
<html <?php language_attributes(); ?> class="<?php echo $scroll_pt; ?>">
<head>


	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/lib/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/lib/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/lib/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/lib/images/favicon/site.webmanifest">
  <link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/lib/images/favicon/safari-pinned-tab.svg" color="#989572">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">

  
	<?php wp_head(); ?>

</head>

    

    <body <?php body_class('flex flex-col min-h-screen relative'); ?> >
      <!-- Setting a fixed height allows us to position the popups on mobile -->
    <header class="bg-brown-light-3 md:px-6 py-2 sticky top-0 z-30 h-28 md:h-auto">
      <div class="container flex flex-row md:grid grid-cols-12 gap-2 md:gap-4 lg:gap-12">

        <div class="w-32 md:w-auto col-span-2 relative">
          <a class="w-full absolute top-0 left-0" href="<?php echo get_home_url(); ?>">
            <img src="<?php echo get_template_directory_uri() . '/lib/images/logos/logo.svg'; ?>" />
          </a>
        </div>
        
        <div class="col-span-10 flex flex-col-reverse max-md:grow md:flex-row md:items-center items-end gap-2 md:gap-12 justify-between">
          
          <div class="border bg-white text-14 pr-1 rounded-sm border-black/20 grow w-full flex items-stretch">
            <div id="search" class="grow relative px-1 py-1">
              <input class="w-full h-full py-2 px-3" type="text" placeholder="Search" />
                <?php echo get_template_part('template-parts/search/search-state-1', '', array()); ?>
                <?php echo get_template_part('template-parts/search/search-state-2', '', array()); ?>
            </div>  
            <div class="hidden md:block w-px bg-black/20 my-2"></div>
            <div class="hidden md:block grow relative px-1 py-1 flex items-center">
              <img class="h-4 absolute top-3 left-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
              <input class="w-full h-full py-2 pr-3 pl-5" type="text" placeholder="Austin, Texas" />
            </div>
            <button class="flex cursor-pointer items-center px-2 py-2 hover:scale-105">
              <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
            </button>
          </div>

          <div class="font-sun-motter text-18 items-center gap-6 hidden lg:flex shrink-0">
            <span class="flex items-center gap-2 relative group">
              <a href="#">Live Music</a>
              <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
              <!-- Dropdown menu -->
              <div class="absolute top-full w-48 left-0 px-4 py-4 bg-white hidden font-regular font-sans text-16 group-hover:flex flex-col shadow-md rounded-sm z-10">
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="#">
                  <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
                  Bands
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="#">
                    <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-person.svg'; ?>" />
                    Solo/Duo
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="#">
                    <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-djs.svg'; ?>" />
                    DJs
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm" href="#">
                    <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-wedding.svg'; ?>" />
                    Wedding Music
                </a>
              </div>
            </span>
            <a href="#">Reviews</a>
          </div>
        
          <div class="flex items-center gap-2 shrink-0">
            <div class="flex items-center mr-4">
              <div data-trigger="mobile-menu" class="hamburger block lg:hidden h-8 w-8 cursor-pointer relative">
                <div aria-hidden="true" class="w-8 h-1 bg-black block absolute top-1/2 -translate-y-2.5 transform transition duration-500 ease-in-out"></div>
                <div aria-hidden="true" class="w-8 h-1 bg-black block absolute top-1/2 transform transition duration-500 ease-in-out"></div>
                <div aria-hidden="true" class="w-8 h-1 bg-black block absolute top-1/2 translate-y-2.5 transform transition duration-500 ease-in-out"></div>
              </div>
            </div>
            <button class="border-2 font-sun-motter text-16 px-3 md:px-5 py-2 md:py-3">Log In</button>
            <button class="bg-navy border-2 border-black text-white shadow-black-offset hover:bg-yellow hover:text-black font-sun-motter text-16 px-3 md:px-5 py-2 md:py-3">Sign Up</button>
          </div>

        </div>

      </div>
    </header>

    <div id="tooltip-sort" class="tooltip text-white bg-black px-4 py-3 text-14 rounded sm absolute z-20 w-64 hidden">
      Learn more about the default Just Musicians search algorithm <a class="text-yellow underline" href="#">here</a>.
    </div>

    <?php echo get_template_part('template-parts/filters/popup', '', array()); ?>



    <?php wp_body_open(); ?>
    <?php echo get_template_part('template-parts/global/mobile-menu', '', array()); ?>
    <div id="page" class="flex flex-col grow z-0">
		<div id="content" class="grow flex flex-col relative">
