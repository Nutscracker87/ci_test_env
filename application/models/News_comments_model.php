<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 10:10
 */
// namespace News_comments_model;

class News_comments_model extends MY_Model
{
    const COMMENTS_TABLE = 'comments';
    const PAGE_LIMIT = 5;

    protected $id;
    protected $text;
    protected $news_id;
    protected $parent_id;
    protected $level;

    protected $time_created;
    protected $time_updated;

    protected $views;

    // protected $comments;
    protected $likes;

    function __construct($id = FALSE)
    {
        parent::__construct();
        $this->class_table = self::COMMENTS_TABLE;
        $this->set_id($id);

        $CI =& get_instance();
        $CI->load->model('news_like_model');
        $this->likes = $CI->news_like_model;
    }


    /**
     * @return integer
     */
    public function get_parent_id()
    {
        return (int) $this->parent_id;
    }

    /**
     * @return string
     */
    public function get_text()
    {
        return $this->text;
    }


    /**
     * @return mixed
     */
    public function get_time_created()
    {
        return $this->time_created;
    }

    /**
     * @param mixed $time_created
     */
    public function set_time_created($time_created)
    {
        $this->time_created = $time_created;
        return $this->_save('time_created', $time_created);
    }

    /**
     * @return int
     */
    public function get_time_updated()
    {
        return strtotime($this->time_updated);
    }

    /**
     * @param mixed $time_updated
     */
    public function set_time_updated($time_updated)
    {
        $this->time_updated = $time_updated;
        return $this->_save('time_updated', $time_updated);
    }

    /**
     * @return News_like_model[]
     */
    public function get_likes()
    {
        return $this->likes->get_likes($this->id, 'comment', 'short_info');
    }

    public function get_news_comments($id, $preparation = false)
    {

        $CI =& get_instance();
        // $_data = $CI->s->from(self::COMMENTS_TABLE)->where('news_id', $id)->many();
        $_data = $CI->s->from(self::COMMENTS_TABLE)->where('news_id', $id)->many();
        $comments = [];
        foreach ($_data as $_item) {
            $comments[] = (new self())->load_data($_item);
        }

        if ($preparation === FALSE) {
            return $comments;
        }

        return self::preparation($comments, $preparation);


        // // $this->db->select_max('level');
        // //  $max=  $this->db->get('comments')->row();
        // $this->db->where('news_id', $id);
        // // $this->db->join('users', 'users.id = comments.user_id', 'inner');
        // $this->db->order_by('comment_id', 'asc');
        // $data = $this->db->get('comments')->result_array();
        // $count = $this->db->count_all_results('comments');
        // $comments = array();
        // for ($i = 0; $i <= $count - 1; $i++) {
        //     if ($data[$i]['level'] == 0) {
        //         $comments[$i][0] = $data[$i];
        //         $indent = 0;
        //         for ($j = 0; $j <= $count - 1; $j++) {
        //             if ($data[$j]['parent_id'] == $data[$i]['comment_id']) {
        //                 $lastParentId = $data[$j]['comment_id'];
        //                 if ($lastLevel = $data[$j]['level']) {
        //                     $comments[$i][$data[$j]['level'] + $indent] = $data[$j];
        //                     $lastLevel = $data[$j]['level'];
        //                     $indent++;
        //                 }
        //             }
        //             if ((isset($lastParentId)) && ($data[$j]['parent_id'] == $lastParentId)) {
        //                 if ($lastLevel = $data[$j]['level']) {
        //                     $lastParentId = $data[$j]['comment_id'];
        //                     $comments[$i][$data[$j]['level'] + $indent] = $data[$j];
        //                     $lastLevel = $data[$j]['level'];
        //                     $indent++;
        //                 }
        //             }
        //         }
        //     }
        // }
        // return $comments;
    }

    /**
     *
     * @param [array] $data
     * @param [string] $preparation
     * @return void
     */
    public static function preparation($data, $preparation)
    {
        switch ($preparation) {
            case 'short_info':
                return self::_preparation_short_info($data);
            default:
                throw new Exception('undefined preparation type');
        }
    }

    /**
     * @param News_model[] $data
     * @return array
     */
    private static function _preparation_short_info($data)
    {
        $res = [];

        foreach ($data as $item) {
            $_info = new stdClass();
            $_info->id = $item->get_id();
            $_info->parent_id = $item->get_parent_id();
            $_info->text = $item->get_text();
            $_info->time = $item->get_time_updated();
            $_info->likes = $item->get_likes();
            $res[] = $_info;
        }
        return $res;
    }

    public static function create($_insert_data){

        $CI =& get_instance();
	    $res = $CI->s->from(self::COMMENTS_TABLE)->insert($_insert_data)->execute();
	    if(!$res){
	        return FALSE;
        }
	    return new self($CI->s->insert_id);
    }

    public static function delete($id){

        $CI =& get_instance();

        $commentsForDelete = self::getChildren($id);
        $commentsForDelete = array_map(function($item) {
            return $item['id'];
        }, $commentsForDelete);
        $commentsForDelete = array_merge($commentsForDelete, [$id]);

        $res = $CI->s->from(self::COMMENTS_TABLE)
            ->where('id @', $commentsForDelete)->delete()->execute();

	    if(!$res){
	        return FALSE;
        }

	    return $commentsForDelete;
    }

    public static function getChildren($parent) {

        $CI =& get_instance();
        $children = array();
        // grab the posts children
        $comments = $CI->s->from(self::COMMENTS_TABLE)->where('parent_id', $parent)->many();
        // now grab the grand children
        foreach( $comments as $child ){
            // recursion!! hurrah
            $gchildren = self::getChildren($child['id']);
            // merge the grand children into the children array
            if( !empty($gchildren) ) {
                $children = array_merge($children, $gchildren);
            }
        }
        // merge in the direct descendants we found earlier
        $children = array_merge($children, $comments);

        return $children;
    }
}
