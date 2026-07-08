<?php

namespace App\Service;

use Throwable;

class PluginEventBus
{
    public const EVENT_DELIVERY_SUBMITTED = 'delivery.submitted';
    public const EVENT_DELIVERY_EVALUATED = 'delivery.evaluated';
    public const EVENT_CHEST_EARNED = 'chest.earned';
    public const EVENT_STUDENT_REWARD_ASSIGNED = 'student.reward.assigned';

    public function dispatch(string $eventName, array $payload = []): array
    {
        $classId = isset($payload['class_id']) ? (int) $payload['class_id'] : null;
        $listeners = (new PluginManagerService())->getEventListeners($eventName, $classId);
        $results = [];

        foreach ($listeners as $listener) {
            $listenerId = (string) ($listener['listener'] ?? '');
            if ($listenerId === '') {
                continue;
            }

            try {
                $result = $this->callListener($listenerId, $payload, $eventName);
                if ($result !== null) {
                    $results[] = [
                        'plugin' => (string) ($listener['plugin'] ?? ''),
                        'listener' => $listenerId,
                        'result' => $result,
                    ];
                }
            } catch (Throwable $exception) {
                $results[] = [
                    'plugin' => (string) ($listener['plugin'] ?? ''),
                    'listener' => $listenerId,
                    'error' => $exception->getMessage(),
                ];
            }
        }

        return $results;
    }

    private function callListener(string $listenerId, array $payload, string $eventName): mixed
    {
        [$className, $methodName] = array_pad(explode('@', $listenerId, 2), 2, '');
        if ($className === '' || $methodName === '' || !class_exists($className)) {
            throw new \RuntimeException('Plugin listener non valido: ' . $listenerId);
        }

        $listener = new $className();
        if (!method_exists($listener, $methodName)) {
            throw new \RuntimeException('Metodo listener non trovato: ' . $listenerId);
        }

        return $listener->{$methodName}($payload, $eventName);
    }
}
