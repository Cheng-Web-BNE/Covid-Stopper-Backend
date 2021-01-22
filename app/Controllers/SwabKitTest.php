<?php namespace App\Controllers;


use App\Models\SwabKitModel;

/**
 * Class SwabKitTest
 * @package App\Controllers
 */
class SwabKitTest extends Test
{
    public function index()
    {
        return $this->respond(null,405);
    }

    //--------------------------------------------------------------------

    /*
     * Tested
     */
    /**
     * Add a new swab kit test appointment
     * @return mixed|void status 200 - ID of Appointment created | status 406 - Sorry, you need login first
     */
    public function addAppointmentOrRequest(){
        $data = $this->getPostedData();

        if ($this->isLogin()) {
            $user_id = $this->verifyToken($data['token']);
            unset($data['token']);
            $data["user_id"] = $user_id;
            $model = new SwabKitModel();
            $appointment_id = $model->createAppointmentOrRequest($data);
            return $this->respond($appointment_id, 200);
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    /*
     * Tested
     */
    /**
     * Read a swab kit test appointment
     * @return false|mixed|string|void status 200 - a JSON formatted file | status 406 - Sorry, you need login first
     */
    public function readAppointmentOrRequest(){
        $data = $this->getPostedData();
        if ($this->isLogin()) {
            $appointment_id = $data['appointment_id'];
            $model = new SwabKitModel();
            return  json_encode($model->readAppointmentOrRequest($appointment_id));
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    /*
     * Tested
     */
    /**
     * List user's all swab kit test appointment
     * @return false|mixed|string|void status 200 - a JSON Formatted text listing on appointments |
     * status 406 - Sorry, you need login first
     */
    public function listAppointmentOrRequest(){
        $data = $this->getPostedData();

        if ($this->isLogin()) {
            $user_id = $this->verifyToken($data['token']);
            $model = new SwabKitModel();
            return  json_encode($model->listAllAppointmentOrRequest($user_id));
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    /*
     *Tested
     */
    /**
     * Cancel a swab kit test appointment
     * @return mixed|void status 200 - Cancel appointment successfully | status 406 - Sorry, you need login first |
     * status 400 - Something wrong
     */
    public function cancelAppointmentOrRequest(){
        $data = $this->getPostedData();
        if ($this->isLogin()) {
            $appointment_id = $data['appointment_id'];
            $model = new SwabKitModel();
            if ($model->deleteAppointmentOrRequest($appointment_id)) {
                return $this->respond("Cancel appointment successfully", 200);
            } else {
                return $this->respond("Something wrong", 400);
            }
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    #---------------Lab Staff Permission Needed for Following Methods--------------------------


    public function releaseResult(){
        $data = $this->getPostedData();
        $appointment_id = $data['appointment_id'];
        $result = $data['result'];
        if ($this->isLogin()) {
            $model = new SwabKitModel();
            if ($model->releaseResult($appointment_id, $result)) {
                return $this->respond("Update appointment successfully", 200);
            } else {
                return $this->respond("Something wrong", 400);
            }
        }
        return $this->respond("Please Login First", 406);
    }


    public function updateAppointmentOrRequest(){
        $data = $this->getPostedData();

        if ($this->isLogin()) {
            $appointment_id = $data['appointment_id'];
            unset($data['appointment_id']);
            unset($data['token']);
            $model = new SwabKitModel();
            if ($model->updateAppointmentOrRequest($appointment_id,$data)) {
                return $this->respond("Update appointment successfully", 200);
            } else {
                return $this->respond("Something wrong", 400);
            }
        }
        else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

}
