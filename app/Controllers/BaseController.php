<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CODEIGNITER\API\ResponseTrait;
use CodeIgniter\Config\Services;
use CodeIgniter\Controller;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

class BaseController extends Controller
{
    use ResponseTrait;



	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	protected $session;
	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
        require_once("../app/Libraries/Token/BeforeValidException.php");
        require_once("../app/Libraries/Token/ExpiredException.php");
        require_once("../app/Libraries/Token/JWK.php");
        require_once("../app/Libraries/Token/JWT.php");
        require_once("../app/Libraries/Token/SignatureInvalidException.php");
        require_once("../app/Config/GlobalVariables.php");
        define("TEST_PASSWORD","shangshangxiaxiazuozuoyouyouba");
        define("JWT_KEY","Hey DodoLab!");

        if (session_status() == PHP_SESSION_NONE)
        {
            $session = Services::session();
        }
	}

    /**
     * According to user's id, generating the JWT token.
     * @param $user_id user's id
     * @param $role user's role
     */
    protected function generateToken($user_id, $role) {

        $payload = array(
            "exp" => time()+(60*60*24),
            "val" => strval($user_id),
            "role" => $role
        );
        $token = strval(JWT::encode($payload, JWT_KEY));


        printf($token);
    }

    /**
     * Verify JWT token
     * @param $token user's JWT token sent from font-end
     * @return string JWT token value
     */
    protected function verifyToken($token) {
        try {
            $decoded = JWT::decode($token,JWT_KEY, array('HS256'));
        } catch (ExpiredException $e){
            return("It's expired.");
        }
        if ($decoded->role == "general" || $decoded->role == "commercial"){
            return($decoded->val);
        }
        return false;
    }

    protected function getRoleInToken($token){
        try {
            $decoded = JWT::decode($token,JWT_KEY, array('HS256'));
        } catch (ExpiredException $e){
            return("It's expired.");
        }
        if ($decoded->role == "general" || $decoded->role == "commercial"){
            return($decoded->role);
        }
        return false;
    }

    /**
     * Check the token is verified or not.
     * @param $token user's JWT token sent from font-end
     * @return bool false - the token is not verified. true - the token is verified.
     */
    public function isVerifiedToken($token)
    {
        try {
            $decoded = JWT::decode($token,JWT_KEY, array('HS256'));
        } catch (ExpiredException $e){
            return false;
        }
        if ($decoded->role == "general" || $decoded->role == "commercial"){
            return true;
        }
        return false;
    }

    /**
     * Check the user is login or not
     * @return bool true - the user is login. false - the user is not login
     */
    protected function isLogin() {
	    $token = $this->getPostedData()['token'];
        return $this->isVerifiedToken($token);
    }

    /**
     * Takes a JSON encoded string and converts it into a PHP variable.
     * @return mixed Returns the value encoded in json in appropriate PHP type
     */
    protected function getPostedData(){
	    if (isset($_POST['token'])){
	        return $_POST;
        }

        return json_decode(file_get_contents('php://input'), true);
    }

    protected function verifyLabToken($token){
        try {
            $lab_token = JWT::decode($token,JWT_KEY, array('HS256'));
        } catch (ExpiredException $e){
            return false;
        } catch (SignatureInvalidException $e){
            return false;
        } catch (BeforeValidException $e){
            return false;
        }

        return $lab_token->val;
    }

    protected function checkLabLoginStatus(){
        if (session()->get('jwt') != null){
            $token = $this->verifyLabToken(session()->get('jwt'));
            if ($token){
                return $token;
            }
        }

        return false;
    }
}
