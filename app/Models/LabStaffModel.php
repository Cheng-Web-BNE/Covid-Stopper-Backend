<?php
namespace App\Models;
use CodeIgniter\Model;
use Config\Database;

class LabStaffModel extends Model
{
    public function readSpecificUser($id)
    {
        $db = Database::connect();
        $builder = $db->table('lab_user');
        $result = $builder->getWhere(['id' => $id], 1)->getRow();
        if (isset($result)) {
            return $result;
        } else {
            return null;
        }
    }

    public function login($email, $password)
    {
        $db = Database::connect();
        $builder = $db->table('lab_user');
        $result = $builder->getWhere(['email' => $email, 'password' => $password], 1)->getRow();
        if (isset($result)) {
            return $result->id;
        } else {
            return null;
        }
    }
}