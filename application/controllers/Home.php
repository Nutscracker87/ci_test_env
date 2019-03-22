<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Home extends MY_Controller
{
    protected $response_data;

    private $user = 1;

    public function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
    }

    /**
     * create comment
     *
     * @return void
     */
    public function index()
    {
        $this->load->view('base/index');
    }

}
