<?php 
/*
* Template Name: VncslabCompanion template debug 202308
* Template Post Type: post, page, suncarousel
*/
/* Package VncslabCompanion Plugin */
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Includes\Init as Init;


global $post;

$pluginProperties = Init::$PLUGIN_INSTANCE_LIST[ PluginProperties::class ];

?>

<!-- Preprocessing some data & information before displaying the debug page -->
<?php 
/** Do custom query depend on the type of requesting page/post */
if( is_page() ){
    $requestURI = $_SERVER['REQUEST_URI'];
    $requestURI = strtok( $requestURI, '?' );

    $pageSlugs = explode( '/', $requestURI);

    $filteredSlugs = array_filter( $pageSlugs, function($item){ if('' != $item ) return $item; } );

    $filteredSlugs = array_values( $filteredSlugs );

    // PluginDebugHelper::showProblematicVar( $filteredSlugs );

    if( 1 == sizeof( $filteredSlugs ) ){
        $pageSlug = $filteredSlugs[0];

        $queryArgs = array(
            'numberposts'       => -1,
            'post_type'         => 'page',
            'pagename'          => $pageSlug 
        );
    
        $queryResult = new WP_Query( $queryArgs );

        $resultPosts = $queryResult->get_posts();//Get all found posts

        $resultPost = ($queryResult->found_posts == 1) ? $resultPosts[0] : new stdClass();
    } 
    else if( 2 <= sizeof( $filteredSlugs ) ){
        $pageSlug = $filteredSlugs[ sizeof($filteredSlugs) - 1];
        $parentSlug = $filteredSlugs[ sizeof($filteredSlugs) - 2 ];

        // PluginDebugHelper::showProblematicVar( $filteredSlugs );

        $queryArgs = array(
            'numberposts'       => -1,
            'post_type'         => 'page',
            'pagename'          => $pageSlug 
        );

        $queryResult = new WP_Query( $queryArgs );

        $resultPosts = $queryResult->get_posts();//Get all found posts

        $resultPost = ($queryResult->found_posts == 1) ? $resultPosts[0] : new stdClass();
    }  
    else{
        $resultPost = new stdClass();
    }

    

    /* $resultPosts = array();

    if( $queryResult->have_posts() ){
        while( $queryResult->have_posts() ){
            $queryResult->the_post();

            $resultPosts[] = $queryResult->get_posts();
        }// End while $queryResult->have_posts()
    }//End if $queryResult->have_posts() */
    

    // Restore original Post Data
    // wp_reset_postdata(); 
}// End of is_page()

?>
<!-- 1. Displaying the debug page template header -->
<?php get_header() ?>

<!-- 2. Displaying the debug page body - content that need to be debug -->
<?php if( $resultPost instanceof WP_Post ): ?>
    <div id="body-primary" class="devsunsetnew-content-area">

        <h2>Home page of the VncslabCompanion plugin - generated from "VncslabCompanion Debug template" </h2>
        <pre>Current post ID: <?php echo $resultPost->ID; ?> </pre>
        <pre>Current post title: <?php echo $resultPost->post_title; ?> </pre>
        <pre>Current OS information: <?php echo PHP_OS; ?> </pre>

        <div id="VncslabCompanion-content-wrapper-id" class="VncslabCompanion-content-wrapper">
            <?php echo $resultPost->post_content; ?>
        </div><!--div#VncslabCompanion-content-wrapper-id-->
    </div><!-- #body-primary -->
<?php endif;//if( $resultPost instanceof WP_Post ): ?>

<!-- 3. Displaying the debug page template footer -->
<!-- Get footer by loading the template footer.php -->
<?php get_footer() ?>