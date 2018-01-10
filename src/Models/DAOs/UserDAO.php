<?php
/**
 * Created by Destin Gando.
 * User: Destin
 * Date: 15/12/2017
 * Time: 14:20
 */

namespace app\DAO;

use app\Entities\UserDTO;
use Exception;

class UserDAO implements IUserDAO
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
     * DAOClient constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return $this
     */
    public function findAll()
    {
        // TODO: Implement findAll() method.
        $sql = 'SELECT * FROM txp_users';
        $this->selectStatement = $this->pdo->query($sql);
        return $this;
    }

    /**
     * @return array
     */
    public function getAllAsArray(){
        return $this->selectStatement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getAllAsEntity(){
        $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, UserDTO::class);
        $data = $this->selectStatement->fetchAll();

        if($data){
            return $data;
        } else {
            throw new Exception("Ancun résultat pour cette requête");
        }
    }


    /**
     * @return array
     * @throws Exception
     */
    public function getOneAsArray(){
        $data = $this->selectStatement->fetch(\PDO::FETCH_ASSOC);

        if($data){
            return $data;
        } else {
            throw new Exception("Ancun résultat pour cette requête");
        }
    }


    /**
     * @return UserDTO
     * @throws Exception
     */
    public function getOneAsEntity(){
        $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, UserDTO::class);
        $data = $this->selectStatement->fetch();

        if($data){
            return $data;
        } else {
            throw new Exception("Ancun résultat pour cette requête");
        }
    }

    public function findOneById(array $pk)
    {
        // TODO: Implement findOneById() method.
    }

    public function find(array $search = [], array $orderBy = [], array $limit = [])
    {
        // TODO: Implement find() method.
        $sql = "SELECT * FROM txp_users ";

        if(count($search)>0){
            $sql .= " WHERE ";
            $cols = array_map(
                function($item){
                    return "$item=:$item";
                }, array_keys($search)
            );

            $sql .= implode(" AND ", $cols);
        }

        if(count($orderBy)>0){
            $sql .= "ORDER BY ";
            $cols = array_map(
                function($item) use($orderBy){
                    return "$item ". $orderBy[$item];
                },
                array_keys($orderBy)
            );
            $sql .= implode(", ", $cols);
        }

        if(count($limit) >0){
            $sql .= " LIMIT ".$limit[0];
            if(isset($limit[1])){
                $sql .= " OFFSET ". $limit[1];
            }
        }

/*        $arr = [];

        foreach ($search as $key=>$value) {
            $arr[':'.$key] = $value;
        }
        var_dump($sql);
        echo '<br>';
        var_dump($arr);*/

        $statement = $this->pdo->prepare($sql);
        //$statement->execute($arr);
        $statement->execute($search);
        $this->selectStatement = $statement;

        return $this;

    }

    public function delete(UserDTO $user)
    {
        // TODO: Implement delete() method.
    }

    public function save(UserDTO $user)
    {
        // TODO: Implement save() method.
    }

}