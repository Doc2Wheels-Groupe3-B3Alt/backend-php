<?php

namespace App\Http;

class Response
{
    private string $content;
    private int $status;
    private array $headers;

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        if ($this->viewExists($content)) {
            $this->content = $this->renderView($content);
        }else{
            $this->content = $content;
        }
        $this->status = $status;
        $this->headers = $headers;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function addHeader(string $header): self
    {
        $this->headers[] = $header;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function renderView($view)
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent()
    {
        ob_start();
        include __DIR__ . '/../Views/layouts/main.php';
        return ob_get_clean();
    }

    protected function renderOnlyView($view)
    {
        ob_start();
        include __DIR__ . "/../Views/$view.php";
        return ob_get_clean();
    }

    private function viewExists($view)
    {
        return file_exists(__DIR__ . "/../Views/$view.php");
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
