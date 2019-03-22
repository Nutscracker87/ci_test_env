<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class News extends MY_Controller
{
    protected $response_data;

    private $user = 1;

    public function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
        $this->load->model('news_like_model');
        $this->load->model('news_model');
        $this->load->library('form_validation');

        $this->response_data = new stdClass();
        $this->response_data->status = 'success';
        $this->response_data->error_message = '';
        $this->response_data->data = new stdClass();

        if (ENVIRONMENT === 'production')
        {
            die('Access denied!');
        }
    }

    public function index()
    {
        $this->all();
    }

    /**
     * all news
     *
     * @return json
     */
    public function all()
    {
        $this->response_data->data->news = News_model::get_all('short_info');
        $this->response_data->data->patch_notes = '';
        $this->response($this->response_data);
    }

    /**
     * latest 3 news
     *
     * @return json
     */
    public function latest()
    {
        $this->response_data->data->news = News_model::get_all('short_info');
        $this->response_data->data->patch_notes = '';
        $this->response($this->response_data);
    }

    /**
     * Single post
     *
     * @param [integer] $id
     * @return json
     */
    public function post($id)
    {
        $this->response_data->data->news = News_model::get_one($id, 'full_info');
        $this->response_data->data->patch_notes = '';
        $this->response($this->response_data);
    }

    public function like($id)
    {
        $this->form_validation->set_data(['id' => $id]);
        $this->form_validation->set_rules('id', 'id', 'trim|required|numeric|exist[news.id]');

        $this->response_data->data = new stdClass();
        $this->response_data->data->patch_notes = '';
        if ($this->form_validation->run() !== TRUE)
        {
            $this->response_data->status = 'error';
            $this->response_data->error_message = implode(' ', $this->form_validation->error_array());
            $this->response($this->response_data);
            return false;
        }

        if(News_like_model::is_liked($id, $this->user, 'news')) {
            $this->response_data->status = 'error';
            $this->response_data->error_message = 'This news was liked before';
            $this->response($this->response_data);
            return false;
        }

        $this->response_data->data->like = News_like_model::like($id, $this->user, 'news');
        $this->response($this->response_data);
        return false;
    }

    public function unlike($id)
    {
        $this->form_validation->set_data(['id' => $id]);
        $this->form_validation->set_rules('id', 'id', 'trim|required|numeric|exist[news.id]');

        $this->response_data->data = new stdClass();
        $this->response_data->data->patch_notes = '';
        if ($this->form_validation->run() !== TRUE)
        {
            $this->response_data->status = 'error';
            $this->response_data->error_message = implode(' ', $this->form_validation->error_array());
            $this->response($this->response_data);
            return false;
        }

        if( !News_like_model::is_liked($id, $this->user, 'news')) {
            $this->response_data->status = 'error';
            $this->response_data->error_message = 'This news was not liked before';
            $this->response($this->response_data);
            return false;
        }

        News_like_model::unlike($id, $this->user, 'news');
        $this->response_data->data->like = $id;
        $this->response($this->response_data);
        return false;
    }

}
