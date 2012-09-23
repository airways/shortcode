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

if(!file_exists(PATH_THIRD.'prolib/prolib.php'))
{
    echo 'Shortcode requires the prolib package. Please place prolib into your third_party folder.';
    exit;
}

require_once PATH_THIRD.'prolib/prolib.php';
require_once PATH_THIRD.'shortcode/config.php';

require_once PATH_THIRD.'shortcode/libraries/shortcode_macros.php';
require_once PATH_THIRD.'shortcode/libraries/shortcode_preferences.php';

class Shortcode_lib extends PL_base_lib {
    private static $get_instance = FALSE;
    private static $instance = null;

    var $default_prefs = array();
    var $site_macros = array();
    var $author_macros = array();
    var $shortcodes = array();

    public function __construct()
    {
        prolib($this, 'shortcode');

        // Throw an exception if the instance already exists, or if the constructor
        // is not being called inside of get_instance().
        if(isset(self::$instance) || !self::$get_instance)
        {
            throw new Exception('Invalid direct call to new instance of '.__CLASS__.' - use '.__CLASS__.'::get_instance() instead of creating an instance directly.');
        }

        parent::__construct();

        $this->macros = new Shortcode_macros($this);
        $this->prefs = new Shortcode_preferences($this);

        $this->vault = new PL_Vault('shortcode');
    }

    public static function get_instance()
    {
        if(!isset(self::$instance))
        {
            self::$get_instance = TRUE;         // Prevent the exception from being thrown
            self::$instance = new self();       // Create the single instance
            self::$get_instance = FALSE;        // Turn back on exception throwing
        }
        return self::$instance;             // Return the instance
    }

    public function get_site_macros($site_id=0)
    {
        if($site_id == 0) $site_id = $this->prolib->site_id;
        if(!isset($this->site_macros[$site_id]))
        {
            $this->site_macros[$site_id] = $this->macros->get_objects(array(
                'site_id' => $site_id,
                'scope' => 'site',

            ));
        }
        return $this->site_macros[$site_id];
    }

    public function get_author_macros($author_id=0)
    {
        if($author_id == 0) $author_id = $this->EE->session->userdata('member_id');
        if(!isset($this->author_macros[$author_id]))
        {
            $this->author_macros[$author_id] = $this->macros->get_objects(array(
                'site_id' => $this->prolib->site_id,
                'owner_id' => $author_id,
                'scope' => 'mine',
            ));
        }
        return $this->author_macros[$author_id];
    }

    /**
     * Returns an array of shortcode definitions provided by plugins and modules on the
     * site through their init_shortcodes() methods. Only add-ons that actually have this
     * method defined are initialized here - these add-ons need to not attempt to parse
     * any template data since they are only being loaded in order to get a list of the
     * shortcodes they support (basically this just means none of these add-ons can be
     * single segment tags, or they need to inspect the TMPL object to make sure that they
     * are being called).
     */
    public function get_shortcodes()
    {
        if(count($this->shortcodes) == 0)
        {
            $shortcodes = array();

            $packages = array_unique(array_keys(array_merge($this->EE->addons->_map['modules'], $this->EE->addons->_map['plugins'])));
            foreach($packages as $key)
            {
                $path = PATH_THIRD.$key;
                $class = false;

                // See if this is a module...
                if(file_exists($path.'/mod.'.$key.'.php'))
                {
                    require_once($path.'/mod.'.$key.'.php');
                    $class = $key;
                }

                // Or a plugin
                elseif(file_exists($path.'/plg.'.$key.'.php'))
                {
                    require_once($path.'/plg.'.$key.'.php');
                    $class = $key.'_plg';
                }

                /* Did we find a module, or plugin, does it exist, and does it have the
                   init_shortcodes() method? */
                if($class && class_exists($class) && method_exists($class, 'init_shortcodes'))
                {
                    /* Setup the package path so we can initialize this add-on and it
                       can find it's libraries and such. */
                    $this->EE->load->add_package_path($path);
                    $MOD = new $class();
                    $this->EE->load->remove_package_path($path);

                    /* Get the add-on's shortcodes. Note that it's perfectly fine for
                       an add-on to register shortcodes on another add-ons behalf to add
                       support if the original add-on doesn't support Shortcode. */
                    $mod_shortcodes = $MOD->init_shortcodes();
                    foreach($mod_shortcodes as $name => $info)
                    {
                        /* If the add-on doesn't specify a different class, assume it
                           wants us to call the tag method on itself. */
                        if(!isset($info['class']))
                        {
                            $info['class'] = $class;
                        }
                        $info['_name'] = $name;
                        $mod_shortcodes[$name] = $info;
                    }

                    $shortcodes = array_merge($shortcodes, $mod_shortcodes);
                }
            }

            /* Add some built in shortcodes, if the corresponding plugins/modules exist */
            if(file_exists(PATH_THIRD.'proform')) {
                $this->EE->lang->loadfile('proform');
                $form_options = array();

                // If ProForm is actually installed, get a list of it's forms and add a shortcode
                if($this->EE->db->table_exists('exp_proform_forms')) {
                    $forms = $this->EE->db->get('exp_proform_forms');
                    foreach($forms->result() as $form)
                    {
                        $form_options[$form->form_name] = $form->form_label;
                    }
                    ksort($form_options);

                    $shortcodes['form'] = array(
                            '_name' => 'form',
                            'class' => 'proform',
                            'method' => 'simple',
                            'label' => '[form] - ProForm Form',
                            'params' => array(
                                array('type' => 'dropdown', 'name' => 'form_name', 'label' => 'Form', 'options' => $form_options),
                            ),
                            'docs' => <<<END
<p>Allows you to insert a ProForm form anywhere in your content. Simply include the name of the form you wish to render:</p>
<code>
[form form_name="contact_us"]
</code>
END

                        );
                }
            }

            if(file_exists(PATH_THIRD.'will_hunting')) {
                $shortcodes['math'] = array(
                            '_name' => 'math',
                            'class' => 'will_hunting',
                            'method' => 'solve',
                            'label' => '[math] - Will Hunting',
                            'params' => array(
                                array('type' => 'input', 'name' => 'math_exp', 'label' => 'Math Expression'),
                            ),
                            'docs' => <<<END
<p>Triggers math evaluation for the given expression:</p>
<code>
[math math_exp="1+4*123"]
</code>
END
                        );
            }

            /* Cache our list so we don't have to do that again since this will be called
               at least twice on most pages. */
            $this->shortcodes = $shortcodes;
        }

        return $this->shortcodes;
    }

    /**
     * Parses macros defined for the active site and the active author (if given an
     * author_id), as well as any shortcodes provided by plugins or modules.
     */
    public function parse($content, $author_id=false)
    {
        // Get all site macros
        $macros = $this->get_site_macros();

        // Get all macros from the entry author
        if($author_id)
        {
            $mine = $this->get_author_macros($author_id);
            $macros = array_merge($macros, $mine);
        }

        // Replace simple macros
        foreach($macros as $macro)
        {
            $content = str_replace('['.$macro->scope.':'.$macro->name.']', $macro->value, $content);
        }

        /* Only bother with full shortcodes if we are parsing a specific entry (and
           therefore got it's author_id) - these will not work in the final output parsing
           since plugins and modules have already been parsed by then. */
        if($author_id)
        {
            $shortcodes = $this->get_shortcodes();

            // Replace full shortcodes with template tag calls to the designated package / method
            foreach($shortcodes as $shortcode => $info)
            {
                $content = preg_replace('#\['.$shortcode.'(.*?)\]#',
                                        '{exp:'.$info['class'].':'.$info['method'].' \1}',
                                        $content);
            }
        }

        return $content;
    }
}
