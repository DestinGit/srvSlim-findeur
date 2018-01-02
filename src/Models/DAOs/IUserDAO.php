<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 15/12/2017
 * Time: 12:35
 */

namespace app\DAO;

use app\Entities\UserDTO;


interface IUserDAO
{
    // --- CRUD+
    public function findAll();

    public function findOneById(array $pk);

    public function find(array $search);

    public function delete(UserDTO $user);

    public function save (UserDTO $user);

}