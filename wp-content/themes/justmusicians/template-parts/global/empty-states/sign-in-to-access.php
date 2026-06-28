<?php
/**
 * Template part for the "Sign in to..." login prompt
 * with cactus/tumbleweed decoration
 *
 * @package JustMusicians
 */

$skip_x_init  = $args['skip_x_init'] ?? false;
$message      = $args['message'] ?? '';
$full_message = 'Sign in to ' . $message;

?>
<?php if (!$skip_x_init) { ?>
<span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = '<?php echo $full_message; ?>';"></span>
<?php } ?>

<div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

    <div class="pb-32 relative z-10">
        <span class="text-18 sm:text-22 block text-center mb-4"><?php echo $full_message; ?></span>
        <button x-on:click="showLoginModal = true;" type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Sign In</button>
    </div>

    <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
    <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

</div>
