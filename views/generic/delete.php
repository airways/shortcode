<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

echo form_open($action_url, '', $hidden); ?>
<p class="warning">Are you sure you want to delete the <?php echo $type; ?> <kbd><b><?php echo $object_name; ?></b></kbd>? This cannot be undone.</p>
<div class="tableFooter">
    <?php echo form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit')); ?>
</div>
<? echo form_close(); ?>