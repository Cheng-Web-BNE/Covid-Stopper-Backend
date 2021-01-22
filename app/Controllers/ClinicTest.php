<?php namespace App\Controllers;

use App\Models\ClinicModel;
use App\Models\ClinicTestModel;

class ClinicTest extends Test
{
    public function index()
    {
        return $this->respond(null,405);
    }
    
    //--------------------------------------------------------------------


    /**
     * Add a new clinic test appointment
     * @return mixed|void status 200 - ID of Appointment created | status 406 - Sorry, you need login first |
     * status 400 - The Information provided might be wrong
     */

    public function addAppointmentOrRequest(){
        $data = $this->getPostedData();

        if ($this->isLogin()) {
            if (isset($data["test_clinic_id"])&&
                isset($data["time"]) &&
                isset($data["date"]) ) {
                $user_id = $this->verifyToken($data['token']);

                unset($data['token']);
                $data["user_id"] = $user_id;
                $model = new ClinicTestModel();
                $appointment_id = $model->createAppointmentOrRequest($data);
                return $this->respond($appointment_id, 200);
            } else {
                return $this->respond("The Information provided might be wrong", 400);
            }
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }

    }

    /** Read a clinic test appointment
     * @return false|mixed|string|void status 200 - a JSON formatted file | status 406 - Sorry, you need login first
     *
     */
    public function readAppointmentOrRequest(){
        $data = $this->getPostedData();

        if ($this->isLogin()) {
            $appointment_id = $data['appointment_id'];
            $model = new ClinicTestModel();
            return json_encode($model->readAppointmentOrRequest($appointment_id));
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }


    /**
     * List user's all clinic test appointment
     * @return false|mixed|string|void status 200 - a JSON Formatted text listing on appointments |
     * status 406 - Sorry, you need login first
     */
    public function listAppointmentOrRequest(){
        $data = $this->getPostedData();
        if ($this->isLogin()) {
            $user_id = $this->verifyToken($data['token']);
            $model = new ClinicTestModel();
            return  json_encode($model->listAllAppointmentOrRequest($user_id));
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }


    /** Update a clinic test appointment
     * @return mixed|void status 200 - Update appointment successfully | status 406 - Sorry, you need login first |
     * status 400 - Something wrong
     */
    public function updateAppointmentOrRequest(){
        $data = $this->getPostedData();

        if ($this->isLogin()) {
            $appointment_id = $data['appointment_id'];
            unset($data['appointment_id']);
            if (!isset($data["result"])){
                if (isset($data["test_clinic_id"])||
                    isset($data["time"])||
                    isset($data["date"])) {
                    unset($data["token"]);
                    $model = new ClinicTestModel();
                    if ($model->updateAppointmentOrRequest($appointment_id,$data)) {
                        return $this->respond("Update appointment successfully", 200);
                    } else {
                        return $this->respond("Something wrong", 400);
                    }
                } else {
                    return $this->respond("The Information provided might be wrong", 400);
                }
            }else {
                $this->releaseResult($appointment_id,$data["result"]);
            }
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }


    /**
     * Cancel a clinic test appointment
     * @return mixed|void status 200 - Cancel appointment successfully | status 406 - Sorry, you need login first |
     * status 400 - Something wrong
     */
    public function cancelAppointmentOrRequest(){
        $data = $this->getPostedData();

        if ($this->isLogin()) {
            $appointment_id = $data['appointment_id'];
            $model = new ClinicTestModel();
            if ($model->deleteAppointmentOrRequest($appointment_id)) {
                return $this->respond("Cancel appointment successfully", 200);
            } else {
                return $this->respond("Something wrong", 400);
            }
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }


    /**
     * List clinics stored in the system
     * @return false|string status 200 - JSON Formatted list of clinics
     */
    public function listClinicInfo(){
        $model = new ClinicTestModel();
        return json_encode($model->listClinicInfo());
    }


    /**
     * Get a specific clinic's information
     * @return false|mixed|string status 200 - JSON Formatted Clinic Information | status 400 - The clinic does not exist.
     */
    public function getClinicInfo(){
        $data = $this->getPostedData();

        $clinic_id = $data['clinic_id'];
        $model = new ClinicTestModel();
        if ($model ->isClinicExist($clinic_id)) {
            return json_encode($model->getClinicInfo($clinic_id));
        } else {
            return $this->respond("The clinic does not exist.", 400);
        }
    }



    #---------------Lab Staff Permission Needed for Following Methods--------------------------


    /**
     * Release clinic test result
     * @return mixed|void status 200 - Update appointment successfully | status 400 - Something wrong
     */

    public function releaseResult(){
        $data = $this->getPostedData();
        $appointment_id = $data['appointment_id'];
        $result = $data['result'];
        if ($this->isLogin()) {
            $model = new ClinicTestModel();
            if ($model->releaseResult($appointment_id, $result)) {
                return $this->respond("Update appointment successfully", 200);
            } else {
                return $this->respond("Something wrong", 400);
            }
        }
        return $this->respond("Please Login First", 406);
    }

}
