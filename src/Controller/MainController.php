<?php

namespace App\Controller;

use App\Service\CallMarvelApiService;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private CallMarvelApiService $callMarvelApi;

    public function __construct(CallMarvelApiService $callMarvelApi)
    {
        $this->callMarvelApi = $callMarvelApi;
    }

    /**
     * @Route("/", defaults={"page": "1"}, name="characters")
     * @Route("/page/{page}", name="characters_paginated")
     */
    public function index(Request $request, int $page = 1): Response
    {
        $search = !empty($request->request->get('search')) ? $request->request->get('search') : null;

        $paginator = new Paginator($page, 50);

        $api_datas = $this->callMarvelApi->getCharacters(
            $paginator->getLimit(),
            $paginator->getOffset(),
            $search
        );

        $characters = $paginator->init($api_datas);

        return $this->render('main/index.html.twig', [
            'characters' => $characters,
            'api_datas' => $api_datas,
            'search' => $search
        ]);
    }

    /**
     * @Route("/{slug}", name="character")
     */
    public function show(Request $request): Response
    {
        $id = $request->request->get('id');

        $api_datas = $this->callMarvelApi->getCharacter($id);

        $character = $api_datas['data']['results'][0];

        return $this->render('main/show.html.twig', [
            'character' => $character,
            'api_datas' => $api_datas
        ]);
    }
}
