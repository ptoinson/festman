<?php
require_once (dirname(__FILE__) . '/../objects/ProgramItem.php');
require_once (dirname(__FILE__) . '/../objects/RelatedPerson.php');
require_once (dirname(__FILE__) . '/../objects/Collateral.php');
require_once (dirname(__FILE__) . '/../objects/Act.php');
require_once (dirname(__FILE__) . '/../objects/Klass.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Workshop.php');
require_once (dirname(__FILE__) . '/../objects/Panel.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Installation.php');
require_once (dirname(__FILE__) . '/../library/config.php');
require_once (dirname(__FILE__) . '/../library/utils.php');
require_once (dirname(__FILE__) . '/admin_utils.php');

if (isset($_SESSION['current_program_item']))
{
    $program_item = $_SESSION['current_program_item'];
    $program_item_class = $program_item->getObjectClass();
    $fm_is_new = false;
    $fm_page = "fm-" . strtolower(esc_attr($program_item_class)) . "-page";
}
else
{
    $program_item = new ProgramItem();
    $program_item_class = $_SESSION['current_program_item_class'];
    $program_item->setObjectClass($program_item_class);
    $fm_is_new = true;
    $fm_page = "fm-" . strtolower(esc_attr($program_item_class)) . "-page";
}

$object_class_display_name = ProgramItem::getObjectClassDisplayName($program_item_class);

?>

<script type="text/javascript">

    <?php if($program_item->isDirty()) { ?>
        fm_hasChanged = true;
    <?php }  ?>

    var nameValidator = new fmValidator();
    nameValidator.addValidator(new fmRequiredValidator("Please enter a Name."));
    nameValidator.addValidator(new fmMaxLengthValidator(256, "Name must have a length less that or equal to 256."));
    
    var urlValidator = new fmValidator();
    urlValidator.addValidator(new fmRequiredValidator("Please enter a Url."));
    urlValidator.addValidator(new fmMaxLengthValidator(256, "Url must have a length less that or equal to 256."));
    
    var urlTextValidator = new fmValidator();
    urlTextValidator.addValidator(new fmMaxLengthValidator(128, "Url must have a text length less that or equal to 128."));
    
    var embedValidator = new fmValidator();
    embedValidator.addValidator(new fmMaxLengthValidator(512, "Embed must have a length less that or equal to 512."));
    
    var embedTextValidator = new fmValidator();
    embedTextValidator.addValidator(new fmMaxLengthValidator(128, "Embed must have a text length less that or equal to 128."));
    
    var originValidator = new fmValidator();
    originValidator.addValidator(new fmMaxLengthValidator(128, "Origin must have a length less that or equal to 128."));
    
    var descriptionValidator = new fmValidator();
    descriptionValidator.addValidator(new fmRequiredValidator("Please enter a Description."));
    descriptionValidator.addValidator(new fmMaxLengthValidator(16384, "Description must have a length less that or equal to 16384."));
    
    function fmOnSubmitProgramItemForm()
    {
        fm_ignoreChanges = true;
        
        var form = document.forms["program-item-form"];
        var elements = form.elements;
    
        var name = form.elements["program_item_name"].value;
        var url = form.elements["program_item_url"].value;
        var url_text = form.elements["program_item_url_text"].value;
        var embed = form.elements["program_item_embed"].value;
        var embed_text = form.elements["program_item_embed_text"].value;
        var origin = form.elements["program_item_origin"].value;
        var editor = tinyMCE.get("program_item_description");
        var description = editor.getContent();
    
        //alert('submitting form name:' + name + "\n" + 'url:' + url + "\n");
    
        var errorString = nameValidator.validate(name);
        errorString = fmAppendLine(errorString, urlValidator.validate(url));
        errorString = fmAppendLine(errorString, urlTextValidator.validate(url_text));
        errorString = fmAppendLine(errorString, embedValidator.validate(embed));
        errorString = fmAppendLine(errorString, embedTextValidator.validate(embed_text));
        errorString = fmAppendLine(errorString, originValidator.validate(origin));
        errorString = fmAppendLine(errorString, descriptionValidator.validate(description));
    
        if(errorString.length > 0)
        {
            alert(errorString);
        }
        else
        {
            form.submit();
        }
    }

    function fmAddCollateral()
    {
        fm_ignoreChanges = true;
        
        var form = document.forms["program-item-form"];
        form.action = "admin.php?page=fm-collateral-page";
        form.elements["action"].value = "add_collateral";
        form.submit();
    }


    function fmAddRelatedPerson()
    {
        fm_ignoreChanges = true;
        
        var form = document.forms["program-item-form"];
        form.elements["action"].value = "create_related_person";
        form.submit();
    }

    function fmEditRelatedPerson(related_person_id)
    {
        fm_ignoreChanges = true;
        
        var form = document.forms["program-item-form"];
        form.elements["action"].value = "edit_related_person";
        form.elements["related_person_id"].value = related_person_id;

        form.submit();
    }
    
    function fmDeleteRelatedPerson(related_person_id)
    {
        fm_ignoreChanges = true;
        
        if ( confirm( 'You are about to delete this Person.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) 
        { 
            var form = document.forms["program-item-form"];

            form.elements["action"].value = "delete_related_person";
            form.elements["related_person_id"].value = related_person_id;

            form.submit();
        }
    }
    
    function fmRemoveCollateral(collateral_id)
    {
        fm_ignoreChanges = true;
        
        if ( confirm( 'You are about to remove this Collateral.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) 
        { 
            var form = document.forms["program-item-form"];

            form.action = "admin.php?page=fm-collateral-page";
            form.elements["action"].value = "remove_collateral";
            form.elements["object_collateral_id"].value = collateral_id;

            form.submit();
        }
    }

    function fmRemoveCollateralList()
    {
        fm_ignoreChanges = true;
        
        if ( confirm( 'You are about to remove this Collateral.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) 
        { 
            var form = document.forms["program-item-form"];

            form.action = "admin.php?page=fm-collateral-page";
            form.elements["action"].value = "remove_collateral_list";

            form.submit();
        }
    }
   
    //href="admin.php?page=fm-collateral-page&action=add_collateral&collateral_collection_type=program_item"
    //admin.php?page=<?php echo $fm_page; ?>&action=create_related_person
</script>


<?php
// don't load directly
if (!defined('ABSPATH'))
    die('-1');

if (!$fm_is_new)
{
    $heading = sprintf(__('<a href="%s">%s</a> / Edit %s'), 'admin.php?page=' . $fm_page, esc_html($object_class_display_name), esc_html($object_class_display_name));
    $submit_text = sprintf(__('Update %s'), esc_html($object_class_display_name));
    $form = '<form name="program-item-form" id="program-item-form" method="post" action="admin.php?page=' . $fm_page . '">';
    $nonce_action = 'update-' . $program_item_class . '_' . $program_item->getId();
}
else
{
    $heading = sprintf(__('<a href="%s">%s</a> / Add New %s'), 'admin.php?page=' . $fm_page, esc_html($object_class_display_name), esc_html($object_class_display_name));
    $submit_text = sprintf(__('Add %s'), esc_html($object_class_display_name));
    $form = '<form name="program-item-form" id="program-item-form" method="post" action="admin.php?page=' . $fm_page . '">';
    $nonce_action = 'add-' . $program_item_class;
}

require_once(ABSPATH . 'wp-admin/includes/meta-boxes.php');

add_screen_option('layout_columns', array('max' => 2));

$user_ID = isset($user_ID) ? (int) $user_ID : 0;

if (isset($_SESSION['error_message']))
{
    echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">" . esc_html($_SESSION['error_message']) . "</TD></TR></TABLE><BR/>";
}
if (isset($_SESSION['action_message']))
{
    echo "<TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>" . esc_html($_SESSION['action_message']) . "</TD></TR></TABLE>";
}

if (!empty($form))
    echo $form;
if (!empty($link_added))
    echo $link_added;

echo "\n";
wp_nonce_field(esc_attr($nonce_action));
echo "\n";
?>

<div class="wrap">
    <div id="icon-themes" class="icon32">
        <br>
    </div>
    <h2><?php echo $heading; ?> <a href="admin.php?page=<?php echo $fm_page; ?>&action=create_program_item&program_item_class=<?php echo esc_attr($object_class_display_name); ?>" class="add-new-h2"><?php echo esc_html_x('Add New', 'Add New'); ?></a></h2>
    <div id="poststuff" class="metabox-holder has-right-sidebar">

        <input type="hidden" name="action" value="save_program_item" /> 
        <input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" />
        <input type="hidden" name="program_item_id" value="<?php echo esc_attr($program_item->getId()); ?>" /> 
        <input type="hidden" name="program_item_class" value="<?php echo esc_attr($program_item_class); ?>" /> 
        <input type="hidden" name="collateral_collection_type" value="program_item" /> 
        <input type="hidden" name="object_collateral_id" value="" /> 
        <input type="hidden" name="related_person_id" value="" /> 
        <input type="hidden" id="user-id" name="user_ID" value="<?php echo (int) $user_ID ?>" /> 

        <div id="side-info-column" class="inner-sidebar">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                <div id="linksubmitdiv" class="postbox ">
                    <div class="handlediv" title="Click to toggle">
                        <br>
                    </div>
                    <h3 class="hndle">
                        <span><?php echo sprintf(__('Save %s'), esc_html($object_class_display_name)); ?></span>
                    </h3>
                    <div class="inside">
                        <div id="submitlink" class="submitbox">
                            <div id="major-publishing-actions">
<?php if (!$fm_is_new)
{ ?>
                                    <div id="delete-action">
                                        <a class="submitdelete" onclick="if ( confirm( 'You are about to delete this \'<?php echo esc_attr($program_item->getName()); ?>\'\n \'Cancel\' to stop, \'OK\' to delete.' ) ) { return true;}return false;" href="<?php echo wp_nonce_url("admin.php?page=$fm_page&amp;action=delete_program_item&amp;program_item_id=" . esc_attr($program_item->getId()) . "&amp;program_item_class=" . esc_attr($program_item_class), 'delete-' . $program_item_class . '_' . esc_attr($program_item->getId())) ?>">Delete</a>
                                    </div>
<?php } ?>
                                <div id="publishing-action">

                                    <button onclick="fmOnSubmitProgramItemForm(); return false;" id="publish" class="button-primary" accesskey="p" tabindex="4" name="save"><?php echo $submit_text ?></button>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="post-body">
            <div id="post-body-content">

                <div id="namediv" class="stuffbox">
                    <h3>
                        <label for="name"><?php echo esc_html($object_class_display_name) ?></label>
                    </h3>
                    <div class="inside">
                        <table class="form-table edit-concert concert-form-table">

                            <tr valign="top">
                                <td class="first">Name:</td>
                                <td><input name="program_item_name" type="text" maxlength="200" value="<?php echo esc_attr($program_item->getName()) ?>" alt="name" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Origin:</td>
                                <td><input name="program_item_origin" type="text" maxlength="100" value="<?php echo esc_attr($program_item->getOrigin()) ?>" alt="origin" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Url:</td>
                                <td><input name="program_item_url" type="text" maxlength="200" value="<?php echo esc_attr($program_item->getUrl()) ?>" alt="url" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Url Text:</td>
                                <td><input name="program_item_url_text" type="text" maxlength="100" value="<?php echo esc_attr($program_item->getUrlText()) ?>" alt="url text" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Embed:</td>
                                <td><input name="program_item_embed" type="text" maxlength="500" value="<?php echo esc_attr($program_item->getEmbed()) ?>" alt="embed" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Embed Text:</td>
                                <td><input name="program_item_embed_text" type="text" maxlength="100" value="<?php echo esc_attr($program_item->getEmbedText()) ?>" alt="embed text" /></td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </div>

                <div id="postdivrich">
                    <h3><label for="content"><?php echo esc_html($object_class_display_name) ?> Description</label></h3>
                    <?php the_editor($program_item->getDescription(), "program_item_description", "program_item_url_text", false); ?>
                </div>

                <div class="collateraldiv">
                    <h3 class="hndle"><span>Collateral <a class="add-new-h2" onclick="fmAddCollateral(); return false;" href="#">Add</a></span></h3>
                    <table class="wp-list-table widefat fixed pages" callspacing="0">
                        <thead>
                            <tr>
                                <th class="manage-column lc-column">Name</th>
                                <th class="manage-column lc-column">Sort Order</th>
                                <th class="manage-column lc-column">Default</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="manage-column lc-column" style="" scope="col">Name</th>
                                <th class="manage-column lc-column" style="" scope="col">Sort Order</th>
                                <th class="manage-column lc-column" style="" scope="col">Default</th>
                            </tr>
                        </tfoot>
                        <tbody id="the-list">

                            <?php
                            $cc_object_collateral_list = &$program_item->getAllObject_Collateral();
                            $program_item->sortObject_Collateral();
                            $cc_object_collateral_count = count($cc_object_collateral_list);
                            if ($cc_object_collateral_count > 0)
                            {
                                for ($i = 0; $i < $cc_object_collateral_count; $i++)
                                {
                                    $cc_object_collateral = $cc_object_collateral_list[$i];
                                    $cc_collateral = $cc_object_collateral->getCollateral();
                                    $cc_collateral_id = $cc_collateral->getId();
                                    $cc_collateral_name = $cc_collateral->getName();
                                    $cc_object_collateral_sort_order = $cc_object_collateral->getSortOrder();
                                    $cc_object_collateral_is_default = $cc_object_collateral->getIsDefault();

                                    $action_id = uniqid("remove");

                                    //echo "<tr class=\"border\">".
                                    //"  <td><input type=\"hidden\" name=\"object_collateral_ids[]\" value=\"".$cc_collateral_id."\">\n".
                                    //"    <input type=\"checkbox\" name=\"object_collateral_checked_ids[]\" value=\"".$cc_collateral_id."\"></td>\n".
                                    //"  <td><a href=\"javascript:void(0)\" OnClick=\"javascript:window.open('../" . $cc_collateral->getUrl() . "', 'collateral')\">".$cc_collateral_name."</td>\n".
                                    //"  <td><input type=\"text\" name=\"object_collateral_sort_order[]\" size=\"3\" value=\"".$cc_object_collateral_sort_order."\"></td>\n".
                                    //"  <td><input type=\"radio\" name=\"object_collateral_default\" value=\"".$cc_collateral_id."\" ". ($cc_object_collateral_is_default == true ? "checked=\"true\"" : "") ."></td>\n".
                                    //"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:removeCollateral('".$this->form_name."', '".$cc_collateral_id."');\">remove</a></td>\n".
                                    //"</tr>\n";
                                    ?>

                                    <tr>
                                        <td class="column-name">
                                            <input type="hidden" name="object_collateral_ids[]" value="<?php echo esc_attr($cc_collateral_id); ?>">
                                            <strong>
                                                <?php echo esc_html($cc_collateral_name); ?>
                                            </strong>
                                            <br>
                                            <div class="row-actions">
                                                <span class="remove">
                                                    <a class="submitdelete" onclick="fmRemoveCollateral('<?php echo esc_attr($cc_collateral_id); ?>'); return false;" href="#">Remove</a>
                                                </span>
                                            </div>
                                        </td>
                                        <td><input type="text" name="object_collateral_sort_order[]" size="3" value="<?php echo esc_attr($cc_object_collateral_sort_order); ?>"></td>
                                        <td><input type="radio" name="object_collateral_default" value="<?php echo esc_attr($cc_collateral_id); ?>" <?php echo ($cc_object_collateral_is_default == true ? "checked=\"true\"" : ""); ?>"></td>
                                    </tr>


                                <?php } // endforeach ?>
                            <?php
                            }
                            else
                            { // endif  
                                ?>
                                <tr class="no-items">
                                    <td class="colspanchange" colspan="2">No Collateral found.</td>
                                </tr>
                            <?php } ?>


                            <?php
                            //echo "<tr class=\"border\">".
                            //"  <td><input type=\"checkbox\" name=\"program_item_ids[]\" value=\"".$program_item_id."\"</td>\n".
                            //"  <td>".$program_item_name."&nbsp;(".$collateral_count.")</td>\n".
                            //"  <td>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:editProgramItem('".$program_item_id."');\">edit</a></td>\n".
                            //"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:deleteProgramItem('".$program_item_id."');\">delete</a></td>\n".
                            //"</tr>\n";
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="relateddiv">
                    <h3>Related Persons <a class="add-new-h2" onclick="fmAddRelatedPerson(); return false;" href="#">Add New</a></h3>
                    <table class="wp-list-table widefat fixed pages" callspacing="0">
                        <thead>
                            <tr>
                                <th class="manage-column lc-column">Name</th>
                                <th class="manage-column lc-column">Collateral Count</th>
                                <th class="manage-column lc-column">Role</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="manage-column lc-column" style="" scope="col">Name</th>
                                <th class="manage-column lc-column" style="" scope="col">Collateral Count</th>
                                <th class="manage-column lc-column" style="" scope="col">Role</th>
                            </tr>
                        </tfoot>
                        <tbody id="the-list">

                            <?php
                            $related_persons = &$program_item->getRelatedPersons();
                            $num_rows = count($related_persons);

                            if ($num_rows > 0)
                            {
                                for ($i = 0; $i < $num_rows; $i++)
                                {
                                    $related_person_id = $related_persons[$i]->getId();
                                    $related_persons_name = $related_persons[$i]->getName();
                                    $related_persons_role = $related_persons[$i]->getRole();
                                    $collateral_count = ($related_person_id == NULL ? count($related_persons[$i]->getAllCollateral()) : RelatedPerson::getCollateralCount($related_person_id));
                                    $action_id = uniqid("delete");

                                    //echo "<tr class=\"border\">".
                                    //"  <td><input type=\"checkbox\" name=\"related_person_ids[]\" value=\"".$related_person_id."\"</td>\n".
                                    //"  <td>".$related_persons_name."&nbsp;(".$collateral_count.")</td>\n".
                                    //"  <td>".$related_persons_role."</td>\n".
                                    //"  <td>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:editRelatedPerson('".$related_person_id."');\">edit</a></td>\n".
                                    //"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:deleteRelatedPerson('".$related_person_id."');\">delete</a></td>\n".
                                    //"</tr>\n";
                                    ?>

                                    <tr>
                                        <td class="column-name">
                                            <strong>
                                                <a class="row-title" title="Edit �Documentation�" href="admin.php?page=<?php echo $fm_page; ?>&action=edit_related_person&related_person_id=<?php echo esc_attr($related_person_id) ?>"><?php echo esc_html($related_persons_name); ?></a>
                                            </strong>
                                            <br>
                                            <div class="row-actions">
                                                <span class="edit">
                                                    <a onclick="fmEditRelatedPerson(<?php echo $related_person_id ?>); return false;" href="#">Edit</a> |
                                                </span>
                                                <span class="delete">
                                                    <a onclick="fmDeleteRelatedPerson(<?php echo $related_person_id ?>); return false;" href="#">Delete</a>
                                                </span>
                                            </div>
                                        </td>
                                        <td><?php echo esc_html($collateral_count); ?></td>
                                        <td><?php echo esc_html($related_persons_role); ?></td>
                                    </tr>


    <?php } // endforeach  ?>
<?php }
else
{ // endif ?>
                                <tr class="no-items">
                                    <td class="colspanchange" colspan="2">No Persons found.</td>
                                </tr>
                            <?php } ?>


                            <?php
                            //echo "<tr class=\"border\">".
                            //"  <td><input type=\"checkbox\" name=\"program_item_ids[]\" value=\"".$program_item_id."\"</td>\n".
                            //"  <td>".$program_item_name."&nbsp;(".$collateral_count.")</td>\n".
                            //"  <td>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:editProgramItem('".$program_item_id."');\">edit</a></td>\n".
                            //"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:deleteProgramItem('".$program_item_id."');\">delete</a></td>\n".
                            //"</tr>\n";
                            ?>
                        </tbody>
                    </table>
                </div>

                <div id="postdiv" class="postarea"></div>
                <div id="normal-sortables" class="meta-box-sortables"></div>
                <input type="hidden" id="referredby" name="referredby" value="<?php echo esc_url(stripslashes(wp_get_referer())); ?>" />
            </div>
        </div>
    </div>
</div>
</form>


<?php
fmClearMessages();
?>