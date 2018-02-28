<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 04/01/2018
 * Time: 15:08
 */

namespace app\Controller;

use app\DAO\TextPatternDAO;
use app\DAO\UserDAO;
use app\Entities\TextPatternDTO;
use DateTime;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class TextPatternCtrl
{
    /**
     * @var ContainerInterface
     */
    private $ctx;

    /**
     * TextPatternCtrl constructor.
     * @param ContainerInterface $ctx
     */
    public function __construct(ContainerInterface $ctx)
    {
        $this->ctx = $ctx;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getPersonalBusiness(Request $request, Response $response)
    {
        $search = ['particulier-entreprise' => 'Section LIKE ?'];

        // filter input 'results' parameter
        $options = ['options' => ['default' => 15, 'min_range' => 0]];
        $nbOfResults = filter_input(INPUT_GET, 'results', FILTER_VALIDATE_INT, $options);

        $kills = filter_input(INPUT_GET, 'Keywords', FILTER_SANITIZE_STRING);
        if (!empty($kills)) {
            $search['Keywords'] = explode(',', $kills);
        }

        $fromCrDat = filter_input(INPUT_GET, 'fromCrDat', FILTER_SANITIZE_STRING);
        if (!empty($fromCrDat)) {
            $search[$fromCrDat] = 'Posted < ?';
        }
        // $search = ['Section' => 'particulier-entreprise'];
        // $search = ['particulier-entreprise' => 'Section LIKE ?', '2018-01-26 15:05:29' => 'Posted < ?'];

        $limits = [$nbOfResults];

        $personalBusiness = $this->getTextPatternDAO()
            ->find($search, ['Posted' => 'DESC'], $limits)->getAllAsArray();

        return $response->withJson($personalBusiness);
    }
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getOnePersonalBusiness(Request $request, Response $response)
    {
        // filter input 'results' parameter
        $options = ['options' => ['default' => 1, 'min_range' => 1]];
        $nbOfResults = filter_input(INPUT_GET, 'results', FILTER_VALIDATE_INT, $options);
        $name = $request->getParam('name');
        // $kills = filter_input(INPUT_GET, 'Keywords', FILTER_SANITIZE_STRING);

        // $search = ['Section' => 'particulier-entreprise','AuthorID' => $name];
        $search = ['particulier-entreprise' => 'Section LIKE ?', 'AuthorID' => [$name]];

//        if (!empty($kills)) {
//            $search['Keywords'] = explode(',', $kills);
//        }

        $limits = [$nbOfResults];

        $personalBusiness = $this->getTextPatternDAO()
            ->find($search, [], $limits)->getOneAsArray();

        return $response->withJson($personalBusiness);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListOfMissionsToApply(Request $request, Response $response)
    {
        $search = ['auto-entrepreneur' => 'Section LIKE ?', 'Status' => [4]];

        // filter input 'results' parameter
        $options = ['options' => ['default' => 15, 'min_range' => 0]];
        $nbOfResults = filter_input(INPUT_GET, 'results', FILTER_VALIDATE_INT, $options);
        $kills = filter_input(INPUT_GET, 'Keywords', FILTER_SANITIZE_STRING);

        $fromCrDat = filter_input(INPUT_GET, 'fromCrDat', FILTER_SANITIZE_STRING);
        if (!empty($fromCrDat)) {
            $search[$fromCrDat] = 'Posted < ?';
        }

        //$search = ['Section' => 'auto-entrepreneur', 'Status' => 4];

        if (!empty($kills)) {
            $search['Keywords'] = explode(',', $kills);
        }

        // $getMyCandidate = filter_var($request->getParam('me'), FILTER_VALIDATE_BOOLEAN);

        $limits = [$nbOfResults];

            $missionsToApply = $this->getTextPatternDAO()
            ->find($search, ['Posted' => 'DESC'], $limits)->getAllAsArray();

        return $response->withJson($missionsToApply);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getListOfMyProjects(Request $request, Response $response)
    {
        $user = $this->getJWTObj()->username;
        $search = ['auto-entrepreneur' => 'Section LIKE ?', 'AuthorID' => [$user]];

        // filter input 'results' parameter
        $options = ['options' => ['default' => 15, 'min_range' => 0]];
        $nbOfResults = filter_input(INPUT_GET, 'results', FILTER_VALIDATE_INT, $options);
        $kills = filter_input(INPUT_GET, 'Keywords', FILTER_SANITIZE_STRING);

        $fromCrDat = filter_input(INPUT_GET, 'fromCrDat', FILTER_SANITIZE_STRING);
        if (!empty($fromCrDat)) {
            $search[$fromCrDat] = 'Posted < ?';
        }

//        $search = ['Section' => 'auto-entrepreneur', 'Status' => 4, 'AuthorID' => $user];

        if (!empty($kills)) {
            $search['Keywords'] = explode(',', $kills);
        }

        $limits = [$nbOfResults];

        $myProjects = $this->getTextPatternDAO()
            ->find($search, [], $limits)->getAllAsArray();

        // clean datas by remove the '-' character
        $myProjects = $this->cleanDatas($myProjects, '-');

        return $response->withJson($myProjects);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getListsOfMyCandidatures(Request $request, Response $response) {
        $user = $this->getJWTObj()->username;
        $search = ['auto-entrepreneur' => 'Section LIKE ?', 'Status' => [4], "%$user%" => 'Custom_27 LIKE ?'];

        // filter input 'results' parameter
        $options = ['options' => ['default' => 15, 'min_range' => 0]];
        $nbOfResults = filter_input(INPUT_GET, 'results', FILTER_VALIDATE_INT, $options);
        $kills = filter_input(INPUT_GET, 'Keywords', FILTER_SANITIZE_STRING);

        $fromCrDat = filter_input(INPUT_GET, 'fromCrDat', FILTER_SANITIZE_STRING);
        if (!empty($fromCrDat)) {
            $search[$fromCrDat] = 'Posted < ?';
        }

        //$search = ['Section' => 'auto-entrepreneur', 'Status' => 4, 'Custom_27' => "%$user%"];

        if (!empty($kills)) {
            $search['Keywords'] = explode(',', $kills);
        }

        //$limits = [$nbOfResults, rand(0, 36)];
        $limits = [$nbOfResults];
        $myCandidatures = $this->getTextPatternDAO()
            ->find($search, [], $limits)->getAllAsArray();

        return $response->withJson($myCandidatures);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function applyToAMission(Request $request, Response $response)
    {

        $txpArticle = $request->getParams();
        $txpArticle['user'] = $this->getJWTObj()->username;

        $txpDTO = $this->getTextPatternDTO();
        $txpDTO->hydrate($txpArticle);


        $missionFromDB = $this->getTextPatternDAO()->findOneById([$txpDTO->getID()])
            ->getOneAsArray();

        $retStatus = $this->checkCandidateBeforeApplyToMission($missionFromDB, $txpArticle, $txpDTO);

        if ($retStatus['status']) {
            $this->getTextPatternDAO()
                ->save($txpDTO);
        }

        return $response->withJson($retStatus);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function persistArticle(Request $request, Response $response)
    {
        $txpArticle = $request->getParams();
        $txpArticle['AuthorID'] = $this->getJWTObj()->username;

        $txpArticle = $this->initializationOfDefaultValues($txpArticle);

        $txpDTO = $this->getTextPatternDTO();
        $txpDTO->hydrate($txpArticle);

        // Verification of required fields
        $msg = $this->verificationOfRequiredFields($txpDTO, $txpArticle['AuthorID'] ?? '');

        // If there is no error message, persist data on DB
        if (empty($msg)) {
            $dao = $this->getTextPatternDAO();
            $dao->save($txpDTO);
            $msg['success'] = true;
            $msg['ID'] = $txpDTO->getID();
        }

        return $response->withJson($msg);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function deleteArticle(Request $request, Response $response) {
        $txpArticle = $request->getParams();
        $txpArticle['AuthorID'] = $this->getJWTObj()->username;

        $txpDTO = $this->getTextPatternDTO();
        $txpDTO->hydrate($txpArticle);

        $dao = $this->getTextPatternDAO();
        $article = $dao->findOneById([$txpDTO->getID()])
            ->getOneAsArray();

        $msg['success'] = false;

        if ($article && ($article['AuthorID'] == $txpArticle['AuthorID'])) {
            $msg['success'] = $dao->delete($txpDTO);
        }

        return $response->withJson($msg);
    }

    /**
     * @param array $txpArticle
     * @return array $txpArticle
     */
    private function initializationOfDefaultValues(array $txpArticle)
    {
        $date = new \DateTime();
        $dateNow = $date->format('Y-m-d H:i:s');

        if (!isset($txpArticle['Posted']) || empty($txpArticle['Posted'])) {
            $txpArticle['Posted'] = $dateNow;
        }

        $txpArticle['LastMod'] = (!isset($txpArticle['LastMod']) || empty($txpArticle['LastMod'])) ? $dateNow : 0;

        $txpArticle['LastModID'] = (!empty($txpArticle['ID'])) ? $txpArticle['AuthorID'] : '';

        // Si no status, par default actif
        $txpArticle['Status'] = $txpArticle['Status'] ?? 4;
        if ($txpArticle['Status'] == 4) {
            $txpArticle['custom_1'] = 'actif';
        } else {
            $txpArticle['custom_1'] = 'inactif';
        }

        $txpArticle['Section'] = 'auto-entrepreneur';
        $txpArticle['Annotate'] = $txpArticle['Annotate'] ?? 0;
        $txpArticle['url_title'] = $txpArticle['url_title'] ?? urlencode($txpArticle['Title']);

        $txpArticle['Expires'] = $txpArticle['Expires'] ?? 0;
        $txpArticle['comments_count'] = $txpArticle['comments_count'] ?? 0;
        $txpArticle['textile_body'] = $txpArticle['textile_body'] ?? 1;
        $txpArticle['textile_excerpt'] = $txpArticle['textile_excerpt'] ?? 1;
        $txpArticle['feed_time'] = $txpArticle['feed_time'] ?? 0;

        if (!isset($txpArticle['Title_html']) || empty($txpArticle['Title_html']))
            $txpArticle['Title_html'] = $txpArticle['Title'] ?? '';

        if (!isset($txpArticle['Body_html']) || empty($txpArticle['Body_html']))
            $txpArticle['Body_html'] = $txpArticle['Body'] ?? '';

        if (!isset($txpArticle['Excerpt']) || empty($txpArticle['Excerpt']))
            $txpArticle['Excerpt'] = $txpArticle['Body'] ?? '';

        if (!isset($txpArticle['Excerpt_html']) || empty($txpArticle['Excerpt_html']))
            $txpArticle['Excerpt_html'] = $txpArticle['Body'] ?? '';

        if (!isset($txpArticle['Image']) || empty($txpArticle['Image']))
            $txpArticle['Image'] = 'no';

        if (!isset($txpArticle['Category2']) || empty($txpArticle['Category2']))
            $txpArticle['Category2'] = $txpArticle['Category1'] ?? '';

        if (!isset($txpArticle['AnnotateInvite']) || empty($txpArticle['AnnotateInvite']))
            $txpArticle['AnnotateInvite'] = $txpArticle['Annotate'];

        if (!isset($txpArticle['override_form']) || empty($txpArticle['override_form']))
            $txpArticle['override_form'] = '-';

        if (!isset($txpArticle['uid']) || empty($txpArticle['uid'])) {
            $txpArticle['uid'] = '-';
        }

        for ($i = 1; $i <= 34; ++$i) {
            if ($i != 27 && (!isset($txpArticle['custom_' . $i]) || empty($txpArticle['custom_' . $i]))) {
                $txpArticle['custom_' . $i] = '-';
            }
        }

        return $txpArticle;
    }

    /**
     * @param TextPatternDTO $txpArticle
     * @param string $name
     * @return array
     */
    private function verificationOfRequiredFields($txpArticle, $name)
    {
        $msg = [];

        if ($txpArticle->getTitle() == null) {
            $msg['title'] = false;
        }

        if ($txpArticle->getBody() == null) {
            $msg['body'] = false;
        }

        if ($txpArticle->getCategory1() == null) {
            $msg['Category1'] = false;
        }

        if ($txpArticle->getKeywords() == null) {
            $msg['Keywords'] = false;
        }

        $userInfos = $this->getUserInfosData($name);
        if ($userInfos == false) {
            $msg['name'] = false;
            $msg['message'][] = "The User does not exist";
        } else {
            $txpArticle->setCustom4($userInfos['phone']);
            $txpArticle->setCustom5($userInfos['phone']);
            $txpArticle->setCustom11($userInfos['last_name']);
            $txpArticle->setCustom12($userInfos['first_name']);
            $txpArticle->setCustom17($userInfos['entreprise']);
        }

        // If there is at least one field that has been incorrectly completed
        if (!empty($msg)) {
            $msg['error'] = true;
            $msg['message'][] = 'Please complete all required fields correctly.';
        }

        return $msg;

    }

    /**
     * @return TextPatternDAO
     */
    private function getTextPatternDAO()
    {
        $dao = null;
        try {
            $dao = $this->ctx->get('textpattern.dao');
        } catch (ContainerExceptionInterface $exception) {

        }

        return $dao;
    }


    /**
     * @return TextPatternDTO
     */
    private function getTextPatternDTO()
    {
        $dto = null;
        try {
            $dto = $this->ctx->get('textpattern.dto');
        } catch (ContainerExceptionInterface $exception) {
        }

        return $dto;
    }

    /**
     * @param array $dataFromDB
     * @param array $txpArticle
     * @param TextPatternDTO $txpDTO
     * @return array
     */
    private function checkCandidateBeforeApplyToMission($dataFromDB, $txpArticle, $txpDTO): array
    {
        $retStatus = [
            'status' => true,
            'message' => 'Registered candidate',
        ];


        if ($this->getUserInfosData($txpArticle['user']) == false) {
            $retStatus['status'] = false;
            $retStatus['message'] = 'User does not exist';
        } else {

            if (!empty($dataFromDB)) {
                $candidates = explode(',', $dataFromDB['custom_27']);

                if (strlen($dataFromDB['custom_27']) > 0 && !in_array($txpArticle['user'], $candidates)) {
                    $txpDTO->setCustom27($dataFromDB['custom_27'] . ',' . $txpArticle['user']);
                    $retStatus['ici'] = '1er';
                } elseif (strlen($dataFromDB['custom_27']) == 0) {
                    $retStatus['ici'] = '2e';
                    $txpDTO->setCustom27($txpArticle['user']);
                } else {
                    //$retStatus['ici'] = '3e';
                    $retStatus['status'] = false;
                    $retStatus['message'] = 'You have already applied for this mission';
                }

            } else {
                //$retStatus['ici'] = 'ee';
                $retStatus['status'] = false;
                $retStatus['message'] = 'Sorry, the mission to which you want to apply does not exist';
            }
        }

        return $retStatus;
    }


    /**
     * @param string $name
     * @return array|boolean
     */
    private function getUserInfosData(string $name)
    {
        $dao = $this->getUserDAO();
        $user = $dao->findOneByName($name)->getOneAsArray();

        return $user;
    }

    /**
     * @return UserDAO
     */
    private function getUserDAO()
    {
        $dao = null;
        try {
            $dao = $this->ctx->get('user.dao');
        } catch (ContainerExceptionInterface $exception) {
        }

        return $dao;
    }

    /**
     * @return \StdClass
     */
    private function getJWTObj() {
        $jwt = null;
        try {
            $jwt = $this->ctx->get('jwt');
        } catch (ContainerExceptionInterface $exception) {
        }

        return $jwt;
    }

    /**
     * @param array $myProjects
     * @param string $str
     * @return array
     */
    private function cleanDatas(array $myProjects, string $str):array
    {
        $retArray = [];
        $element = [];

        foreach ($myProjects as $firstKey => $arr) {

            $element['candidates'] = [];

            foreach ($arr as $key => $value) {

                if ($key == 'custom_27' && strlen($arr[$key]) > 0 && $arr[$key] != $str) {
                    $element['candidates'] = explode(',', $arr[$key]);
                }

                $val = $value;
                if (strlen($value) == 1 && $value == $str) {
                    $val = '';
                }

                $element[$key] = $val;
            }

            $retArray[$firstKey] = $element;
        }

        return $retArray;
    }
}