<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 04/01/2018
 * Time: 15:08
 */

namespace app\Controller;

use app\DAO\TextPatternDAO;
use app\Entities\TextPatternDTO;
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
        // filter input 'results' parameter
        $options = ['options' => ['default' => 5, 'min_range' => 0]];
        $nbOfResults = filter_input(INPUT_GET, 'results', FILTER_VALIDATE_INT, $options);

        $search = ['Section' => 'particulier-entreprise'];
        $limits = [$nbOfResults, $nbOfResults * 2];

        $personalBusiness = $this->getTextPatternDAO()
            ->find($search, [], $limits)->getAllAsArray();

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
        // filter input 'results' parameter
        $options = ['options' => ['default' => 5, 'min_range' => 0]];
        $nbOfResults = filter_input(INPUT_GET, 'results', FILTER_VALIDATE_INT, $options);

        $search = ['Section' => 'auto-entrepreneur', 'Status' => 4];

        $limits = [$nbOfResults, $nbOfResults * 2];

        $missionsToApply = $this->getTextPatternDAO()
            ->find($search, [], $limits)->getAllAsArray();

        return $response->withJson($missionsToApply);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function applyToAMission(Request $request, Response $response)
    {

        $txpArticle = $request->getParams();


        $txpDTO = $this->getTextPatternDTO();
        $txpDTO->hydrate($txpArticle);


        $missionFromDB = $this->getTextPatternDAO()->findOneById([$txpDTO->getID()])
            ->getOneAsArray();

        $retStatus = $this->checkCandidateBeforeApplyToMission($missionFromDB, $txpArticle, $txpDTO);

        if ($retStatus['status']) {
            $this->getTextPatternDAO()
                ->save($txpDTO)->flush();
        }

        return $response->withJson($retStatus);
    }

    /**
     * @return TextPatternDAO
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getTextPatternDAO()
    {
        return $this->ctx->get('textpattern.dao');
    }

    /**
     * @return TextPatternDTO
     */
    private function getTextPatternDTO()
    {
        $dao = null;
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
            'toto' => $dataFromDB['custom_27']
        ];

        //if(count($dataFromDB) > 0)
        if (!empty($dataFromDB)) {
            $candidates = explode(',', $dataFromDB['custom_27']);

            if (strlen($dataFromDB['custom_27']) > 0 && !in_array($txpArticle['user'], $candidates)) {
                $txpDTO->setCustom27($dataFromDB['custom_27'] . ',' . $txpArticle['user']);
                $retStatus['ici'] = '1er';
            } elseif (strlen($dataFromDB['custom_27']) == 0) {
                $retStatus['ici'] = '2e';
                $txpDTO->setCustom27($txpArticle['user']);
            } else {
                $retStatus['ici'] = '3e';
                $retStatus['status'] = false;
                $retStatus['message'] = 'You have already applied for this mission';
            }

        } else {
            $retStatus['ici'] = 'ee';
            $retStatus['status'] = false;
            $retStatus['message'] = 'Sorry, the mission to which you want to apply does not exist';
        }
        return $retStatus;
    }
}