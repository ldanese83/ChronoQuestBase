<?php

namespace App\Core;

// Router HTTP minimale con supporto a parametri dinamici nei path.
class Router
{
    // Struttura interna: [METHOD][path] => callback.
    private array $routes = [];

    // Registra una route GET.
    public function get($path, $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    // Registra una route POST.
    public function post($path, $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    // Effettua matching della richiesta corrente e invoca il callback.
    public function dispatch(): void
    {
        // Legge metodo HTTP reale della richiesta.
        $method = $_SERVER['REQUEST_METHOD'];
        // Normalizza URI rimuovendo query string.
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Se non esistono route per quel metodo, risponde 404.
        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo 'Metodo non supportato';
            return;
        }

        // Prova ogni route registrata per il metodo richiesto.
        foreach ($this->routes[$method] as $path => $callback) {
            // Converte placeholder tipo /utente/{id} in gruppo regex.
            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $path);
            // Ancora il pattern all'inizio/fine stringa URI.
            $pattern = '#^' . $pattern . '$#';

            // Se c'è match, recupera eventuali parametri dinamici.
            if (preg_match($pattern, $uri, $matches)) {
                // Rimuove la stringa completa lasciando solo i gruppi.
                array_shift($matches);

                // Gestisce callback funzione/closure diretta.
                if (is_callable($callback)) {
                    call_user_func_array($callback, $matches);
                    return;
                }

                // Gestisce callback in forma [Classe::class, metodo].
                if (is_array($callback)) {
                    [$class, $methodName] = $callback;
                    call_user_func_array([new $class(), $methodName], $matches);
                    return;
                }
            }
        }

        // Nessuna route compatibile trovata: 404 standard.
        http_response_code(404);
        echo 'Pagina non trovata';
    }
}
