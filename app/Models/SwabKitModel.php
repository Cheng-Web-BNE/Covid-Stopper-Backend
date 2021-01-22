<?php
namespace App\Models;

use Config\Database;
define("TABLE_NAME","swab_kit_request");

class SwabKitModel extends TestModel
{

    public function listAllAppointmentOrRequest($user_id){
        $db      = Database::connect();
        $builder = $db->table(TABLE_NAME);
        $result = $builder->getWhere(['user_id' => $user_id]);
        if (isset($result)){
            return $result->getResult();
        } else {
            return false;
        }
    }

    public function createAppointmentOrRequest($data){
        $db      = Database::connect();
        $builder = $db->table(TABLE_NAME);
        $builder->insert($data);
        return $db->insertID();
    }

    public function readAppointmentOrRequest($appointment_id){
        $db      = Database::connect();
        $builder = $db->table(TABLE_NAME);
        $result = $builder->getWhere(['id' => $appointment_id], 1)->getRow();
        if (isset($result)){
            return $result;
        } else {
            return false;
        }
    }

    public function releaseResult($appointment_id,$result){
        $db      = Database::connect();
        $builder = $db->table(TABLE_NAME);
        $builder->set('result', $result);
        $builder->where('id',$appointment_id);
        $builder->update();
        return true;
    }

    public function updateAppointmentOrRequest($appointment_id,$data){
        $db      = Database::connect();
        $builder = $db->table(TABLE_NAME);
        $builder->set($data);
        $builder->where('id',$appointment_id);
        $builder->update();
        return true;
    }

    public function deleteAppointmentOrRequest($appointment_id){
        $db      = Database::connect();
        $builder = $db->table(TABLE_NAME);
        $builder->delete(['id' => $appointment_id]);
        return true;
    }

    public function listAllAppointmentOrReuqestWithJoin($page){
        $page = intval($page);
        $db      = Database::connect();
        $result[] = $db->query(
            '
SELECT skr.id as appointment_id, u.user_id, u.first_name, u.last_name, u.email, u.phone_number, skr.send_tracking_number 
FROM swab_kit_request skr 
NATURAL JOIN user u 
WHERE result IS NULL 
LIMIT 100 OFFSET ' . strval(100*($page-1)) . ';'
        );
        $result[] = $db->query(
            '
SELECT COUNT(*) as row_count 
FROM swab_kit_request skr 
NATURAL JOIN user u 
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