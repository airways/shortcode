<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'shortcode/models/shortcode_macro.php';

class Shortcode_macros extends PL_handle_mgr
{
    var $table = "shortcode_macros";
    var $singular = "macro";
    var $plural = "macros";
    var $class = "Shortcode_macro";
    var $serialized = array('settings');
    var $children = array();
    var $edit_hidden = array('site_id', 'owner_id', 'scope');
    var $heading = array('scope', 'type', 'value');
    var $filters = array('scope');

    public function __construct(&$lib)
    {
        parent::__construct(FALSE, FALSE, FALSE, FALSE, $lib);
    }

    public function get_type_options()
    {
        $result = array(
            'text' => 'Text',
            'textarea' => 'Textarea',
        );
        return $result;
    }

    public function model_form(&$row)
    {
        return array(
            'type' => array('dropdown', array('Types' => $this->get_type_options())),
            'value' => 'textarea',
        );
    }
}
