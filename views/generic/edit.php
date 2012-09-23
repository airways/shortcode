<?php
    if(isset($message) && $message != FALSE) echo '<div class="notice success">'.$message.'</div>';
    if(isset($error) && $error != FALSE) echo '<div class="notice">'.$error.'</div>';
?>
<div id="process_tab">
    <ul class="process_fields">
        <li class="section first">
<!--             <label><?php echo isset($form_name) ? $form_name : '' ; ?></label> -->

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if(count($_POST) > 0)
{
    echo validation_errors('<div class="message error-message">', '</div>');

    if(isset($message) && $message != FALSE) echo '<div class="message error-message">'.$message.'</div>';
    if(isset($error) && $error != FALSE) echo '<div class="message error-message">'.$error.'</div>';
}


if(isset($info) && $info != FALSE) echo '<div class="info">'.$info.'</div>';
if(isset($warning) && $warning != FALSE) echo '<div class="warning">'.$warning.'</div>';


?>
<!-- <div class="message error-message">
    Please complete the highlighted fields below.
</div> -->
<?php
if(isset($buttons)):
    foreach($buttons as $btn):
?>
<div class="new_field">
    <span class="button"><a href="<?php echo $btn['url']; ?>"><?php echo $btn['label']; ?></a></span>
</div>
<?php
    endforeach;
endif;
?>

<div class="editForm" id="<?php if(isset($form_name)) echo $form_name; ?>">
<?php
    if(!isset($generic_edit_embedded) || !$generic_edit_embedded)
    {
        echo form_open($action_url, array('class' => 'generic_edit'), isset($hidden) ? $hidden : array());
    }
    $table_template = $cp_table_template;
    $table_template['cell_start'] = '<td width="50%">';
    $table_heading = array(lang('heading_property'), lang('heading_value'));
    $this->table->set_template($table_template);
    $this->table->set_heading($table_heading);


    foreach($form as $field)
    {
        if(!is_array($field))
        {
            echo "Not an array:";
            var_dump($field);
            die;
        }

        if(array_key_exists('heading', $field))
        {
            echo $this->table->generate();
            echo '<h3 class="sub-heading">'.$field['heading'].'</h3>';
            if(isset($field['description']))
            {
                echo '<p>'.$field['description'].'</p>';
            }
            $this->table->set_template($table_template);
            $this->table->set_heading($table_heading);
            continue;
        }

        if(isset($hidden_fields) && array_search($field['lang_field'], $hidden_fields) !== FALSE)
        {
            continue;
        }


        // used to look up lang entries for this field
        $lang_field = 'field_' . $field['lang_field'];

        // construct label cell
        if(isset($field_names[$lang_field]))
        {
            $label = '<label>' . $field_names[$lang_field] . '</label>';
        } else {
            $label = '<label>' . lang($lang_field) . '</label>';
        }

        if(array_search('required', $field) !== FALSE) {
            $label .= '<em class="required">* </em>';
        } else {
            $label .= '';
        }

        $label .= '<br />';
        if(lang("{$lang_field}_desc") != "{$lang_field}_desc") {
            $label .= lang("{$lang_field}_desc");
        }

        if(!array_key_exists('control', $field)) {
            $field['control'] = form_input($field['lang_field']);
        }
        // add field to the table
        $this->table->add_row(
                $label,
                $field['control']
            );
    }

    echo $this->table->generate();

    ?>
</div>
</li>

        <li>
            <?php
                if(!isset($generic_edit_embedded) || !$generic_edit_embedded)
                {
                    ?>
                    <?php echo form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'))?>
                    <?php echo form_close();
                }
            ?>
            <br/><br/>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('.generic_edit input[type=text]').filter(':visible:first').focus();
                });
            </script>
        </li>

<?php

function embedded_listing(&$table, $cp_table_template, $package_name, &$items, &$mgr, $return_type, $return_item_id)
{
    $embedded = TRUE;
    $type = $mgr->singular;
    $create_item = lang($type.'_create') != $type.'_create' ? lang($type.'_create') : lang('item_create');
//     $return = AMP.'return_type='.$return_type.AMP.'return_item_id='.$return_item_id;
    $edit_url    = ACTION_BASE.AMP.'method=edit'.AMP.'G='.$type.AMP.'item_id=%s';
    $delete_url  = ACTION_BASE.AMP.'method=delete'.AMP.'G='.$type.AMP.'item_id=%s';

    include PATH_THIRD.$package_name.'/views/generic/listing.php';
}

foreach($mgr->children as $child_mgr_name)
{
    if(isset($child_items[$child_mgr_name]))
    {
        embedded_listing($this->table, $cp_table_template, $package_name, $child_items[$child_mgr_name], $managers[$child_mgr_name],
            $type, $item_id);
    }
}
?>
    </ul>
</div>
