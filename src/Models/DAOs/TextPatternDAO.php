<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 04/01/2018
 * Time: 14:48
 */

namespace app\DAO;

use app\DBMA\QueryBuilder;
use app\Entities\TextPatternDTO;
//use Exception;

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
     * @var \PDOStatement;
     */
    private $cudPreparedStatement;

    /**
     * TextPatternDAO constructor.
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
        $sql = 'SELECT * FROM textpattern ';
        $this->selectStatement = $this->pdo->query($sql);
        return $this;

    }

    /**
     * @param array $pk
     * @return $this
     */
    public function findOneById(array $pk)
    {
        // TODO: Implement findOneById() method.
        $sql = 'SELECT * FROM textpattern WHERE id = ? ';

        try {
            $this->selectStatement = $this->pdo->prepare($sql);
            $this->selectStatement->execute($pk);
        } catch (\PDOException $exception) {

        }

        return $this;
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
        $sql = "SELECT * FROM textpattern ";
        $whereSting = '';
        $searchValues = [];

        $qb = new QueryBuilder();
        $qb->select("*")->from("textpattern");

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

    public function delete(TextPatternDTO $tpArticle)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param TextPatternDTO $tpArticle
     * @return TextPatternDAO
     */
    public function save(TextPatternDTO $tpArticle)
    {
        // TODO: Implement save() method.
        if ($tpArticle->getID() == null) {
            $pk = $this->insert($tpArticle);
            $tpArticle->setID($pk);
        } else {
            $this->update($tpArticle);
        }

        return $this;
    }

    /**
     * @param TextPatternDTO $tpArticle
     * @return int
     */
    private function insert(TextPatternDTO $tpArticle)
    {
        $lastInsertId = -1;

        $sql = "INSERT INTO textpattern(
                Posted,Expires,AuthorID,LastMod,LastModID,Title,Title_html,Body,Body_html,Excerpt,Excerpt_html,Image,
                Category1,Category2,Annotate,AnnotateInvite,comments_count,Status,textile_body,textile_excerpt,
                Section,override_form,Keywords,url_title,custom_1,custom_2,custom_3,custom_4,custom_5,custom_6,custom_7,
                custom_8,custom_9,custom_10,uid,feed_time,custom_11,custom_12,custom_13,custom_14,custom_15,custom_16,
                custom_17,custom_18,custom_19,custom_20,custom_21,custom_22,custom_23,custom_24,custom_25,custom_26,
                custom_27,custom_28,custom_29,custom_30,custom_31,custom_32,custom_33,custom_34
                )
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
                 ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";

        try {
            // Prepare the statement for update data
            if ($this->cudPreparedStatement == null) {
                $this->cudPreparedStatement = $this->pdo->prepare($sql);
            }

            // Associates a value with a parameter
            // and execute INSERT prepared request
            $this->cudPreparedStatement->execute([
                $tpArticle->getPosted(), $tpArticle->getExpires(), $tpArticle->getAuthorID(), $tpArticle->getLastMod(),
                $tpArticle->getLastModID(), $tpArticle->getTitle(), $tpArticle->getTitleHtml(), $tpArticle->getBody(),
                $tpArticle->getBodyHtml(), $tpArticle->getExcerpt(), $tpArticle->getExcerptHtml(), $tpArticle->getImage(),
                $tpArticle->getCategory1(), $tpArticle->getCategory2(), $tpArticle->getAnnotate(),
                $tpArticle->getAnnotateInvite(), $tpArticle->getCommentsCount(), $tpArticle->getStatus(),
                $tpArticle->getTextileBody(), $tpArticle->getTextileExcerpt(), $tpArticle->getSection(),
                $tpArticle->getOverrideForm(), $tpArticle->getKeywords(), $tpArticle->getUrlTitle(), $tpArticle->getCustom1(),
                $tpArticle->getCustom2(), $tpArticle->getCustom3(), $tpArticle->getCustom4(), $tpArticle->getCustom5(),
                $tpArticle->getCustom6(), $tpArticle->getCustom7(), $tpArticle->getCustom8(), $tpArticle->getCustom9(),
                $tpArticle->getCustom10(), $tpArticle->getUid(), $tpArticle->getFeedTime(), $tpArticle->getCustom11(),
                $tpArticle->getCustom12(), $tpArticle->getCustom13(), $tpArticle->getCustom14(), $tpArticle->getCustom15(),
                $tpArticle->getCustom16(), $tpArticle->getCustom17(), $tpArticle->getCustom18(), $tpArticle->getCustom19(),
                $tpArticle->getCustom20(), $tpArticle->getCustom21(), $tpArticle->getCustom22(), $tpArticle->getCustom23(),
                $tpArticle->getCustom24(), $tpArticle->getCustom25(), $tpArticle->getCustom26(), $tpArticle->getCustom27(),
                $tpArticle->getCustom28(), $tpArticle->getCustom29(), $tpArticle->getCustom30(), $tpArticle->getCustom31(),
                $tpArticle->getCustom32(), $tpArticle->getCustom33(), $tpArticle->getCustom34()
            ]);

            $lastInsertId = $this->pdo->lastInsertId();

        } catch (\PDOException $exception) {
//            $this->pdo->rollBack();
        }

        return $lastInsertId;
    }

    /**
     * @param TextPatternDTO $tpArticle
     */
    private function update(TextPatternDTO $tpArticle)
    {
         $sql = "UPDATE textpattern SET custom_27 = ? WHERE id = ? ";
//        $sql = "UPDATE textpattern SET custom_27 = CONCAT_WS(',', custom_27, ? )
//                WHERE id = ? ";
        try {
            // Prepare the statement for update data
            if ($this->cudPreparedStatement == null) {
                $this->cudPreparedStatement = $this->pdo->prepare($sql);
            }

            // Associates a value with a parameter
            // and execute UPDATE prepared request
            $this->cudPreparedStatement->execute([
                $tpArticle->getCustom27(),

                // Must be at the end of the table
                $tpArticle->getID()
            ]);

        } catch (\PDOException $exception) {
//            $this->pdo->rollBack();
        }
    }

    /**
     * Validate the transaction or rollback
     */
    public function flush() {
//        try {
//            $this->pdo->commit();
//        } catch (\PDOException $exception) {
//            $this->pdo->rollBack();
//        }
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
            $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, TextPatternDTO::class);
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
     * @return TextPatternDTO
     */
    public function getOneAsEntity()
    {
        try {
            $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, TextPatternDTO::class);
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
                    $qb->where(" $key = ? ");

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