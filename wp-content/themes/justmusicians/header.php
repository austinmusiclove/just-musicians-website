<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BarnRaiser
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
    <header class="bg-brown-light-3 px-6 py-2 sticky top-0 z-10">
      <div class="container grid grid-cols-12 gap-12">

        <div class="col-span-2 relative">
          <a class="w-full absolute top-0 left-0" href="#">
            <img src="<?php echo get_template_directory_uri() . '/lib/images/logos/logo.svg'; ?>" />
          </a>
        </div>
        
        <div class="col-span-10 flex items-center gap-12 justify-between">
          
          <div class="border bg-white text-14 pr-1 rounded-sm border-black/20 grow flex items-stretch">
            <div id="search" class="grow relative px-1 py-1">
              <input class="w-full h-full py-2 px-3" type="text" placeholder="Search" />
              <div id="search-state-1" class="absolute top-full left-0 w-full px-4 py-4 bg-white hidden flex flex-col shadow-md rounded-sm">
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#">
                  <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
                  Bands
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#">
                  <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-person.svg'; ?>" />
                  Solo/Duo
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#">
                  <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-djs.svg'; ?>" />
                  DJs
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#">
                  <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-wedding.svg'; ?>" />
                  Wedding Music
                </a>
              </div>
            </div>  
            <div class="w-px bg-black/20 my-2"></div>
            <div class="grow relative px-1 py-1 flex items-center">
              <input class="w-full h-full py-2 px-3" type="text" placeholder="Austin, Texas" />
            </div>
            <button class="flex cursor-pointer items-center px-2 py-2 hover:scale-105">
              <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
            </button>
          </div>

          <div class="font-sun-motter text-18 flex items-center gap-6">
            <span class="flex items-center gap-2">
              <a href="#">Live Music</a>
              <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
            </span>
            <a href="#">Reviews</a>
          </div>
        
          <div class="flex items-center gap-2">
            <button class="border-2 font-sun-motter text-16 px-5 py-3">Log In</button>
            <button class="bg-navy border-2 border-black text-white shadow-black-offset hover:bg-yellow hover:text-black font-sun-motter text-16 px-5 py-3">Sign Up</button>
          </div>

        </div>

      </div>
    </header>

    <div id="tooltip-sort" class="tooltip text-white bg-black px-4 py-3 text-14 rounded sm absolute z-20 w-64 hidden">
      Learn more about the default Just Musicians search algorithm <a class="text-yellow underline" href="#">here</a>.
    </div>

    <?php echo get_template_part('template-parts/filters/popup', '', array()); ?>



    <?php wp_body_open(); ?>
    <div id="page" class="flex flex-col grow z-0">
		<div id="content" class="grow flex flex-col relative">
