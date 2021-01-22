<?php
namespace App\Models;

use Config\Database;

class ClinicTestModel extends TestModel
{

    public function listAllAppointmentOrRequest($user_id){
        $db      = Database::connect();
        $builder = $db->table('test_clinic_appointment');
        $result = $builder->getWhere(['user_id' => $user_id])->getResult();
        if (isset($result)){
            return $result;
        } else {
            return false;
        }
    }

    public function createAppointmentOrRequest($data){
        $db      = Database::connect();
        $builder = $db->table('test_clinic_appointment');
        $builder->insert($data);
        return $db->insertID();
    }

    public function readAppointmentOrRequest($appointment_id){
        $db      = Database::connect();
        $builder = $db->table('test_clinic_appointment');
        $result = $builder->getWhere(['id' => $appointment_id], 1)->getRow();
        if (isset($result)){
            return $result;
        } else {
            return false;
        }
    }

    public function updateAppointmentOrRequest($appointment_id,$data){
        $db      = Database::connect();
        $builder = $db->table('test_clinic_appointment');
        $builder->set($data);
        $builder->where('id',$appointment_id);
        $builder->update();
        return true;
    }

    public function deleteAppointmentOrRequest($appointment_id){
        $db      = Database::connect();
        $builder = $db->table('test_clinic_appointment');
        $builder->delete(['id' => $appointment_id]);
        return true;
    }

    public function listClinicInfo(){
        $db      = Database::connect();
        $builder = $db->table('test_clinic');
        $result = $builder->get()->getResult();
        if (isset($result)){
            return $result;
        } else {
            return false;
        }
    }

    public function getClinicInfo($clinic_id) {
        $db      = Database::connect();
        $builder = $db->table('test_clinic');
        $result = $builder->getWhere(['id' => $clinic_id], 1)->getRow();
        if (isset($result)){
            return $result;
        } else {
            return false;
        }
    }

    public function isClinicExist ($clinic_id) {
        $db      = Database::connect();
        $builder = $db->table('test_clinic');
        $result = $builder->getWhere(['id' => $clinic_id], 1)->getRow();
        if (isset($result)){
            return true;
        } else {
            return false;
        }
    }

    public function releaseResult($appointment_id,$result) {
        $db      = Database::connect();
        $builder = $db->table('test_clinic_appointment');
        $builder->set('result', $result);
        $builder->where('id',$appointment_id);
        $builder->update();
        return true;
    }

    public function listAllAppointmentOrReuqestWithJoin($page){
        $page = intval($page);
        $db      = Database::connect();
        $result[] = $db->query(
            '
SELECT c.id as appointment_id, u.user_id, u.first_name, u.last_name, u.email, u.phone_number, clinic.name as clinic_name 
FROM test_clinic_appointment c 
NATURAL JOIN user u 
JOIN test_clinic clinic ON c.test_clinic_id = clinic.id 
WHERE result IS NULL 
LIMIT 100 OFFSET ' . strval(100*($page-1)) . ';'
        );
        $result[] = $db->query(
            '
SELECT COUNT(*) as row_count 
FROM test_clinic_appointment c 
NATURAL JOIN user u 
JOIN test_clinic clinic ON c.test_clinic_id = clinic.id 
WHERE result IS NULL ;'
        );
        $result[1] = $result[1]->getRow()->row_count;
        $result[0] = $result[0]->getResultArray();
        if ($result[1] !=0 && isset($result[0]) && isset($result[1])){
            return $result;
        } else {
            return [[],0];
        }
    }
}
