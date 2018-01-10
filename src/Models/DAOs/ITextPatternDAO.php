<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 04/01/2018
 * Time: 14:45
 */

namespace app\DAO;

use app\Entities\TextPatternDTO;

interface ITextPatternDAO
{
    // --- CRUD+
    public function findAll();

    public function findOneById(array $pk);

    public function find(array $search);

    public function delete(TextPatternDTO $user);

    public function save (TextPatternDTO $user);

}