<?php namespace App\Controllers;

use App\Models\ClinicTestModel;
use App\Models\DriveThroughTestModel;
use App\Models\SwabKitModel;

class LabStaff extends BaseController
{
    public function index()
    {
        return redirect()->to(base_url('/LabStaff/clinic'));
    }

    //--------------------------------------------------------------------
    public function clinic($page = 1){

        $token = $this->checkLabLoginStatus();

        if (!$token){
            return redirect()->to(base_url('/Member/labstafflogin'));
        }

        $model = new ClinicTestModel();
        $queryResult = $model->listAllAppointmentOrReuqestWithJoin(intval($page));
        $numOfAppointments = $queryResult[1];
        $appointments = $queryResult[0];

        return view('lab_staff/dashboard.php',
            [
                'nav_start_color'=>'rgb(113,168,235)',
                'nav_end_color'=>'rgb(136,202,133)',
                'test_mode'=> 'clinic',
                'num_appointments'=> $numOfAppointments,
                'appointments'=> $appointments,
                'staff_name'=>$_SESSION['name'],
                'page_num'=>$page
            ]
        );
    }

    public function driveThrough($page = 1){
        if (!$this->checkLabLoginStatus()){
            return redirect()->to(base_url('/Member/labstafflogin'));
        }

        $model = new DriveThroughTestModel();
        $queryResult = $model->listAllAppointmentOrReuqestWithJoin(intval($page));
        $numOfAppointments = $queryResult[1];
        $appointments = $queryResult[0];

        return view('lab_staff/dashboard.php',
            [
                'nav_start_color'=>'rgb(112,168,235)',
                'nav_end_color'=>'rgb(179,93,95)',
                'test_mode'=> 'driveThrough',
                'num_appointments'=> $numOfAppointments,
                'appointments'=> $appointments,
                'staff_name'=>$_SESSION['name'],
                'page_num'=>$page
            ]
        );
    }

    public function swabKit($page = 1){
        if (!$this->checkLabLoginStatus()){
            return redirect()->to(base_url('/Member/labstafflogin'));
        }

        $model = new SwabKitModel();
        $queryResult = $model->listAllAppointmentOrReuqestWithJoin(intval($page));
        $numOfAppointments = $queryResult[1];
        $appointments = $queryResult[0];

        return view('lab_staff/dashboard.php',
            [
                'nav_start_color'=>'rgb(112,168,235)',
                'nav_end_color'=>'rgb(109,93,199)',
                'test_mode'=> 'swabKit',
                'num_appointments'=> $numOfAppointments,
                'appointments'=> $appointments,
                'staff_name'=>$_SESSION['name'],
                'page_num'=>$page
            ]
        );
    }
}
