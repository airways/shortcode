<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'shortcode/lib.shortcode.php';

class Shortcode_preferences extends PL_prefs
{
    var $default_prefs = array(
        'shortcode_license_key' => ''
    );

    public function __construct(&$lib)
    {
        parent::__construct('shortcode_preferences', FALSE, $this->default_prefs);
        $this->lib = &$lib;
    }


    /**
     * Used by the Prolib_base_mcp::preferences() action to generate a prefs form. Any prefs
     * not given control markup by this function will get input fields automatically.
     */
    function prefs_form($prefs) {
//         $status_groups = $this->prolib->get_status_groups();
//         $status_groups = array(0 => 'None') + $status_groups;

        $result = array();
        
        foreach($prefs as $name => $value)
        {
            $f_name = 'pref_'.$name;
            switch($f_name)
            {
//                 case 'pref_status_group';
//                     $result['pref_status_group'] = form_dropdown('pref_status_group', $status_groups, $value);
//                     break;
            }
        }
        
        return $result;
    }
}

