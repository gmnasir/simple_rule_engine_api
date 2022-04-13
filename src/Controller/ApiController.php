<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    #[Route('/api/upload-file', name: 'index', methods: 'POST')]
    public function index(Request $request): Response
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'https://debricked.com/api/login_check', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer 840b33fecd238bfd92ff8880236fb32f13cdd0abb425fa0b',
            ],
            'body' => [
                '_username' => 'dev.nasir.k@gmail.com',
                '_password' => 'Nasir#4321',
            ],
        ]);

        // $statusCode = $response->getStatusCode();

        // $contentType = $response->getHeaders()['content-type'][0];

        // $content = $response->getContent();

        $content = $response->toArray();
        $jwt_token = 'Bearer '.$content['token'];
        $data = json_decode($request->getContent(), true);

        $dependency_upload = $client->request('POST', 'https://debricked.com/api/1.0/open/uploads/dependencies/files', [
            'headers' => [
                'Accept' => '*',
                'Content-Type' => 'multipart/form-data',
                'Authorization' => $jwt_token,
            ],

            'body' => base64_encode($request->files->get('file')),
        ]);

        $statusCode = $dependency_upload->getStatusCode();
        dd($statusCode);
        $res = $dependency_upload->toArray();
        dd($res);

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
