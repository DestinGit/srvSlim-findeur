<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 04/01/2018
 * Time: 14:48
 */

namespace app\DAO;

use app\Entities\TextPatternDTO;
use Exception;

class TextPatternDAO implements ITextPatternDAO
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var \PDOStatement;
     */
    private $selectStatement;

    /**
     * TextPatternDAO constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll()
    {
        // TODO: Implement findAll() method.
        $sql = 'SELECT * FROM textpattern';
        $this->selectStatement = $this->pdo->query($sql);
        return $this;

    }

    public function findOneById(array $pk)
    {
        // TODO: Implement findOneById() method.
    }

    public function find(array $search = [], array $orderBy = [], array $limit = [])
    {
        // TODO: Implement find() method.
       // $sql = "SELECT * FROM textpattern ";
        $sql = 'SELECT t.*, t1.name AS imageName FROM textpattern t LEFT JOIN txp_image t1 ON t.Image = t1.id ';

        if (count($search) > 0) {
            $sql .= " WHERE ";
            $cols = array_map(
                function ($item) {
                    return "$item=:$item";
                }, array_keys($search)
            );

            $sql .= implode(" AND ", $cols);
        }

        if (count($orderBy) > 0) {
            $sql .= "ORDER BY ";
            $cols = array_map(
                function ($item) use ($orderBy) {
                    return "$item " . $orderBy[$item];
                },
                array_keys($orderBy)
            );
            $sql .= implode(", ", $cols);
        }

        if (count($limit) > 0) {
            $sql .= " LIMIT " . $limit[0];
            if (isset($limit[1])) {
                $sql .= " OFFSET " . $limit[1];
            }
        }

        $statement = $this->pdo->prepare($sql);
        $statement->execute($search);
        $this->selectStatement = $statement;

        return $this;

    }

    public function delete(TextPatternDTO $user)
    {
        // TODO: Implement delete() method.
    }

    public function save(TextPatternDTO $user)
    {
        // TODO: Implement save() method.
    }


    /**
     * @return array
     */
    public function getAllAsArray()
    {
        return $this->selectStatement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getAllAsEntity()
    {
        $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, TextPatternDTO::class);
        $data = $this->selectStatement->fetchAll();

        if ($data) {
            return $data;
        } else {
            throw new Exception("Ancun résultat pour cette requête");
        }
    }


    /**
     * @return array
     * @throws Exception
     */
    public function getOneAsArray()
    {
        $data = $this->selectStatement->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return $data;
        } else {
            throw new Exception("Ancun résultat pour cette requête");
        }
    }

    /**
     * @return TextPatternDTO
     * @throws Exception
     */
    public function getOneAsEntity()
    {
        $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, TextPatternDTO::class);
        $data = $this->selectStatement->fetch();

        if ($data) {
            return $data;
        } else {
            throw new Exception("Ancun résultat pour cette requête");
        }
    }

}