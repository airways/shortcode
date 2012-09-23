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

class Shortcode_mcp extends Prolib_base_mcp {

    public $return_data;

    private $_base_url;

    public function __construct()
    {
        prolib($this, 'shortcode');
        $this->lib = &Shortcode_lib::get_instance();
        parent::__construct();

        $this->_base_url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=shortcode';

        $this->EE->cp->add_to_head('<link rel="stylesheet" href="' . $this->EE->config->item('theme_folder_url') . 'third_party/shortcode/styles/main.css" type="text/css" media="screen" />');
        $this->EE->cp->add_to_head('<script type="text/javascript" src="' . $this->EE->config->item('theme_folder_url') . 'third_party/shortcode/javascript/global.js"></script>');

        $nav = array(
            'my_macros'         => $this->_base_url.AMP.'filter_scope=mine',
//             'global_macros'     => $this->_base_url.AMP.'filter_scope=global',
//             'tab_preferences'   => $this->_base_url.AMP.'method=preferences',
        );

        if($this->EE->session->userdata('group_id') == 1)
        {
            $nav['site_macros'] = $this->_base_url.AMP.'filter_scope=site';
        }
        $this->EE->cp->set_right_nav($nav);
    }

    public function index()
    {
        // List shortcode objects in the index of the module
        $this->find_manager('macro');
        return $this->listing();
    }

    public function listing($params=array('set_title' => true))
    {
        $params['set_title'] = false;
        $title = lang('shortcode_module_name');

        if($this->EE->input->get('filter_scope'))
        {
            switch($this->EE->input->get('filter_scope'))
            {
                case 'mine':
                    $title = lang('my_macros');
                    break;
                case 'site':
                    $title = lang('site_macros');
                    break;
                case 'global':
                    $title = lang('global_macros');
                    break;
            }
        }

        $this->EE->cp->set_variable('cp_page_title', $title);

        switch($this->EE->input->get('filter_scope'))
        {
            case 'mine':
                // If we're looking at the mine scope, filter to only the current user
                $params['filters']['owner_id'] = $this->EE->session->userdata('member_id');
                break;
            case 'site':
            default:
                if($this->EE->session->userdata('group_id') != 1)
                {
                    // If we're not a Super Admin, go back to mine scope
                    $this->EE->functions->redirect(ACTION_BASE.'method=listing'.AMP.'G=macro'.AMP.'filter_scope=mine');
                }
                break;
        }

        return parent::listing($params);
    }

    public function process_create()
    {
        $_POST['owner_id'] = $this->EE->session->userdata('member_id');
        $_POST['site_id'] = $this->prolib->site_id;
        if(!$this->EE->input->get_post('scope'))
        {
            $_POST['scope'] = 'mine';
        }
        return parent::process_create();
    }
}

/* End of file mcp.shortcode.php */
/* Location: /system/expressionengine/third_party/shortcode/mcp.shortcode.php */
