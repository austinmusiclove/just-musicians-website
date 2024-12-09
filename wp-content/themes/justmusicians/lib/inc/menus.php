<?php

// Register menus
register_nav_menus( array(
  'header_menu_desktop' => esc_html__( 'Header', 'BarnRaiser' ),
  'footer_menu' => esc_html__( 'Footer', 'BarnRaiser' ),
) );


// Footer menu classes
class Footer_Menu_Walker extends Walker_Nav_Menu {
function start_el( &$output, $data_object, $depth = 0, $args = NULL, $current_object_id = 0) {

    if ($depth == 0 ):
      if ( empty( $data_object->url ) || '#' === $data_object->url ) :
          $output .= '<li class="mb-2 mt-5 pt-5 text-28 border-t font-caslon border-artichoke-100">';
          $output .= apply_filters( 'the_title', $data_object->title, $data_object->ID );
          $output .= '</li>';
      else :
        $output .= '<li><a href="'.$data_object->url.'" target="'.$data_object->target.'" class="inline-block max-md:opacity-50 text-23 md:text-19">';
        $output .= apply_filters( 'the_title', $data_object->title, $data_object->ID );
        $output .= '</a></li>';
      endif;
      $output .= '</li>';
    return $output;

    else:
      $output .= '<li><a href="'.$data_object->url.'" target="'.$data_object->target.'" class="inline-block max-md:opacity-50 text-23 md:text-19 hover:underline">';
      $output .= apply_filters( 'the_title', $data_object->title, $data_object->ID );
      $output .= '</a></li>';
      return $output;

    endif;
  }
}
