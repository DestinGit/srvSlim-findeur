<?php
/**
 * Created by IntelliJ IDEA.
 * User: yemei
 * Date: 22/02/2018
 * Time: 14:56
 */

namespace app\Controller;


use app\DAO\CategoryDAO;
use app\Entities\CategoryDTO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class CategoryCtrl
{
    private $ctx;

    /**
     * CategoryCtrl constructor.
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
     */
    public function getAllSkills(Request $request, Response $response)
    {
        $search = ['parent' => [
            'metiers-du-web', 'assistance-et-secretariat', 'fonction-commerciale', 'ressources-humaines',
            'evenementiel-communication', 'services-generaux']
        ];

        $skills = $this->getCategoryDAO()->find($search)
            ->getAllAsArray();

        return $response->withJson($skills);
    }

    /**
     * @return CategoryDAO
     */
    private function getCategoryDAO()
    {
        $dao = null;
        try {
            $dao = $this->ctx->get('category.dao');
        } catch (ContainerExceptionInterface $exception) {

        }

        return $dao;

    }

    /**
     * @return CategoryDTO
     */
    private function getCategoryDTO()
    {
        $dto = null;
        try {
            $dto = $this->ctx->get('category.dto');
        } catch (ContainerExceptionInterface $exception) {
        }

        return $dto;

    }
}