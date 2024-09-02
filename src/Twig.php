<?php

namespace App;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
{
    private static Twig $instance;
    private Environment $twig;

    private function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public static function init(): void
    {
        $loader = new FilesystemLoader('resources/views', '/var/www/project-cgrd/');
        $twig = new Environment($loader);

        self::$instance = new static($twig);
    }

    public static function getInstance(): static
    {
        if (!isset(self::$instance)) {
            throw new \Exception('Not initialized');
        }

        return self::$instance;
    }

    public function render(string $template, array $context): string
    {
        return $this->twig->render($template, $context);
    }
}