<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 04/01/2018
 * Time: 15:08
 */

namespace app\Controller;

use app\DAO\TextPatternDAO;
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
        $options = ['options' => ['default'=>5, 'min_range'=>0]];
        $nbOfResults = filter_input(INPUT_GET, 'results',FILTER_VALIDATE_INT, $options);

        $search = ['Section' => 'particulier-entreprise'];
        $limits = [$nbOfResults, $nbOfResults * 2];

        $personalBusiness = $this->getTextPatternDAO()
            ->find($search, [], $limits)->getAllAsArray();

        return $response->withJson($personalBusiness);
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
}