<?php

use App\Controllers\Login;
use App\Controllers\News;
use App\Enums\HttpMethods;
use App\Routing\Router;

Router::register('/', 'index', HttpMethods::GET, Login::class, 'index');
Router::register('/login', 'login', HttpMethods::POST, Login::class, 'login');
Router::register('/logout', 'logout', HttpMethods::GET, Login::class, 'logout');

Router::register('/news', 'news', HttpMethods::GET, News::class, 'list');
Router::register('/news/getsingle', 'news.getsingle', HttpMethods::POST, News::class, 'getSingle');
Router::register('/news/add', 'news.add', HttpMethods::POST, News::class, 'add');
Router::register('/news/edit', 'news.edit', HttpMethods::POST, News::class, 'edit');
Router::register('/news/delete', 'news.delete', HttpMethods::POST, News::class, 'delete');
