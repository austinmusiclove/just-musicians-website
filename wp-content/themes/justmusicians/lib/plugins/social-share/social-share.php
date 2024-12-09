<?php
// https://wpvkp.com/add-social-media-sharing-buttons-to-wordpress-without-plugin/
//https://www.gavick.com/blog/wordpress-social-media-share-buttons-without-plugin



function br_social_buttons() {
    global $post;
    $permalink = get_permalink($post->ID);
    $title = get_the_title();

    // Setup styles
    $class = "bg-black w-10 h-10 rounded-full flex items-center justify-center cursor-pointer hover:scale-105";

    // Email content
    $subject = 'Barn Raiser: '.$title;
    $body = "I'd like to share this article I found at Barn Raiser: ".$permalink;


    $content = '<div class="social-buttons flex xl:flex-col items-center xl:space-y-2">

    <a class="'.$class.'" href="http://twitter.com/share?text='.$title.'&url='.$permalink.'"
        onclick="window.open(this.href, \'twitter-share\', \'width=550,height=400\');return false;">
        <img class="w-5 h-auto" alt="Share on Twitter" src="'.get_template_directory_uri().'/lib/images/icons/twitter.svg" />
    </a>

    <a class="'.$class.'" href="https://www.facebook.com/sharer/sharer.php?u='.$permalink.'"
         onclick="window.open(this.href, \'facebook-share\',\'width=580,height=296\');return false">
        <img class="w-5 h-auto" alt="Share on Facebook" src="'.get_template_directory_uri().'/lib/images/icons/facebook.svg" />
    </a>

    <a class="'.$class.'" href="mailto:?&subject='.$subject.'&body='.$body.'">
        <img class="w-5 h-auto" alt="Share via email" src="'.get_template_directory_uri().'/lib/images/icons/mail.svg" />
    </a>

    <div class="'.$class.'" onclick="window.print();return false;">
        <img class="w-5 h-auto" alt="Print" src="'.get_template_directory_uri().'/lib/images/icons/print.svg" />
    </div>


    </div>';

    return $content;
}
