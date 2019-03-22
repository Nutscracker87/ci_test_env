<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 10:10
 */
// namespace News_comments_model;

class News_like_model extends MY_Model
{
    const LIKES_TABLE = 'likes';
    const PAGE_LIMIT = 5;

    protected $id;
    protected $entity_id;
    protected $entity;
    protected $user_id;

    protected $time_created;
    protected $time_updated;

    protected $views;

    function __construct($id = FALSE)
    {
        parent::__construct();
        $this->class_table = self::LIKES_TABLE;
        $this->set_id($id);
    }

    /**
     * @return integer
     */
    public function get_entity_id()
    {
        return (int) $this->entity_id;
    }

    /**
     * @return string
     */
    public function get_entity()
    {
        return $this->entity;
    }

    /**
     * @return integer
     */
    public function get_user_id()
    {
        return (int) $this->user_id;
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
    public function get_likes($id, $entity = 'news', $preparation = FALSE)
    {
        $CI =& get_instance();
        $_data = $CI->s->from(self::LIKES_TABLE)
                    ->where('entity_id', $id)
                    ->where('entity', $entity)
                    ->many();
        $likes = [];
        foreach ($_data as $_item) {
            $likes[] = (new self())->load_data($_item);
        }

        if ($preparation === FALSE) {
            return $likes;
        }

        return self::preparation($likes, $preparation);
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
            $_info->id = (int)$item->get_id();
            $_info->user_id = $item->get_user_id();
            $_info->time = $item->get_time_updated();
            $res[] = $_info;
        }
        return $res;
    }

    public static function create($_insert_data){

        $CI =& get_instance();
	    $res = $CI->s->from(self::LIKES_TABLE)->insert($_insert_data)->execute();
	    if(!$res){
	        return FALSE;
        }
	    return new self($CI->s->insert_id);
    }

    public static function deleteRelated($removedComments, $entity = 'comment')
    {

        $CI =& get_instance();

        $res = $CI->s->from(self::LIKES_TABLE)
            ->where('entity_id @', $removedComments)
            ->where('entity', $entity)
            ->delete()
            ->execute();

	    if(!$res){
	        return FALSE;
        }

	    return TRUE;
    }

    public static function is_liked($entity_id, $user_id, $entity = 'comment')
    {

        $CI =& get_instance();
        $res = $CI->s->from(self::LIKES_TABLE)
            ->where('user_id', $user_id)
            ->where('entity_id', $entity_id)
            ->where('entity', $entity)
            ->count();

        if($res > 0){
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Like
     *
     * @param [integer] $entity_id
     * @param [integer] $user_id
     * @param string $entity
     * @return void
     */
    public static function like($entity_id, $user_id, $entity = 'comment')
    {

        $CI =& get_instance();
        $res = $CI->s->from(self::LIKES_TABLE)->insert([
            'entity_id' => $entity_id,
            'user_id' => $user_id,
            'entity' => $entity,
        ])->execute();

        if(!$res){
            return FALSE;
        }

        $like = new self($CI->s->insert_id);
        return self::preparation([$like], 'short_info')[0];
    }

    /**
     * Unlike
     *
     * @param [integer] $entity_id
     * @param [integer] $user_id
     * @param string $entity
     * @return void
     */
    public static function unlike($entity_id, $user_id, $entity = 'comment')
    {

        $CI =& get_instance();

        $res = $CI->s->from(self::LIKES_TABLE)
            ->where('entity_id', $entity_id)
            ->where('entity', $entity)
            ->where('user_id', $user_id)
            ->delete()
            ->execute();

	    if(!$res){
	        return FALSE;
        }

	    return TRUE;
    }
}
