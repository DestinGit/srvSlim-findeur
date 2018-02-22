<?php
/**
 * Created by IntelliJ IDEA.
 * User: yemei
 * Date: 22/02/2018
 * Time: 14:40
 */

namespace app\DAO;


use app\Entities\CategoryDTO;

interface ICategoryDAO
{
    // --- CRUD+
    public function findAll();

    public function findOneById(array $pk);

    public function find(array $search);

    public function delete(CategoryDTO $category);

    public function save (CategoryDTO $category);

}