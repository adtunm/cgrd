<?php

namespace App\Controllers;

use App\Database\Models\NewsModel;
use App\Routing\Responses\JsonResponse;
use App\Routing\Responses\RedirectResponse;
use App\Routing\Responses\Response;
use App\Routing\Responses\ResponseInterface;
use App\Services\NewsService;

class News extends AbstractController
{
    public const RESTRICTED_METHODS = [
        'add',
        'edit',
        'delete',
        'list',
        'getSingle'
    ];
    private NewsService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new  NewsService();
    }

    public function add(\stdClass $data): ResponseInterface
    {
        $this->checkParameters($data, [
            'title' => 'string',
            'content' => 'string'
        ]);

        $error = $this->service->checkErrors($data);

        if ($error !== null) {
            $this->notification->addNotification($error, 'ERROR');
            return new RedirectResponse('news');
        }

        $news = new NewsModel();
        $news->content = $data->content;
        $news->title = $data->title;

        $result = $this->service->save($news);

        if (!$result) {
            $this->notification->addNotification('Something went wrong during saving', 'ERROR');
        } else {
            $this->notification->addNotification('News is added', 'OK');
        }

        return new RedirectResponse('news');
    }

    public function edit(\stdClass $data): RedirectResponse
    {
        $this->checkParameters($data, [
            'id' => 'int',
            'title' => 'string',
            'content' => 'string',
        ]);

        $news = new NewsModel();
        $news->id = $data->id;
        $news->title = $data->title;
        $news->content = $data->content;

        $isNewsSame = $this->service->isNewsSame($news);

        if ($isNewsSame) {
            $this->notification->addNotification('News isn\'t modified', 'ERROR');
        } else {
            $result = $this->service->edit($news);
            if ($result === 1) {
                $this->notification->addNotification('News is edited', 'OK');
            } else {
                $this->notification->addNotification('Something went wrong during edit', 'ERROR');
            }
        }

        return new RedirectResponse('news');
    }

    public function getSingle(\stdClass $data): JsonResponse
    {
        $this->checkParameters($data, [
            'id' => 'int'
        ]);

        $news = $this->service->get($data->id);
        $response = $this->service->getResponse($news);

        if ($response['status'] === 'ERROR') {
            $this->notification->addNotification('News don\'t exist', 'ERROR');
        }

        return new JsonResponse(
            $response
        );
    }

    public function delete(\stdClass $data): JsonResponse
    {
        $this->checkParameters($data, [
            'id' => 'int'
        ]);
        $result = $this->service->delete($data->id);

        if ($result) {
            $this->notification->addNotification('News is deleted', 'OK');
        } else {
            $this->notification->addNotification('Something went wrong during deleting', 'ERROR');
        }

        return new JsonResponse(['status' => 'OK']);
    }

    public function list(): ResponseInterface
    {
        $news = $this->checkNews($this->service->getAll());
        $notification = $this->notification->getNotification();

        return new Response('news.html.twig', [
            'news' => $news,
            'notification' => $notification['value'] ?? null,
            'notificationStatus' => $notification['status'] ?? null,
            'pageTitle' => 'News'
        ]);
    }

    private function truncate(string $text, int $chars): string
    {
        if (strlen($text) <= $chars) {
            return $text;
        }

        $text = substr($text, 0, $chars - 3);

        return $text."...";
    }

    /**
     * @param NewsModel[] $news
     * @return array
     */
    private function checkNews(array $news): array
    {
        foreach ($news as $singleNews) {
            $singleNews->content = $this->truncate($singleNews->content, 55);
            $singleNews->title = $this->truncate($singleNews->title, 15);
        }

        return $news;
    }

}