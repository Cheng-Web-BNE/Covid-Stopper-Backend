<?php
namespace App\Models;

use CodeIgniter\Model;

abstract class TestModel extends Model
{
    public function listAllAppointmentOrRequest($user_id){
        return false;
    }

    public function createAppointmentOrRequest($data){
        return false;
    }

    public function readAppointmentOrRequest($appointment_id){
        return false;
    }

    public function updateAppointmentOrRequest($appointment_id,$data){
        return false;
    }

    public function deleteAppointmentOrRequest($appointment_id){
        return false;
    }
}
