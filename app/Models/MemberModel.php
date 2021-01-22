<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class MemberModel extends Model
{
    protected $table = "user";
    public function isSpecificMemberExist($phone_number){
        $db      = Database::connect();
        $builder = $db->table('user');
        $result = $builder->getWhere(['phone_number' => $phone_number], 1)->getRow();
        if (isset($result)){
            return true;
        } else {
            return false;
        }
    }

    public function isSpecificCommercialMemberExist($name){
        $db      = Database::connect();
        $builder = $db->table('commercial_user');
        $result = $builder->getWhere(['name' => $name], 1)->getRow();
        if (isset($result)){
            return true;
        } else {
            return false;
        }
    }

    public function addMemberToRelation($data,$role){
        $db      = Database::connect();
        if ($role == "general"){
            $builder = $db->table('user');
        } elseif ($role == "commercial") {
            $builder = $db->table('commercial_user');
        } else{
            return false;

        }
        $builder->insert($data);
        return $db->insertID();
    }



    public function login($phone_number, $password){
        $db      = Database::connect();
        $builder = $db->table('user');
        $result = $builder->getWhere(['phone_number' => $phone_number, 'password' => $password], 1)->getRow();
        if(isset($result)){
            return $result->user_id;
        } else {
            return null;
        }
    }

    public function commercialLogin($name, $password){
        $db      = Database::connect();
        $builder = $db->table('commercial_user');
        $result = $builder->getWhere(['name' => $name, 'password' => $password], 1)->getRow();
        if(isset($result)){
            return $result->id;
        } else {
            return null;
        }
    }

    public function readMemberFromRelation($user_id) {
        $db      = Database::connect();
        $builder = $db->table('user');
        $result = $builder
            ->select("
                user_id,
                phone_number,
                first_name,
                last_name,
                email,
                positive,
                bluetooth,
                street_address,
                street_suburb,
                street_post_code,
                postal_address,
                postal_suburb,
                postal_post_code,
                verification_code
                ")
            ->getWhere(['user_id' => $user_id], 1)
            ->getRow();
        if (isset($result)){
            return $result;
        } else {
            return false;
        }

    }

    public function readCommercialMemberFromRelation($user_id) {
        $db      = Database::connect();
        $builder = $db->table('commercial_user');
        $result = $builder
            ->select("
                id,
                name,
                address,
                suburb,
                post_code,
                phone_number,
                email
                ")
            ->getWhere(['id' => $user_id], 1)
            ->getRow();
        if (isset($result)){
            return $result;
        } else {
            return false;
        }

    }

    public function updateMemberToRelation($user_id,$data) {
        $db      = Database::connect();
        $builder = $db->table('user');
        $builder->set($data);
        $builder->where('user_id',$user_id);
        $builder->update();
        return true;
    }

    public function updateCommercialMemberToRelation($user_id,$data) {
        $db      = Database::connect();
        $builder = $db->table('commercial_user');
        $builder->set($data);
        $builder->where('id',$user_id);
        $builder->update();
        return true;
    }
}
