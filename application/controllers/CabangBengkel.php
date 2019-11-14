<?php
use Restserver \Libraries\REST_Controller ;
Class CabanBengkel extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('CabangBengkelModel');
        $this->load->library('form_validation');
    }
    public function bengkel_get(){
        return $this->returnData($this->db->get('branches')->result(), false);
    }
    public function bengkel_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->CabangBengkelModel->rules();
        if($id == null){
            
            array_push($rule,[
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'required'
                ],
                [ 
                    'field' => 'address',
                    'label' => 'address',
                    'rules' => 'required'
                ],
                [
                    'field' => 'phoneNumber',
                    'label' => 'phoneNumber',
                    'rules' => 'required|is_unique[branches.phoneNumber]'
                ]
            );
        }
        else{
            array_push($rule,
                [
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'required'
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $bengkel = new BengkelData();
        $bengkel->name = $this->post('name');
        $bengkel->address = $this->post('address');
        $bengkel->phoneNumber = $this->post('phoneNumber');
        if($id == null){
            $response = $this->CabangBengkelModel->store($bengkel);
        }else{
            $response = $this->CabangBengkelModel->update($bengkel,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
    public function bengkel_delete($id = null){
        if($id == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->CabangBengkelModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }
    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}
Class BengkelData{
    public $name;
    public $address;
    public $phoneNumber;
}