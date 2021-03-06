<?php
/**
 * Template Name: Films Template
 * Description: 
 *
 * @package WordPress
 * @subpackage cmkyf
 * @since cmkyf 1.0
 */
cmkyf_set_section('festival');
cmkyf_set_subsection('films');
get_header();

wp_enqueue_script('cmkyf-page-management');
wp_enqueue_style('jquery-fancybox-style');
wp_enqueue_style('jquery-fancybox-buttons-style');
wp_enqueue_style('jquery-fancybox-thumbs-style');

require_once CMKYF_PLUGIN_BASE_DIR.'/library/config.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/library/utils.php';

require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Utils.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Location.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Event.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Act.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Workshop.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Panel.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Klass.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Installation.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/ProgramItem.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Program_ProgramItem.php';

require_once get_template_directory() . '/controls/ItemListControl.php';

cmkyf_include('menu-festival.php');

$films = ProgramItem::getAllTypedProgramItems('Film');
$itemListControl = new ItemListControl($films);
?>
<div id="primary">
    
    <?php 
    	if (count($films) > 0)
        {
    		echo $listControl->render(); 
    	}
    	else {
    		echo '<div class="not-found">No Films found.</div>';
    	}
    ?>

</div><!-- #primary -->

<?php get_footer(); ?>