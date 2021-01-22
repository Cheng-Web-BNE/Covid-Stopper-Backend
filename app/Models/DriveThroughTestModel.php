<?php
namespace App\Models;

use Config\Database;

class DriveThroughTestModel extends TestModel
{
    /*
     * This function has been tested
     */
    public function listAllAppointmentOrRequest($user_id){
        $db      = Database::connect();
        $builder = $db->table('test_drive_through_history');
        $result = $builder->getWhere(['user_id' => $user_id]);
        if (isset($result)){
            return $result->getResult();
        } else {
            return false;
        }
    }

    /*
     * This function has been tested
     */
    public function createAppointmentOrRequest($data){
        $db      = Database::connect();
        $builder = $db->table('test_drive_through_history');
        $builder->insert($data);
        return $db->insertID();
    }

    /*
     * This function has been tested
     */
    public function readAppointmentOrRequest($appointment_id){
        $db      = Database::connect();
        $builder = $db->table('test_drive_through_history');
        $result = $builder->getWhere(['id' => $appointment_id], 1)->getRow();
        if (isset($result)){
            return $result;
        } else {
            return false;
        }
    }

    public function updateAppointmentOrRequest($appointment_id,$data){
        $db      = Database::connect();
        $builder = $db->table('test_drive_through_history');
        $builder->set($data);
        $builder->where('id',$appointment_id);
        $builder->update();
        return true;
    }

    /*
     * This function has been tested
     */
    public function deleteAppointmentOrRequest($appointment_id){
        $db      = Database::connect();
        $builder = $db->table('test_drive_through_history');
        $builder->delete(['id' => $appointment_id]);
        return true;
    }


    public function isDriveThroughExist($test_drive_through_id) {
        $db      = Database::connect();
        $builder = $db->table('test_drive_through');
        $result = $builder->getWhere(['id' => $test_drive_through_id], 1)->getRow();
        if (isset($result)){
            return true;
        } else {
            return false;
        }
    }

    /*
     * This function has been tested
     */
    public function listDriveThroughInfo() {
        $db      = Database::connect();
        $builder = $db->table('test_drive_through');
        $result = $builder->get()->getResult();
        if (isset($result)){
            return $result;}
        else {
            return false;
        }
    }

    /*
     * This function has been tested
     */
    public function getDriveThroughInfo($test_drive_through_id) {
        $db      = Database::connect();
        $builder = $db->table('test_drive_through');
        $result = $builder->getWhere(['id' => $test_drive_through_id], 1)->getRow();
        if (isset($result)){
            return $result;
        } else {
            return false;
        }
    }

    public function releaseResult($appointment_id,$result) {
        $db      = Database::connect();
        $builder = $db->table('test_drive_through_history');
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
SELECT tdt.id as appointment_id, u.user_id, u.first_name, u.last_name, u.email, u.phone_number, td.name as centre_name 
FROM test_drive_through_history tdt 
NATURAL JOIN user u 
JOIN test_drive_through td ON tdt.test_drive_through_id = td.id 
WHERE result IS NULL 
LIMIT 100 OFFSET ' . strval(100*($page-1)) . ';'
        );
        $result[] = $db->query(
            '
SELECT COUNT(*) as row_count 
FROM test_drive_through_history tdt 
NATURAL JOIN user u 
JOIN test_drive_through td ON tdt.test_drive_through_id = td.id 
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
