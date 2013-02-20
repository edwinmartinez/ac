class Content extends CI_Controller {

    public function privacy_policy()
    {
        $this->load->view('privacy_policy');
    }

    public function terms_of_service()
    {
        $this->load->view('terms_of_service');
    }

}