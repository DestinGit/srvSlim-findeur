<?php
/**
 * Created by Destin Gando.
 * User: Destin
 * Date: 15/12/2017
 * Time: 14:20
 */

namespace app\DAO;

use app\Entities\UserDTO;

//use Exception;

class UserDAO implements IUserDAO
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var \PDOStatement;
     */
    private $selectStatement, $cudPreparedStatement;


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
        try {
            $this->selectStatement = $this->pdo->query($sql);
        } catch (\PDOException $exception) {

        }
        return $this;
    }

    /**
     * @return array
     */
    public function getAllAsArray()
    {
        try {
            $data = $this->selectStatement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exception) {
            $data = null;
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getAllAsEntity()
    {
        try {
            $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, UserDTO::class);
            $data = $this->selectStatement->fetchAll();

        } catch (\PDOException $exception) {
            $data = null;
        }
        return $data;
    }


    /**
     * @return array
     */
    public function getOneAsArray()
    {
        try {
            $data = $this->selectStatement->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exception) {
            $data = null;
        }

        return $data;
    }


    /**
     * @return UserDTO
     */
    public function getOneAsEntity()
    {
        try {
            $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, UserDTO::class);
            $data = $this->selectStatement->fetch();
        } catch (\PDOException $exception) {
            $data = null;
        }
        return $data;
    }

    public function findOneById(array $pk)
    {
        // TODO: Implement findOneById() method.
    }

    /**
     * @param array $search
     * @param array $orderBy
     * @param array $limit
     * @return $this
     */
    public function find(array $search = [], array $orderBy = [], array $limit = [])
    {
        // TODO: Implement find() method.
        $sql = "SELECT * FROM txp_users ";

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

        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($search);
            $this->selectStatement = $statement;
        } catch (\PDOException $exception) {
            echo 'Erreur exécution find : ' . $exception->getMessage();
        }

        return $this;
    }

    public function delete(UserDTO $user)
    {
        // TODO: Implement delete() method.
    }


    /**
     * @param UserDTO $user
     * @return $this
     */
    public function save(UserDTO $user)
    {
        // TODO: Implement save() method.
        if ($user->getUserId() == null) {
            $pk = $this->insert($user);
            $user->setUserId($pk);
        } else {
            $this->update($user);
        }

        return $this;
    }

    /**
     * Validate the transaction or rollback
     */
    public function flush()
    {
        try {
            $this->pdo->commit();
        } catch (\PDOException $exception) {
            $this->pdo->rollBack();
        }
    }

    //UPDATE txp_users SET pass = '$P$BMVzitxb/DHAp1rhvtsdsCthfpYJg./', nonce = '0d381b89f0375f9bb18ce21a246925a7', RealName = 'titi toto' WHERE user_id = 4202

    /**
     * @param UserDTO $user
     * @return int
     */
    private function insert(UserDTO $user)
    {
        $lastInsertId = -1;

        $sql = 'INSERT INTO txp_users(name, pass, RealName, first_name, last_name,
                email, privs, last_access, nonce, address, phone, web, entreprise, detail)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        try {
            // Prepare the statement for update data
            if ($this->cudPreparedStatement == null) {
                $this->cudPreparedStatement = $this->pdo->prepare($sql);
            }
            // Associates a value with a parameter
            // and execute INSERT prepared request
            $this->cudPreparedStatement->execute([
                $user->getName(), $user->getPass(), $user->getRealName(), $user->getFirstName(),
                $user->getLastName(), $user->getEmail(), $user->getPrivs(), $user->getLastAccess(),
                $user->getNonce(), $user->getAddress(), $user->getPhone(), $user->getWeb(),
                $user->getEntreprise(), $user->getDetail()
            ]);

            $lastInsertId = $this->pdo->lastInsertId();

        } catch (\PDOException $exception) {
            $this->pdo->rollBack();
        }

        return $lastInsertId;
    }

    /**
     * @param UserDTO $user
     */
    private function update(UserDTO $user)
    {
        $sql = 'UPDATE txp_users SET name = ?, pass = ?, RealName = ?, first_name = ?, last_name = ?,
                email = ?, privs = ?, last_access = ?, nonce = ?, address = ?, phone = ?, web = ?,
                entreprise = ?, detail = ? 
                WHERE user_id = ? ';

        try {
            // Prepare the statement for update data
            if ($this->cudPreparedStatement == null) {
                $this->cudPreparedStatement = $this->pdo->prepare($sql);
            }

            // Associates a value with a parameter and execute UPDATE prepared request
            $this->cudPreparedStatement->execute([
                $user->getName(), $user->getPass(), $user->getRealName(), $user->getFirstName(),
                $user->getLastName(), $user->getEmail(), $user->getPrivs(), $user->getLastAccess(),
                $user->getNonce(), $user->getAddress(), $user->getPhone(), $user->getWeb(),
                $user->getEntreprise(), $user->getDetail(),

                // Must be at the end of the table
                $user->getUserId()
            ]);

        } catch (\PDOException $exception) {
            $this->pdo->rollBack();
        }

    }
}