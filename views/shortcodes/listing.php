<div class="info">
<b>Note:</b> These shortcodes are provided by installed add-ons. Install more add-ons to get more shortcodes!
</div>

<?php

if(!isset($table)) $table = &$this->table;

$return = (isset($return_type)
                    ? AMP.'return_type='.$return_type.AMP.'return_item_id='.$return_item_id
                    : ''
                );
if(!isset($embedded) || !$embedded): 

    if(isset($message) && $message != FALSE) echo '<div class="notice success">'.$message.'</div>';
    if(isset($error) && $error != FALSE) echo '<div class="notice">'.$error.'</div>';

    if(isset($info) && $info != FALSE) echo '<div class="info">'.$info.'</div>';
    if(isset($warning) && $warning != FALSE) echo '<div class="warning">'.$warning.'</div>';

?>
<div id="process_tab">
    <ul class="process_fields">
<?php endif; ?>
        <li class="section first">

            <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

            $table->set_template($cp_table_template);
            
            $heading = array(
                lang('heading_shortcode'),
                lang('heading_docs')
            );
            
            $table->set_heading($heading);

            if (count($items) > 0)
            {
                foreach($items as $key => $item)
                {
                    $row = array(
                        $item['label'],
                        isset($item['docs']) ? $item['docs'] : '',
                    );
                    
                    if(isset($mgr->heading))
                    {
                        foreach($mgr->heading as $field)
                        {
                            $value = substr($item->$field, 0, 100);
                            if($value != $item->$field) $value .= '...';
                            $row[] = htmlentities($value);
                        }
                    }

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
<?php if(!isset($embedded) || !$embedded): ?>
    </ul>
</div>
<?php endif; ?>
