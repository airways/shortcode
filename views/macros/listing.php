<?php

if(!isset($table)) $table = &$this->table;

$return = (isset($return_type)
                    ? AMP.'return_type='.$return_type.AMP.'return_item_id='.$return_item_id
                    : ''
                );
foreach($filters as $filter => $value)
{
    $return = AMP.$filter.'='.$value.AMP.$return;
}
if(!isset($embedded) || !$embedded): 

            if(isset($message) && $message != FALSE) echo '<div class="notice success">'.$message.'</div>';
            if(isset($error) && $error != FALSE) echo '<div class="notice">'.$error.'</div>';

?>
<div id="process_tab">
    <ul class="process_fields">
<?php endif; ?>
        <li class="section first">
            
            
            <!-- <label><?php echo $mgr->plural_label ? $mgr->plural_label : ucwords($mgr->plural); ?></label> -->
            <br/>
            <div class="new_button"><a title="Create Workflow" class="submit" href="<?php echo BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP
                .'module='.$package_name.AMP
                .'method=create'.AMP
                .'G='.$type
                .$return; ?>"><?php echo $create_item; ?></a></div>
            <br/>
        </li>
        <li class="section first">

            <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


            $table->set_template($cp_table_template);
            
            $heading = array();
            $heading[] = lang('heading_item_name');
            if(isset($mgr->heading))
            {
                foreach($mgr->heading as $field)
                {
                    $heading[] = lang('heading_'.$field);
                }
            }
            $heading[] = lang('heading_actions');
            
            $table->set_heading($heading);

            if (count($items) > 0)
            {
                foreach($items as $item)
                {
                    $row = array();
                    $row[] = '<a href="'.sprintf($edit_url, $item->get_obj_id()).$return.'">['.$item->scope.':'.$item->name.']</a>';
                    
                    if(isset($mgr->heading))
                    {
                        foreach($mgr->heading as $field)
                        {
                            $value = substr($item->$field, 0, 100);
                            if($value != $item->$field) $value .= '...';
                            $row[] = htmlentities($value);
                        }
                    }
                    
                    $row[] = '<span class="action-list"> '.
                                '<a href="'.sprintf($edit_url, $item->get_obj_id()).$return.'">'. lang('edit_item').'</a> '.
                                '<a href="'.sprintf($delete_url, $item->get_obj_id()).$return.'">'.lang('delete_item').'</a>'.
                            '</span>';
                    $table->add_row($row);
                }
            }
            else
            {
                $table->add_row(array(
                    'data'      => '<div class="no_items_msg">' . lang('no_items') . '</div>',
                    'colspan'   => 2 + (isset($mgr->heading) ? count($mgr->heading) : 0),
                ));
            }

            echo $table->generate();

            if(isset($pagination))
            {
                ?>
                <div class="tableFooter">
                    <span class="pagination"><?=$pagination?></span>
                </div>
                <?php
            }

            ?>

        </li>
        <li>
            <div class="new_button"><a title="Create Workflow" class="submit" href="<?php echo BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP
                .'module='.$package_name.AMP
                .'method=create'.AMP
                .'G='.$type
                .$return; ?>"><?php echo $create_item; ?></a></div>
            <br/>
        </li>
<?php if(!isset($embedded) || !$embedded): ?>
    </ul>
</div>
<?php endif; ?>
