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

class Shortcode_rte {

    public $info = array(
        'name'        => SHORTCODE_NAME,
        'version'     => SHORTCODE_VERSION,
        'description' => SHORTCODE_DESCRIPTION,
        'cp_only'     => 'n'
    );

    public $EE;

    function __construct()
    {
        prolib($this, 'shortcode');
        $this->lib = Shortcode_lib::get_instance();
        $this->_base_url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=shortcode';
    }

    function globals()
    {
        $this->EE->lang->loadfile('shortcode');
        return array(
            'rte.shortcode.label' => SHORTCODE_NAME,
        );
    }

    function styles()
    {
    return <<<END
        /**
         * Button
         */
        button.shortcode {
            padding: 3px 4px 3px 4px !important;
            height: 23px;
        }
        button.shortcode b {
            background: url(../themes/third_party/shortcode/images/icons/macro.png) no-repeat 0px 0px !important;
            width: 45px;
            height: 16px !important;
        }


        /**
         * Dialog
         */
        #rte-shortcode-dialog p {
            margin:10px 0;
        }

        #rte-shortcode-dialog label {
            display: block;
            margin: 5px 0px 2px 0px;
        }

        #rte-shortcode-dialog input[type=\"text\"]
        {
            width: 100%;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            padding: 4px;
        }

        #rte-shortcode-dialog .buttons {
            margin: 10px 0 8px;
            float: right;
        }

        #rte-shortcode-dialog .submit {
            cursor: pointer;
        }

        #rte-shortcode-dialog .notice {
            color: #CE0000;
            font-weight: bold;
            margin: 5px 0;
        }

        #rte-shortcode-link {
            cursor: pointer;
            margin-right: 1em;
        }

        #rte-shortcode-link:hover {
            text-decoration: underline;
        }

        #rte-shortcode-dialog-external {
            margin-top: 10px;
        }
        
        #rte-shortcode-dialog .edit_settings {
            display: none;
        }
END;
    }

    function definition()
    {
        // load the external file

        $author_macros = $this->lib->get_author_macros();
        $site_macros = $this->lib->get_site_macros();
        $shortcodes = $this->lib->get_shortcodes();

        $macro_options = array();
        $shortcode_options = array();
        $opts_forms = array();

        foreach(array_merge($site_macros, $author_macros) as $macro)
        {
            $macro_options[$macro->scope.':'.$macro->name] = '['.$macro->scope.':'.$macro->name.']';
        }
        ksort($macro_options);

        foreach($shortcodes as $shortcode)
        {
            $shortcode_options[$shortcode['_name']] = isset($shortcode['label']) ? $shortcode['label'] : '['.$shortcode['_name'].']';
            if(isset($shortcode['params']))
            {
//                 $shortcode['form'] = ' ';
//                 foreach($this->prolib->pl_forms->create_cp_form(array('form_name' => ''), $shortcode['params']) as $element)
//                 {
//                     $shortcode['form'] = $shortcode['form'] . '<label for="'.$element['lang_field'].'">'.
//                                             lang('field_'.$element['lang_field']).
//                                          '</label> '.$element['control'];
//                 }
//                 $opts_forms[$shortcode['_name']] = $shortcode['form'];
                $opts_forms[$shortcode['_name']] = $shortcode['params'];
            }
        }
        ksort($shortcode_options);

        $options = array();
        if(count($macro_options) > 0)
        {
            $options['Macros'] = array_unique($macro_options);
        }
        
        if(count($shortcode_options) > 0)
        {
            $options['Shortcodes'] = array_unique($shortcode_options);
        }

        $form_elements = $this->prolib->pl_forms->create_cp_form(
                array('macro' => ''),
                array('macro' => array('dropdown', $options, $opts_forms)),
                array(), array());

        $form = '';
        
        foreach($form_elements as $element)
        {
            $form .= '<label for="'.$element['lang_field'].'">'.lang('field_'.$element['lang_field']).'</label> '.$element['control'];
        }

        if(count($macro_options) == 0)
        {
            $form .= '<br/><div class="info"><b>Note: No Macros setup yet</b><br/>You can create some on the <b><a href="'.$this->_base_url.'">Shortcode module page</a></b>.</div>';
        }
        if(count($shortcode_options) == 0)
        {
            $form .= '<div class="info"><b>Note: No Shortcodes installed yet</b><br/>You can download some from <a href="http://metasushi.com">metasushi.com</a>.</div>';
        }
        
        $js = "\n\n";
        $js .= 'var SHORTCODE_OPTS = '.json_encode($opts_forms).";\n\n";
//         $js .= 'var SHORTCODE_SELECT = "'.str_replace("\n", "\\\n", addslashes(form_dropdown('macro', $options, $opts_forms))).'"';
        $js .= 'var SHORTCODE_SELECT = "'.str_replace("\n", "\\\n", addslashes($form)).'"';
        $js .= ";\n\n";
// echo $js;exit;
        //echo htmlentities($js);exit;
        return $js . file_get_contents('rte/rte.shortcode.js', TRUE);
    }
}

/* End of file rte.strip_tags.php */
/* Location: ./system/expressionengine/third_party/shortcode/rte.shortcode.php */
