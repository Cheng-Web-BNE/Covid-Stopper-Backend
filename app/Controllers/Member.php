<?php
namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\LabStaffModel;
use CodeIgniter\Model;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use \Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

/**
 * Class Member
 * @package App\Controllers
 */
class Member extends BaseController
{


    /*
     * This is not a valid API. So return False
     */
    public function index()
    {
        return false;
    }

    //--------------------------------------------------------------------
    public function register($role='general')
    {

        // Takes raw data from the request
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        // Converts it into a PHP object
        if (
            isset($data["email"]) &&
            isset($data["password"]) &&
            ($role == 'general' || $role == 'commercial' )) {
            $model = new MemberModel();

            if ($role == "general" &&
                isset($data["phone_number"]) &&
                isset($data["first_name"]) &&
                isset($data["last_name"])){
                if (!$model->isSpecificMemberExist($data['phone_number'])) {
                    $result = $model->addMemberToRelation($data,$role);
                    return ($this->generateToken($result,$role));
                }
            } elseif ($role == "commercial" &&
                isset($data["name"]) &&
                isset($data["phone_number"])){
                if (!$model->isSpecificCommercialMemberExist($data['name'])) {
                    $result = $model->addMemberToRelation($data,$role);
                    return ($this->generateToken($result,$role));
                }
            } else{
                return $this->respond("The Information provided might be wrong", 400);
            }
            return $this->respond("The Information provided might already existed in the database", 406);
        }
        return $this->respond("The Information provided might be wrong", 400);
    }

    public function isVerifiedToken($token)
    {
        return(parent::isVerifiedToken($token));
    }

    /**
     * @param $token - JWT token
     * @return string JWT token value
     */
    protected function verifyToken($token) {
        return(parent::verifyToken($token));
    }


    protected function generateToken($user_id,$role)
    {
        return(parent::generateToken($user_id,$role));
    }

    /*function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        return $headers;
    }

    function getBearerToken() {
        $authHeaders = $this->getAuthorizationHeader();

        if (!empty($auheaders)) {
            $authArr = explode(" ", $authHeaders);
            $token = $authArr[1];
            return $token;
        } else {
            return null;
        }
    }*/

    function login() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (isset($data['role'])){
            $role = $data['role'];
            unset($data['role']);
        }else{
            return $this->respond("The Information provided might be wrong", 400);
        }

        if (isset($data["password"])) {
            $model = new MemberModel();
            if ($role == "general" && isset($data["phone_number"])) {
                $result = $model ->login($data["phone_number"],$data["password"] );
            } elseif ($role == "commercial" && isset($data["name"])) {
                $result = $model ->commercialLogin($data["name"],$data["password"] );
            } else{
                return $this->respond("The Information provided might be wrong", 400);
            }

            if ($result != null) {
                return($this->generateToken($result,$role));
            } else {
                return $this->respond("Wrong phone number or password", 406);
            }
        }
        return $this->respond("The Information provided might be wrong", 400);
    }


    /**
     * Read a member's information
     * @return false|mixed|string status 200 - a JSON formatted file | status 406 - Sorry, you need login first
     */
    public function readMember(){
        $data = $this->getPostedData();
        if ($this->isLogin()) {
            $user_id = $this->verifyToken($data['token']);
            if ($user_id == false){
                return $this->respond("Sorry, you might need re-login.", 406);
            } elseif ($this->getRoleInToken($data['token']) != "general"){
                return $this->respond("Wrong User Type",400);
            }
            $model = new MemberModel();
            return  json_encode($model->readMemberFromRelation($user_id));
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    public function readCommercialMember(){
        $data = $this->getPostedData();
        if ($this->isLogin()) {
            $user_id = $this->verifyToken($data['token']);
            if ($user_id == false){
                return $this->respond("Sorry, you might need re-login.", 406);
            } elseif ($this->getRoleInToken($data['token']) != "commercial"){
                return $this->respond("Wrong User Type",400);
            }
            $model = new MemberModel();
            return  json_encode($model->readCommercialMemberFromRelation($user_id));
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }


    /**
     * Update a member's information
     * @return mixed status 200 - Update information successfully | status 400 - Something wrong |
     * status 406 - Sorry, you need login first
     */
    public function updateMember()
    {
        $data = $this->getPostedData();
        if ($this->isLogin()) {
            $user_id = $this->verifyToken($data['token']);
            if ($user_id == false){
                return $this->respond("Sorry, you might need re-login.", 406);
            } elseif ($this->getRoleInToken($data['token']) != "general"){
                return $this->respond("Wrong User Type",400);
            }
            unset($data['token']);
            $model = new MemberModel();
            if ($model->updateMemberToRelation($user_id,$data)) {
                return $this->respond("Update information successfully", 200);
            } else {
                return $this->respond("Something wrong", 400);
            }

        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    public function updateCommercialMember()
    {
        $data = $this->getPostedData();
        if ($this->isLogin()) {
            $user_id = $this->verifyToken($data['token']);
            if ($user_id == false){
                return $this->respond("Sorry, you might need re-login.", 406);
            } elseif ($this->getRoleInToken($data['token']) != "commercial"){
                return $this->respond("Wrong User Type",400);
            }
            unset($data['token']);
            $model = new MemberModel();
            if ($model->updateCommercialMemberToRelation($user_id,$data)) {
                return $this->respond("Update information successfully", 200);
            } else {
                return $this->respond("Something wrong", 400);
            }

        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    protected function generateLabToken($lab_user_id){
        $payload = array(
            "exp" => time()+(60*60*24),
            "val" => strval($lab_user_id),
            "role"=> "general"
        );
        return strval(JWT::encode($payload, JWT_KEY));
    }

    public function labStaffLogin(){
        if ($this->checkLabLoginStatus() != false){
            return redirect()->to(base_url('/LabStaff'));
        }
        if (!isset($_POST['username'])){
            return view('lab_staff/login');
        } else{
            if (isset($_POST['username']) && isset($_POST['password'])){
                $model = new LabStaffModel();
                $lab_user_id = $model->login($_POST['username'],$_POST['password']);
                if ($lab_user_id != null){
                    session()->set('jwt', $this->generateLabToken($lab_user_id));
                    session()->set('name',$model->readSpecificUser($lab_user_id)->name);
                    session()->set('jwt', $this->generateLabToken($lab_user_id));
                    return redirect()->to(base_url('/LabStaff'));
                } else{
                    return view('lab_staff/login',['error'=>'Invalid Username/Password']);
                }

            } else{
                return view('lab_staff/login',['error'=>'Please Enter the Password']);
            }
        }
    }

    public function labLogOut(){
        session_destroy();
        return redirect()->to(base_url('/LabStaff'));
    }

}
