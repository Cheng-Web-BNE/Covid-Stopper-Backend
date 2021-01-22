<?php namespace App\Controllers;


use App\Models\DriveThroughTestModel;

class DriveThroughTest extends Test
{
    public function index()
    {
        return $this->respond(null,405);
    }

    //--------------------------------------------------------------------

    /*
     * This function has been tested
     */
    /**Add a new through drive test appointment
     * @return mixed|void status 200 - ID of Appointment created | status 406 - Sorry, you need login first |
     * status 400 - The Information provided might be wrong
     */
    public function addAppointmentOrRequest(){
        $data = $this->getPostedData();

        if ($this->isLogin()) {
            if (isset($data["test_drive_through_id"])) {
                $user_id = $this->verifyToken($data['token']);
                unset($data['token']);
                $data["user_id"] = $user_id;
                $model = new DriveThroughTestModel();
                $appointment_id = $model->createAppointmentOrRequest($data);
                return $this->respond($appointment_id, 200);
            } else {
                return $this->respond("The Information provided might be wrong", 400);
            }
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    /*
     * This function has been tested
     */
    /** Read a drive through appointment
     * @return false|mixed|string|void status 200 - a JSON formatted file | status 406 - Sorry, you need login first
     */
    public function readAppointmentOrRequest(){
        $data = $this->getPostedData();
        if ($this->isLogin()) {
            $appointment_id = $data['appointment_id'];
            $model = new DriveThroughTestModel();
            return  json_encode($model->readAppointmentOrRequest($appointment_id));
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    /*
     * This function has been tested
     */
    /** List user's all drive through test appointment
     * @return false|mixed|string|void status 200 - a JSON Formatted text listing on appointments |
     * status 406 - Sorry, you need login first
     */
    public function listAppointmentOrRequest(){
        $data = $this->getPostedData();

        if ($this->isLogin()) {
            $user_id = $this->verifyToken($data['token']);
            $model = new DriveThroughTestModel();
            return  json_encode($model->listAllAppointmentOrRequest($user_id));
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    /*
     * This function has been tested
     */
    /** Update a drive through appointment
     * @return mixed|void status 200 - Update appointment successfully | status 406 - Sorry, you need login first |
     * status 400 - Something wrong
     */
    public function updateAppointmentOrRequest(){
        $data = $this->getPostedData();

        if ($this->isLogin()) {
            $appointment_id = $data['appointment_id'];
            unset($data['appointment_id']);
            unset($data['token']);
            if (!isset($data["result"])){
                if (isset($data["test_drive_through_id"])) {
                    $model = new DriveThroughTestModel();
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

    /*
     * This function has been tested
     */
    /** Cancel a drive through appointment
     * @return mixed|void status 200 - Cancel appointment successfully | status 406 - Sorry, you need login first |
     * status 400 - Something wrong
     */
    public function cancelAppointmentOrRequest(){
        $data = $this->getPostedData();
        if ($this->isLogin()) {
            $appointment_id = $data['appointment_id'];
            $model = new DriveThroughTestModel();
            if ($model->deleteAppointmentOrRequest($appointment_id)) {
                return $this->respond("Cancel appointment successfully", 200);
            } else {
                return $this->respond("Something wrong", 400);
            }
        } else {
            return $this->respond("Sorry, you need login first.", 406);
        }
    }

    /*
     * This function has been tested
     */
    /** List drive through points stored in the system
     * @return false|string status 200 - JSON Formatted list of clinics
     */
    public function listDriveThroughInfo(){
        $model = new DriveThroughTestModel();
        return json_encode($model->listDriveThroughInfo());
    }

    /*
     * This function has been tested
     */
    /** Get a specific drive through point's information
     * @return false|mixed|string status 200 - JSON Formatted Clinic Information | status 400 - The clinic does not exist.
     */
    public function getDriveThroughInfo(){
        $data = $this->getPostedData();

        $test_drive_through_id = $data['test_drive_through_id'];
        $model = new DriveThroughTestModel();
        if ($model ->isDriveThroughExist($test_drive_through_id)) {
            return json_encode($model->getDriveThroughInfo($test_drive_through_id));
        } else {
            return $this->respond("The clinic does not exist.", 400);
        }
    }

    #---------------Lab Staff Permission Needed for Following Methods--------------------------


    /**
     * Release drive through test result
     * @return mixed|void status 200 - Update appointment successfully | status 400 - Something wrong
     */

    public function releaseResult()
    {
        $data = $this->getPostedData();
        $appointment_id = $data['appointment_id'];
        $result = $data['result'];
        if ($this->isLogin()) {
            $model = new DriveThroughTestModel();
            if ($model->releaseResult($appointment_id, $result)) {
                return $this->respond("Update appointment successfully", 200);
            } else {
                return $this->respond("Something wrong", 400);
            }
        }
        return $this->respond("Please Login First", 406);
    }

}
