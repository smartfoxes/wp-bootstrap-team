<?php

add_shortcode( 'team' , "wp_bootstrap_team");

function wp_bootstrap_team($atts, $content="") {
    $class = isset($atts['class']) ? $atts['class'] : null;
    $photos = isset($atts['photos']) ? $atts['photos'] : true;

    $args = array(
    	'type'                     => 'team_member',
    	'orderby'                  => 'date',
    	'order'                    => 'ASC',
    	'hide_empty'               => 1,
    	'hierarchical'             => 0,
    	'taxonomy'                 => 'team_department'
    ); 
    
    $categories = get_categories( $args );
    $content .= '<a name="spotlight"></a>';
    if($categories) {
        $content .=  '<ul id="menu-team-departments" class="nav subnav nav-team-departments nav-pills">';    
        //$content .= '<li class="menu-all active"><a href="#" data-department="all">All</a></li>';
        
        foreach($categories as $category) {
            if(!$defaultCategory) {
                $defaultCategory = $category->term_id;
            }
            $content .= '<li class="menu-'.$category->slug.' '.($defaultCategory == $category->term_id ? "active":"").'"><a data-department="'.$category->term_id.'" href="'.'#'.'">'.$category->name.'</a></li>';
        }
        $content .= "</ul>";
    }

    $content .= '<div class="team-members-spot"><div class="team-members-details"></div><a class="team-members-spot-close" href="#">CLOSE X</a></div>';

    $content .= "<div class='team-members-wrap'><div class='team-members'>";
    $args = array(
    	'order' => 'ASC',
    	'orderby' => 'date',
    	'numberposts' => 999,
    	'offset' => 0,
    	'post_type' => 'team_member',
    	'post_status' => 'publish'
    ); 
    $pages = get_posts($args); 
    
    foreach($pages as $page) {
        $post_categories = wp_get_object_terms( $page->ID, 'team_department');
        $cats = array();
        $department = "";        
        $isVisible = false;
        foreach($post_categories as $c){
        	$cats[] = 'team-department-'.$c->term_id;
        	$department = $c->name;
        	if($defaultCategory == $c->term_id) {
        	    $isVisible = true;
        	}
        }
        setup_postdata( $page );
        $content .= '<div class="team-member '.join(' ',$cats).'" '.($isVisible ? "":"style='display:none;'").'>';
        
        $title = get_post_meta( $page->ID, '_wp_bootstrap_team_title', true );      
        $phone = get_post_meta( $page->ID, '_wp_bootstrap_team_phone', true ); 
        $email = get_post_meta( $page->ID, '_wp_bootstrap_team_email', true );     
        $linkedin = get_post_meta( $page->ID, '_wp_bootstrap_team_linkedin', true );     
        
        if($photos ) {
            $content .= '<div class="team-member-excerpt"><h3 class="team-member-name">'.$page->post_title.'</h3>';
        
            if($title) {
                $content .= '<h3 class="team-member-title">'.$title.'</h3>';
            }
            $content .= '<a href="#spotlight" class="team-member-more">click for more &gt;</a></div>';
            
            $content .= '<div class="team-member-photo">' . get_the_post_thumbnail( $page->ID, 'team-member-photo' ) . "</div>";        
        } else {
            $content .= '<div class="team-member-excerpt">';
            $content .= '<a href="#spotlight" class="team-member-more">Meet '.preg_replace('/ .*$/', '', trim($page->post_title)).' &gt;</a>';
            /*if($department) {
                $content .= '<h4 class="team-member-department">'.$department.'</h4>';
            }*/
            $content .= '</div>';
            
            $content .= '<div class="team-member-photo">';
            $content .= '<h3 class="team-member-name">'.$page->post_title.'</h3>';
            if($title) {
                $content .= '<p class="team-member-title">'.$title.'</p>';
            }
            /*if($phone) {
                $content .= '<p class="team-member-phone">T: <span class="team-member-phone-number">'.$phone.'</span></p>';
            }
            $content .= '<p class="team-member-links">';
            $content .= '<a href="#" class="team-member-aboutme">About Me</a>';
            
            if($email) {
                $content .= '<a href="mailto:'.$email.'" class="team-member-email">Email</a>';
            }
            if($linkedin) {
                $content .= '<a target="_blank" href="'.$linkedin.'" class="team-member-linkedin">LinkedIn</a>';
            }
            
            $content .= '</p>';*/
            
            /*if($department) {
                $content .= '<h4 class="team-member-department">'.$department.'</h4>';
            }*/
            $content .= "</div>";                                    
        }
        $content .= '<div class="team-member-spotlight"><div class="team-member-info">';
        $content .= '<h3 class="team-member-name">'.$page->post_title.'</h3>';
        if($title) {
            $content .= '<p class="team-member-title">'.$title.'</p>';
        }
        if($phone) {
            $content .= '<p class="team-member-phone">Call <span class="team-member-phone-number">'.$phone.'</span></p>';
        }
        if($email) {
            $content .= '<a href="mailto:'.$email.'" class="team-member-email">Email me</a>';
        }
        if($department) {
            $content .= '<h4 class="team-member-department">'.$department.'</h4>';
        }
        
        $content .= '</div><div class="team-member-text">'.get_the_content().'</div>';
        $content .= "</div>";        

        $content .= '</div>';
        
        //
    }
    wp_reset_postdata();
    $content .= '</div></div>';
    
    return "
    <div class=\"team {$class}\">    
        {$content}
    </div>
    ";
}
