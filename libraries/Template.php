<?php

class Template
{
    protected $template;
    protected $vars = [];

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function __get($key)
    {
        return $this->vars[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->vars[$key] = $value;
    }

    public function __toString()
    {
        $projectRoot = dirname(__DIR__);
        $templatePath = $this->template;

        // Resolve template paths from the project root, never from the current
        // working directory (which can differ on Vercel/serverless runtimes).
        if (!str_starts_with($templatePath, DIRECTORY_SEPARATOR)) {
            $templatePath = $projectRoot . DIRECTORY_SEPARATOR . ltrim($templatePath, '/\\');
        }

        $templatePath = realpath($templatePath);
        $templatesRoot = realpath($projectRoot . DIRECTORY_SEPARATOR . 'templates');

        if (
            $templatePath === false ||
            $templatesRoot === false ||
            !is_file($templatePath) ||
            !str_starts_with($templatePath, $templatesRoot . DIRECTORY_SEPARATOR)
        ) {
            error_log('TalkingSpace template not found: ' . $this->template);
            return '<!-- Template unavailable -->';
        }

        $vars = $this->vars;

        ob_start();
        try {
            extract($vars, EXTR_SKIP);
            include $templatePath;
            return (string) ob_get_clean();
        } catch (Throwable $e) {
            ob_end_clean();
            error_log('TalkingSpace template rendering failed: ' . $e->getMessage());
            return '<!-- Template rendering failed -->';
        }
    }
}
