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

class Shortcode {
    
    public $return_data;

    public function __construct()
    {
        prolib($this, 'shortcode');
        $this->lib = &Shortcode_lib::get_instance();
    }
    
    public function parse()
    {
        $p_author_id = $this->EE->TMPL->fetch_param('author_id');
        return $this->lib->parse($this->EE->TMPL->tagdata, $p_author_id);
    }
    
    public function tweet()
    {
        $p_id = $this->EE->TMPL->fetch_param('id');
        $p_url = $this->EE->TMPL->fetch_param('url');
        
        $params = false;
        if($p_id) $params = 'id='.$p_id;
        if($p_url) $params = 'url='.$p_url;
        
        if($params)
        {
            if($html = $this->lib->vault->get(md5('twitter'.$params)))
            {
                return $html;
            } else {
                $response = file_get_contents('https://api.twitter.com/1/statuses/oembed.xml?'.$params);
                if($response && $xml = new SimpleXMLElement($response))
                {
                    $this->lib->vault->put((string)$xml->html, true, md5('twitter'.$params));
                    return $xml->html;
                } else {
                    return '[tweet - invalid response from Twitter API]';
                }
            }
        } else {
            return '[tweet - no ID or URL paremter provided]';
        }
    }

}
/* End of file mod.shortcode.php */
/* Location: /system/expressionengine/third_party/shortcode/mod.shortcode.php */
