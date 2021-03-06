<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package Shortcode
 * @author Isaac Raway <isaac.raway@gmail.com>
 *
 * Copyright (c)2012. Isaac Raway and MetaSushi, LLC.
 * All rights reserved.
 *
 * This source is commercial software. Use of this software requires a
 * site license for each domain it is used on. Use of this software or any
 * of its source code without express written permission in the form of
 * a purchased commercial or other license is prohibited.
 *
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE.
 *
 * As part of the license agreement for this software, all modifications
 * to this source must be submitted to the original author for review and
 * possible inclusion in future releases. No compensation will be provided
 * for patches, although where possible we will attribute each contribution
 * in file revision notes. Submitting such modifications constitutes
 * assignment of copyright to the original author (Isaac Raway and
 * MetaSushi, LLC) for such modifications. If you do not wish to assign
 * copyright to the original author, your license to  use and modify this
 * source is null and void. Use of this software constitutes your agreement
 * to this clause.
 *
 **/

require_once PATH_THIRD.'shortcode/lib.shortcode.php';

if(!class_exists('Shortcode_ext')) {
class Shortcode_ext {

    public $settings        = array();
    public $description     = SHORTCODE_DESCRIPTION;
    public $docs_url        = SHORTCODE_DOCSURL;
    public $name            = SHORTCODE_NAME;
    public $settings_exist  = 'n';
    public $version         = SHORTCODE_VERSION;

    public $EE;

    public function __construct($settings = '')
    {
        $this->EE =& get_instance();
        $this->lib = &Shortcode_lib::get_instance();
        $this->settings = $settings;
    }

    public function settings()
    {
        return array(

        );
    }

    public function activate_extension()
    {
        // Setup custom settings in this array.
        $this->settings = array();

        $hooks = array(
            'channel_entries_tagdata_end'       => 'channel_entries_tagdata_end',
        );

        foreach ($hooks as $hook => $method)
        {
            $data = array(
                'class'     => __CLASS__,
                'method'    => $method,
                'hook'      => $hook,
                'settings'  => serialize($this->settings),
                'version'   => $this->version,
                'enabled'   => 'y'
            );

            $this->EE->db->insert('extensions', $data);
        }
    }

    public function channel_entries_tagdata_end($tagdata, $row, $mod)
    {
        $tagdata = $this->lib->parse($tagdata, $row['author_id']);
        if(strpos($tagdata, '{exp:') !== FALSE)
        {
            //echo htmlentities($tagdata);

            /*
            if(!class_exists('EE_Template'))
            {
                ee()->load->helper('text');
                ee()->load->library('Template');
            }
            ee()->TMPL = new EE_Template();
            ee()->TMPL->template = $tagdata;
            ee()->TMPL->template = ee()->TMPL->parse_globals(ee()->TMPL->template);
            ee()->TMPL->parse(ee()->TMPL->template);
            ee()->TMPL->final_template = ee()->TMPL->parse_globals(ee()->TMPL->final_template);
            $result = ee()->TMPL->final_template;
            */
        }
        return $tagdata;
    }

    /*
    
    Without some sort of escaping for content entered by visitors (such as in comments or URL segments), this is
    too dangerous to leave in.
    
    // public function sessions_end()
    // {
    //     if((!defined('REQ') || REQ != 'CP') && !$this->EE->input->get('cp'))
    //     {
    //         ob_start();
    //         register_shutdown_function('shortcode_shutdown');
    //     }
    // }
    
    */

    function disable_extension()
    {
        $this->EE->db->where('class', __CLASS__);
        $this->EE->db->delete('extensions');
    }

    function update_extension($current = '')
    {
        if ($current == '' OR $current == $this->version)
        {
            return FALSE;
        }
    }

}


/*
    
Without some sort of escaping for content entered by visitors (such as in comments or URL segments), this is
too dangerous to leave in.

// function shortcode_shutdown()
// {
//     $EE = &get_instance();
//     if(count($EE->db->ar_from) > 0 || count($EE->db->ar_where) > 0) {
//         ob_end_flush();
//         exit;
//     }
// 
//     $lib = &Shortcode_lib::get_instance();
//     $content = ob_get_contents();
//     ob_end_clean();
//     $content = $lib->parse($content);
//     echo $content;
// }

*/

}


/* End of file ext.shortcode.php */
/* Location: /system/expressionengine/third_party/shortcode/ext.shortcode.php */
