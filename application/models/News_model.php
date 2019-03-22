<?php
/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 10:10
 */
// use News_comments_model;
//require_once 'News_comments_model.php';

class News_model extends MY_Model
{
    const NEWS_TABLE = APPLICATION_NEWS;
    const PAGE_LIMIT = 5;
    protected $id;
    protected $header;
    protected $short_description;
    protected $text;
    protected $img;
    protected $tags;
    protected $time_created;
    protected $time_updated;
    protected $views;
    protected $comments;
    protected $likes;

    function __construct($id = FALSE)
    {
        parent::__construct();

        $CI =& get_instance();
        $CI->load->model('news_comments_model');
        $CI->load->model('news_like_model');

        $this->comments = $CI->news_comments_model;
        $this->likes = $CI->news_like_model;

        $this->class_table = self::NEWS_TABLE;
        $this->set_id($id);
    }

    /**
     * @return string
     */
    public function get_header()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function set_header($header)
    {
        $this->header = $header;
        return $this->_save('header', $header);
    }

    /**
     * @return string
     */
    public function get_short_description()
    {
        return $this->short_description;
    }

    /**
     * @param mixed $description
     */
    public function set_short_description($description)
    {
        $this->short_description = $description;
        return $this->_save('short_description', $description);
    }

    /**
     * @return string
     */
    public function get_full_text()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function get_image()
    {
        return $this->img;
    }

    /**
     * @param mixed $image
     */
    public function set_image($image)
    {
        $this->img = $image;
        return $this->_save('image', $image);
    }

    /**
     * @return string
     */
    public function get_tags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function set_tags($tags)
    {
        $this->tags = $tags;
        return $this->_save('tags', $tags);
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
        return $this->likes->get_likes($this->id, 'news', 'short_info');
    }

    /**
     * @return News_comments_model[]
     */
    public function get_comments()
    {
        return $this->comments->get_news_comments($this->id, 'short_info');
    }

    /**
     * @param int $id
     * @return boolean
     */
    public static function is_exist($id)
    {
        $CI =& get_instance();
        $_data = $CI->s->from(self::NEWS_TABLE)
            ->where('id', $id)->count();
        if(!$_data) {
            return false;
        }

        return true;
    }

    /**
     * @param int $id
     * @param bool|string $preparation
     * @return array
     */
    public static function get_one($id, $preparation = FALSE)
    {
        $CI =& get_instance();
        $_data = $CI->s->from(self::NEWS_TABLE)
            ->where('id', $id)->many();
        $news_list = [];
        foreach ($_data as $_item) {
            $news_list[] = (new self())->load_data($_item);
        }

        if ($preparation === FALSE || empty($news_list)) {
            return $news_list;
        }

        return self::preparation($news_list, $preparation)[0];
    }

    /**
     * @param int $page
     * @param bool|string $preparation
     * @return array
     */
    public static function get_all($preparation = FALSE)
    {
        $CI =& get_instance();
        $_data = $CI->s->from(self::NEWS_TABLE)
                ->sortDesc('time_created')
                ->many();
        $news_list = [];
        foreach ($_data as $_item) {
            $news_list[] = (new self())->load_data($_item);
        }
        if ($preparation === FALSE) {
            return $news_list;
        }
        return self::preparation($news_list, $preparation);
    }

    /**
     * @param int $page
     * @param bool|string $preparation
     * @return array
     */
    public static function get_latest($preparation = FALSE)
    {
        $CI =& get_instance();
        $_data = $CI->s->from(self::NEWS_TABLE)
            ->sortDesc('time_created')
            ->limit(3)
            ->many();
        $news_list = [];
        foreach ($_data as $_item) {
            $news_list[] = (new self())->load_data($_item);
        }
        if ($preparation === FALSE) {
            return $news_list;
        }
        return self::preparation($news_list, $preparation);
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
            case 'full_info':
                return self::_preparation_full_info($data);
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
        $res = self::_preparation_full_info($data);
        foreach ($res as $key => $item) {
            unset($res[$key]->text);
            $res[$key]->comments = count($res[$key]->comments);
            $res[$key]->likes = count($res[$key]->likes);
        }
        return $res;
    }

    /**
     * @param News_model[] $data
     * @return array
     */
    private static function _preparation_full_info($data)
    {
        $res = [];
        foreach ($data as $item) {
            $_info = new stdClass();
            $_info->id = (int)$item->get_id();
            $_info->header = $item->get_header();
            $_info->description = $item->get_short_description();
            $_info->text = $item->get_full_text();
            $_info->img = $item->get_image();
            $_info->time = $item->get_time_updated();
            $_info->comments = $item->get_comments();
            $_info->likes = $item->get_likes();
            $res[] = $_info;
        }
        return $res;
    }

    public static function create($data){
        $CI =& get_instance();
	    $res = $CI->s->from(self::NEWS_TABLE)->insert($_insert_data)->execute();
	    if(!$res){
	        return FALSE;
        }
	    return new self($CI->s->insert_id);
    }



}