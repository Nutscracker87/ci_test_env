<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Comments extends MY_Controller
{
    protected $response_data;

    private $user = 1;

    public function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
        $this->load->model('news_comments_model');
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

    /**
     * create comment
     *
     * @return void
     */
    public function create()
    {
        $data = $this->input->post();
        $this->form_validation->set_data($data);

        $this->form_validation->set_rules('text', 'text', 'trim|required|min_length[3]');
        // $this->form_validation->set_rules('user_id', 'user_id', 'trim|required|numeric');
        $this->form_validation->set_rules('news_id', 'news_id', 'trim|required|numeric|exist[news.id]');
        $this->form_validation->set_rules(
            'parent_id',
            'parent_id',
            'trim|required|numeric|parent_available[comments.id]|three_levels_max_tree_depth[comments.id]'
        );

        $this->response_data->data = new stdClass();
        $this->response_data->data->patch_notes = '';
        if ($this->form_validation->run() !== TRUE)
        {
            $this->response_data->status = 'error';
            $this->response_data->error_message = implode(' ', $this->form_validation->error_array());
            $this->response($this->response_data);
            return false;
        }

        $commentData = [
            'text' => $this->input->post('text'),
            'news_id' => $this->input->post('news_id'),
            'user_id' => $this->user,
            'parent_id' => $this->input->post('parent_id')
        ];

        $comment = News_comments_model::create($commentData);

        $commentData['id'] = $comment->get_id();
        $this->response_data->data->comment = $commentData ;

        $this->response($this->response_data);
        return false;
    }

    /**
     * delete comment
     *
     * @param [intger] $id
     * @return void
     */
    public function delete($id)
    {
        $this->form_validation->set_data(['id' => $id]);
        $this->form_validation->set_rules('id', 'id', 'trim|required|numeric|exist[comments.id]');

        $this->response_data->data = new stdClass();
        $this->response_data->data->patch_notes = '';
        if ($this->form_validation->run() !== TRUE)
        {
            $this->response_data->status = 'error';
            $this->response_data->error_message = implode(' ', $this->form_validation->error_array());
            $this->response($this->response_data);
            return false;
        }

        $removedComments = News_comments_model::delete($id);

        News_like_model::deleteRelated($removedComments);

        $this->response_data->data->comments = $removedComments;
        $this->response($this->response_data);
        return false;
    }

    public function like($id)
    {
        $this->form_validation->set_data(['id' => $id]);
        $this->form_validation->set_rules('id', 'id', 'trim|required|numeric|exist[comments.id]');

        $this->response_data->data = new stdClass();
        $this->response_data->data->patch_notes = '';
        if ($this->form_validation->run() !== TRUE)
        {
            $this->response_data->status = 'error';
            $this->response_data->error_message = implode(' ', $this->form_validation->error_array());
            $this->response($this->response_data);
            return false;
        }

        if(News_like_model::is_liked($id, $this->user, 'comment')) {
            $this->response_data->status = 'error';
            $this->response_data->error_message = 'This comment was liked before';
            $this->response($this->response_data);
            return false;
        }

        $this->response_data->data->like = News_like_model::like($id, $this->user, 'comment');
        $this->response($this->response_data);
        return false;
    }

    public function unlike($id)
    {
        $this->form_validation->set_data(['id' => $id]);
        $this->form_validation->set_rules('id', 'id', 'trim|required|numeric|exist[comments.id]');

        $this->response_data->data = new stdClass();
        $this->response_data->data->patch_notes = '';
        if ($this->form_validation->run() !== TRUE)
        {
            $this->response_data->status = 'error';
            $this->response_data->error_message = implode(' ', $this->form_validation->error_array());
            $this->response($this->response_data);
            return false;
        }

        if( !News_like_model::is_liked($id, $this->user, 'comment')) {
            $this->response_data->status = 'error';
            $this->response_data->error_message = 'This comment was not liked before';
            $this->response($this->response_data);
            return false;
        }

        News_like_model::unlike($id, $this->user, 'comment');
        $this->response_data->data->like = $id;
        $this->response($this->response_data);
        return false;
    }

}
