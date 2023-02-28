<?php 

/**
 * Plugin Name:     Display subcategories of all parent terms
 * Description:     Provides a shortcode [display-subcategories] to display a grid of subcategories of all parent terms of a custom taxonomy, ordered by a custom field.
 * Version:         1.0.0
 * Author:          Inna Zhdanova
 */ 

/**
 * Add Shortcode
 */

if ( !function_exists( 'display_subcategories_shortcode' ) ) {

    function display_subcategories_shortcode() {

        $content = '';
        
        $args = [
            'taxonomy'      => 'industry',
            'parent'	    => 0,
            'hide_empty'    => false,
        ];
        $terms = get_terms( $args );

        if( $terms ){
            
            foreach( $terms as $term ){
                
                $content .= '<h2>'.$term->name.'</h2>';
                $content .= '<p>'.$term->description.'</p>';

                $child_args = array(
                    'taxonomy' => 'industry',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    'hide_empty' => false,
                    'hierarchical' => false,
                    'parent' => $term->term_id,
                    'meta_query' => [[
                        'key' => 'industry_order',
                        'type' => 'NUMERIC',
                    ]],
                );
                
                /* Without sorting
                
                $child_args = [
                    'taxonomy'      => 'industry',
                    'parent'	    => $term->term_id,
                    'hide_empty'    => false,
                ];*/

                $child_terms = get_terms( $child_args );
            
                $content .=  '<div class="subcategories-block">';

                foreach( $child_terms as $child_term ){

                    $image_id = get_term_meta( $child_term->term_id, 'image_id', true );
                    
                    $content .=  '<div>';

                    $content .= '<div class="category-img"><div class="overflow">';
                    $content .= wp_get_attachment_image( $image_id, ['200', '100'] );
                    $content .= '</div></div>';

                    $content .= '<h4>'.$child_term->name.'</h4>';
                    
                    $content .=  '</div>';
                    
                }
                
                $content .=  '</div>';
                
            }
        } 

        return $content;

    }
}
add_shortcode( 'display-subcategories', 'display_subcategories_shortcode' );


/**
 * Register style
 */

if ( !function_exists( 'display_subcategories_style_scripts' ) ) {
    function display_subcategories_style_scripts () {
        wp_enqueue_style( 'display_subcategories_style', plugins_url('assets/style.css', __FILE__ ) );
    }
}
add_action( 'wp_enqueue_scripts', 'display_subcategories_style_scripts' );
