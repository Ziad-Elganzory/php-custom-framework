<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ViewNotFoundException;

class View
{
    public function __construct(
        protected string $view,
        protected array $params = []
    ){}

    public static function make(string $view, array $params = [])
    {
        return new static($view, $params);
    } 

    public function render(): bool|string
    {
        $viewPath = VIEWS_PATH . '/' . $this->view . '.php';
        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException();
        }
        
        foreach ($this->params as $key => $value) {
            $$key = $value;
        }
        
        ob_start();
        include __DIR__ . '/../views/templates/header.php';
        include $viewPath;
        include __DIR__ . '/../views/templates/footer.php';
        $content = (string) ob_get_clean();
        
        return $this->parseTemplateVariables($content);
    }

    protected function parseTemplateVariables(string $content): string
    {
        return preg_replace_callback('/\{\{\s*(\w+)\s*\}\}/', function($matches) {
            try{
                $variable = $matches[1];
                if (array_key_exists($variable, $this->params)) {
                    return $this->params[$variable];
                }
                throw new \Exception("Variable {$variable} not found in view parameters.");
            } catch (\Exception $e) {
                echo $e->getMessage();
                return;
            }

        }, $content);
    }

    public function __toString(): string
    {
        return $this->render();
    }
}