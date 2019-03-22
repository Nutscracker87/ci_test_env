<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

    /**
     * is value exist
     *
     * @param [string] $str
     * @param [string] $field
     * @return boolean
     */
    public function exist($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
		return isset($this->CI->s)
			? ($this->CI->s->from($table)->where($field, $str)->count() > 0)
			: FALSE;
    }

    /**
     * is parent node exist
     *
     *
     * @param [string] $str
     * @param [string] $field
     * @return boolean
     */
    public function parent_available($str, $field)
    {
        if($str == 0) return TRUE;
        if($this->exist($str, $field) === FALSE) return FALSE;

        return TRUE;
    }

    /**
     * is parent available(exist and level < 3) for new children
     * max levels = 2
     *
     * @param [string] $str
     * @param [string] $field
     * @return boolean
     */

    public function three_levels_max_tree_depth($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);

        $i = 0;
        $pr = TRUE;

        while ($pr && $i < 4) {
            $res = $this->CI->s->from($table)->where($field, $str)->one();
            $str = !$res ? $str : $res['parent_id'];
            $pr = empty($res) ? FALSE : TRUE;
            $i++;
        }

        return $i < 4;
    }

}