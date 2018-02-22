<?php
/**
 * Created by IntelliJ IDEA.
 * User: yemei
 * Date: 22/02/2018
 * Time: 14:43
 */

namespace app\DAO;


use app\DBMA\QueryBuilder;
use app\Entities\CategoryDTO;

class CategoryDAO implements ICategoryDAO
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
     * CategoryDAO constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function findAll()
    {
        // TODO: Implement findAll() method.
        $sql = 'SELECT * FROM txp_category';
        try {
            $this->selectStatement = $this->pdo->query($sql);
        } catch (\PDOException $exception) {

        }

        return $this;
    }

    public function findOneById(array $pk)
    {
        // TODO: Implement findOneById() method.
    }

    public function find(array $search = [], array $orderBy = [], array $limit = [])
    {
        // TODO: Implement find() method.
        $sql = "SELECT * FROM txp_category ";
        $whereSting = '';
        $searchValues = [];

        $qb = new QueryBuilder();
        $qb->select("*")->from("txp_category");

        // Build the clause 'where' for the SQL request return the values in array
        $searchValues = $this->buildWhereClause($search, $whereSting, $searchValues, $qb);

        try {
            $sql = $qb->getSQL();
        } catch (\Exception $e) {
        }

        // Build 'ORDER BY' clause
        $sql = $this->buildOrderByClause($orderBy, $sql);

        // Build 'LIMIT ANT OFFSET' clause
        $sql = $this->buildLimitAndOffsetClause($limit, $sql);

        $statement = $this->pdo->prepare($sql);
        $statement->execute($searchValues);
        $this->selectStatement = $statement;

        return $this;

    }

    public function delete(CategoryDTO $category)
    {
        // TODO: Implement delete() method.
    }

    public function save(CategoryDTO $category)
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
     */
    public function getAllAsEntity()
    {
        try {
            $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, CategoryDTO::class);
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
     * @return CategoryDTO
     */
    public function getOneAsEntity()
    {
        try {
            $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, CategoryDTO::class);
            $data = $this->selectStatement->fetch();
        } catch (\PDOException $exception) {
            $data = null;
        }

        return $data;
    }

    /**
     * @param array $search
     * @param string $where
     * @param array $searchValues
     * @param QueryBuilder $qb
     * @return array $searchValues
     */
    private function buildWhereClause(array $search, string $where, array $searchValues, QueryBuilder $qb): array
    {
        if (count($search) > 0) {

            foreach ($search as $key => $value) {
                if (is_array($value)) {
                    $size = count($value);
                    for ($i = 0; $i < $size; ++$i) {
                        $glue = ($i > 0) ? ' OR' : '';
                        $where .= " $glue $key = ? ";

                        // Save values into an array for the SQL request
                        $searchValues[] = $value[$i];
                    }
                    if ($size > 1) {
                        $where .= " $glue $key = ? ";

                        // Save values into an array for the SQL request
                        $searchValues[] = implode(',', $value);
                    }
                    $qb->where($where);

                } else {
                    $qb->where(" $key LIKE ? ");

                    // Save values into an array for the SQL request
                    $searchValues[] = $value;
                }
            }

        }
        return $searchValues;
    }

    /**
     * @param array $orderBy
     * @param string $sql
     * @return string $sql
     */
    private function buildOrderByClause(array $orderBy, string $sql): string
    {
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
        return $sql;
    }

    /**
     * @param array $limit
     * @param $sql
     * @return string
     */
    private function buildLimitAndOffsetClause(array $limit, $sql): string
    {
        if (count($limit) > 0) {
            $sql .= " LIMIT " . $limit[0];
            if (isset($limit[1])) {
                $sql .= " OFFSET " . $limit[1];
            }
        }
        return $sql;
    }

}