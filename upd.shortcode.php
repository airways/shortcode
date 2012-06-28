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

class Shortcode_upd {

    public $version = '0.01';

    private $EE;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->EE =& get_instance();
    }

    public function install()
    {
        $mod_data = array(
            'module_name'           => SHORTCODE_NAME,
            'module_version'        => $this->version,
            'has_cp_backend'        => "y",
            'has_publish_fields'    => 'n'
        );

        $this->EE->db->insert('modules', $mod_data);

        $this->EE->load->dbforge();
        $forge = &$this->EE->dbforge;


        $fields = array(
            'macro_id'          => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'owner_id'          => array('type' => 'int', 'constraint' => '11'),
            'site_id'           => array('type' => 'int', 'constraint' => '4', 'default' => '1'),
            'type'              => array('type' => 'varchar', 'constraint' => '32'),
            'scope'             => array('type' => 'varchar', 'constraint' => '32', 'default' => 'mine'),
            'name'              => array('type' => 'varchar', 'constraint' => '255'),
            'value'             => array('type' => 'text'),
        );
        $forge->add_field($fields);
        $forge->add_key('macro_id', TRUE);
        $forge->add_key('owner_id');
        $forge->add_key('site_id');
        $forge->add_key('name');
        $forge->create_table('shortcode_macros');

        // Create Preferences table
        $fields = array(
            'preference_id'    => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'preference_name'  => array('type' => 'varchar', 'constraint' => '64'),
            'site_id'          => array('type' => 'int', 'constraint' => '4', 'default' => '1'),
            'value'            => array('type' => 'varchar', 'constraint' => '255'),
            'settings'         => array('type' => 'text')
        );
        $forge->add_field($fields);
        $forge->add_key('preference_id', TRUE);
        $forge->add_key('preference_name');
        $forge->create_table('shortcode_preferences');
        
        return TRUE;
    }

    public function uninstall()
    {
        $mod_id = $this->EE->db->select('module_id')
                               ->get_where('modules', array('module_name' => SHORTCODE_NAME))
                               ->row('module_id');

        $this->EE->db->where('module_id', $mod_id)
                     ->delete('module_member_groups');

        $this->EE->db->where('module_name', SHORTCODE_NAME)
                     ->delete('modules');

        $this->EE->load->dbforge();
        $this->EE->dbforge->drop_table('shortcode_macros');
        $this->EE->dbforge->drop_table('shortcode_preferences');

        return TRUE;
    }

    public function update($current = '')
    {
        
        return TRUE;
    }

}
/* End of file upd.shortcode.php */
/* Location: /system/expressionengine/third_party/shortcode/upd.shortcode.php */
