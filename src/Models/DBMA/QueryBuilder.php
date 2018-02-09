<?php
/**
 * Created by IntelliJ IDEA.
 * User: yemei
 * Date: 08/02/2018
 * Time: 17:06
 */

namespace app\DBMA;


class QueryBuilder
{
    /**
     * @var array
     */
    private $from = [];

    /**
     * @var array
     */
    private $select = [];

    /**
     * @var array
     */
    private $where = [];

    /**
     * @var array
     */
    private $orderBy = [];

    /**
     * @param array ...$tableName
     * @return $this
     *
     * $qb->from("auteurs")
     * $qb->from("livres","auteurs")
     */
    public function from(...$tableName){
        $this->from[] = implode(", ",$tableName);
        return $this;
    }

    /**
     * @param $fields
     * @return $this
     *
     * $qb->select("nom, prenom, titre")
     */
    public function select(string $fields){
        $this->select[] = $fields;
        return $this;
    }

    /**
     * @param string $whereClause
     * @return $this
     */
    public function where(string $whereClause){
        $this->where[] = $whereClause;
        return $this;
    }

    /**
     * @param string $orderByClause
     * @return $this
     */
    public function orderBy(string $orderByClause){
        $this->orderBy[] = $orderByClause;
        return $this;
    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function getSQL(){
        if(count($this->from) == 0 || count($this->select) == 0){
            throw new \Exception("The FROM and SELECT clauses can not be empty");
        }

        $sql =  "SELECT ". implode(",", $this->select).
            " FROM ". implode(", ", $this->from);

        if(count($this->where) >0){
            $sql .= " WHERE (". implode(") AND (", $this->where). ")";
        }

        if(count($this->orderBy) > 0){
            $sql .= " ORDER BY ". implode(", ", $this->orderBy);
        }

        $sql = str_replace(";","", $sql);

        return $sql;
    }
}