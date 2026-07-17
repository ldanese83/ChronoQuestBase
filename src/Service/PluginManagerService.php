<?php

namespace App\Service;

use PDO;
use Throwable;
use ZipArchive;

class PluginManagerService
{
    private const BUILTIN_DEFINITIONS = [
        'corse' => [
            'menus' => [
                'student' => [
                    [
                        'label_key' => 'plugin.corse.menu',
                        'url' => '/studenti/plugin/corse',
                        'icon' => 'fas fa-fw fa-flag-checkered',
                        'order' => 50,
                    ],
                ],
                'teacher' => [
                    [
                        'label_key' => 'plugin.corse.menu',
                        'url' => '/docenti/plugin/corse',
                        'icon' => 'fas fa-fw fa-flag-checkered',
                        'order' => 50,
                    ],
                ],
            ],
            'routes' => [
                [
                    'method' => 'GET',
                    'path' => '/docenti/plugin/corse',
                    'handler' => 'App\\Plugin\\Corse\\CorsePluginController@teacherIndex',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/corse/auto',
                    'handler' => 'App\\Plugin\\Corse\\CorsePluginController@saveCar',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/corse/importa-auto-demo',
                    'handler' => 'App\\Plugin\\Corse\\CorsePluginController@importDemoCars',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/corse/auto/{carId}/elimina',
                    'handler' => 'App\\Plugin\\Corse\\CorsePluginController@deleteCar',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/corse/gare',
                    'handler' => 'App\\Plugin\\Corse\\CorsePluginController@createRace',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/corse/gare/{raceId}/chiudi',
                    'handler' => 'App\\Plugin\\Corse\\CorsePluginController@closeRace',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'GET',
                    'path' => '/studenti/plugin/corse',
                    'handler' => 'App\\Plugin\\Corse\\CorsePluginController@studentIndex',
                    'area' => 'student',
                ],
                [
                    'method' => 'POST',
                    'path' => '/studenti/plugin/corse/auto/{carId}/acquista',
                    'handler' => 'App\\Plugin\\Corse\\CorsePluginController@buyCar',
                    'area' => 'student',
                ],
                [
                    'method' => 'POST',
                    'path' => '/studenti/plugin/corse/auto/{ownedCarId}/equipaggia',
                    'handler' => 'App\\Plugin\\Corse\\CorsePluginController@equipCar',
                    'area' => 'student',
                ],
                [
                    'method' => 'POST',
                    'path' => '/studenti/plugin/corse/gare/{raceId}/riscuoti',
                    'handler' => 'App\\Plugin\\Corse\\CorsePluginController@claimPrize',
                    'area' => 'student',
                ],
            ],
        ],
        'fight_the_monster' => [
            'menus' => [
                'student' => [
                    [
                        'label_key' => 'plugin.ftm.menu',
                        'url' => '/studenti/plugin/fight-the-monster',
                        'icon' => 'fas fa-fw fa-dragon',
                        'order' => 60,
                    ],
                ],
                'teacher' => [
                    [
                        'label_key' => 'plugin.ftm.menu',
                        'url' => '/docenti/plugin/fight-the-monster',
                        'icon' => 'fas fa-fw fa-dragon',
                        'order' => 60,
                    ],
                ],
            ],
            'events' => [
                PluginEventBus::EVENT_DELIVERY_EVALUATED => [
                    'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginListener@onDeliveryEvaluated',
                ],
                PluginEventBus::EVENT_CHEST_EARNED => [
                    'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginListener@onChestEarned',
                ],
            ],
            'routes' => [
                [
                    'method' => 'GET',
                    'path' => '/docenti/plugin/fight-the-monster',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@teacherIndex',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/fight-the-monster/mostri',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@saveMonster',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/fight-the-monster/importa-demo',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@importDemoAssets',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/fight-the-monster/mostri/{monsterId}/attiva',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@activateMonster',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/fight-the-monster/mostro/agisci',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@monsterAct',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/fight-the-monster/eroi',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@saveHero',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/fight-the-monster/armi',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@saveItem',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'POST',
                    'path' => '/docenti/plugin/fight-the-monster/armi/{itemId}/elimina',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@deleteItem',
                    'area' => 'teacher',
                ],
                [
                    'method' => 'GET',
                    'path' => '/studenti/plugin/fight-the-monster',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@studentIndex',
                    'area' => 'student',
                ],
                [
                    'method' => 'GET',
                    'path' => '/studenti/plugin/fight-the-monster/armi',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@studentItems',
                    'area' => 'student',
                ],
                [
                    'method' => 'POST',
                    'path' => '/studenti/plugin/fight-the-monster/armi/{studentItemId}/equipaggia',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@equipItem',
                    'area' => 'student',
                ],
                [
                    'method' => 'POST',
                    'path' => '/studenti/plugin/fight-the-monster/drop-visti',
                    'handler' => 'App\\Plugin\\FightTheMonster\\FightTheMonsterPluginController@acknowledgeDefeatDrops',
                    'area' => 'student',
                ],
            ],
        ],
        'forzieri_bonus' => [
            'default_config' => [
                'moltiplicatore' => 2,
            ],
        ],
    ];

    private TranslationService $translator;
    private PermissionService $permissionService;

    public function __construct()
    {
        $this->translator = new TranslationService();
        $this->permissionService = new PermissionService();
    }

    public function isActive(string $pluginCode, ?int $classId = null): bool
    {
        $plugin = $this->getPluginByCode($pluginCode);
        if ($plugin === null || (int) ($plugin['attivo'] ?? 0) !== 1) {
            return false;
        }

        if ($classId === null) {
            return true;
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT attivo
             FROM ct_plugin_classe
             WHERE fk_plugin = :fk_plugin
               AND fk_classe = :fk_classe
             LIMIT 1'
        );
        $stmt->execute([
            'fk_plugin' => (int) $plugin['id_plugin'],
            'fk_classe' => $classId,
        ]);

        return ((int) ($stmt->fetchColumn() ?: 0)) === 1;
    }

    public function getStudentMenuItems(?int $classId = null): array
    {
        return $this->getMenuItems('student', $classId);
    }

    public function getTeacherMenuItems(?int $classId = null): array
    {
        return $this->getMenuItems('teacher', $classId);
    }

    public function getPluginByCode(string $pluginCode): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_plugin, codice_plugin, nome_plugin, versione, attivo, descrizione, configurazione_json
             FROM ct_plugin
             WHERE codice_plugin = :codice_plugin
             LIMIT 1'
        );
        $stmt->execute(['codice_plugin' => $this->normalizePluginCode($pluginCode)]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $this->decodePluginRow($row);
    }

    public function getEventListeners(string $eventName, ?int $classId = null): array
    {
        if ($classId === null) {
            $classId = $this->permissionService->getCurrentClassId();
        }

        if ($classId === null) {
            return [];
        }

        $listeners = [];
        foreach ($this->getPluginsForClass($classId) as $plugin) {
            $pluginCode = $this->normalizePluginCode((string) ($plugin['codice_plugin'] ?? ''));
            if ($pluginCode === '' || (int) ($plugin['attivo'] ?? 0) !== 1 || (int) ($plugin['classe_attivo'] ?? 0) !== 1) {
                continue;
            }

            foreach ($this->getPluginEventListeners($pluginCode, $plugin, $eventName) as $listener) {
                $listeners[] = [
                    'plugin' => $pluginCode,
                    'event' => $eventName,
                    'listener' => (string) $listener,
                ];
            }
        }

        return $listeners;
    }

    public function getRouteDefinitions(): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id_plugin, codice_plugin, nome_plugin, versione, attivo, descrizione, configurazione_json
             FROM ct_plugin
             WHERE attivo = 1
             ORDER BY nome_plugin ASC'
        );
        $stmt->execute();

        $routes = [];
        $registeredRouteKeys = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) ?: [] as $row) {
            $plugin = $this->decodePluginRow($row);
            $pluginCode = $this->normalizePluginCode((string) ($plugin['codice_plugin'] ?? ''));
            $pluginRoutes = self::BUILTIN_DEFINITIONS[$pluginCode]['routes'] ?? [];
            $configuredRoutes = is_array($plugin['configuration']['routes'] ?? null) ? $plugin['configuration']['routes'] : [];

            foreach (array_merge($pluginRoutes, $configuredRoutes) as $route) {
                if (!is_array($route)) {
                    continue;
                }

                $routeKey = strtoupper((string) ($route['method'] ?? 'GET')) . ' ' . (string) ($route['path'] ?? '');
                if (isset($registeredRouteKeys[$routeKey])) {
                    continue;
                }
                $registeredRouteKeys[$routeKey] = true;

                $route['plugin'] = $pluginCode;
                $routes[] = $route;
            }
        }

        return $routes;
    }

    public function getPluginsForClass(?int $classId): array
    {
        if ($classId === null) {
            $stmt = Database::getConnection()->prepare(
                'SELECT id_plugin, codice_plugin, nome_plugin, versione, attivo, descrizione, configurazione_json
                 FROM ct_plugin
                 ORDER BY nome_plugin ASC'
            );
            $stmt->execute();
        } else {
            $stmt = Database::getConnection()->prepare(
                'SELECT p.id_plugin,
                        p.codice_plugin,
                        p.nome_plugin,
                        p.versione,
                        p.attivo,
                        p.descrizione,
                        p.configurazione_json,
                        pc.attivo AS classe_attivo,
                        pc.configurazione_json AS classe_configurazione_json
                 FROM ct_plugin p
                 LEFT JOIN ct_plugin_classe pc
                   ON pc.fk_plugin = p.id_plugin
                  AND pc.fk_classe = :fk_classe
                 ORDER BY p.nome_plugin ASC'
            );
            $stmt->execute(['fk_classe' => $classId]);
        }

        return array_map(fn (array $row): array => $this->decodePluginRow($row), $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    public function getManagementPageData(): array
    {
        $areaStatus = $this->permissionService->checkTeacherAreaAccess();
        $classId = $this->permissionService->getCurrentClassId();

        return [
            'permissionStatus' => $areaStatus,
            'classroom' => $classId !== null ? $this->getClassroom($classId) : null,
            'plugins' => $areaStatus === PermissionService::STATUS_OK ? $this->getPluginsForClass($classId) : [],
            'isAdmin' => $this->currentUserIsAdmin(),
        ];
    }

    public function saveClassPlugins(array $input): array
    {
        $permissionStatus = $this->permissionService->checkPermissionsTeacher();
        if ($permissionStatus !== PermissionService::STATUS_OK) {
            return $this->permissionError($permissionStatus);
        }

        $classId = $this->permissionService->getCurrentClassId();
        if ($classId === null) {
            return $this->error('plugin.manage.error.no_class');
        }

        $plugins = $this->getPluginsForClass($classId);
        $activePluginIds = array_map('intval', $input['plugin_attivo'] ?? []);
        $configs = is_array($input['plugin_config'] ?? null) ? $input['plugin_config'] : [];

        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();

            foreach ($plugins as $plugin) {
                $pluginId = (int) ($plugin['id_plugin'] ?? 0);
                if ($pluginId <= 0) {
                    continue;
                }

                $classConfig = trim((string) ($configs[$pluginId] ?? ''));
                if ($classConfig !== '' && json_decode($classConfig, true) === null && json_last_error() !== JSON_ERROR_NONE) {
                    throw new \RuntimeException($this->translator->translate('plugin.manage.error.invalid_json'));
                }

                $params = [
                    'fk_plugin' => $pluginId,
                    'fk_classe' => $classId,
                    'attivo' => in_array($pluginId, $activePluginIds, true) ? 1 : 0,
                    'configurazione_json' => $classConfig !== '' ? $classConfig : null,
                ];

                $existing = $pdo->prepare(
                    'SELECT id_plugin_classe
                     FROM ct_plugin_classe
                     WHERE fk_plugin = :fk_plugin
                       AND fk_classe = :fk_classe
                     LIMIT 1'
                );
                $existing->execute([
                    'fk_plugin' => $pluginId,
                    'fk_classe' => $classId,
                ]);

                $existingId = (int) ($existing->fetchColumn() ?: 0);
                if ($existingId > 0) {
                    $stmt = $pdo->prepare(
                        'UPDATE ct_plugin_classe
                         SET attivo = :attivo,
                             configurazione_json = :configurazione_json
                         WHERE id_plugin_classe = :id_plugin_classe'
                    );
                    $stmt->execute([
                        'attivo' => $params['attivo'],
                        'configurazione_json' => $params['configurazione_json'],
                        'id_plugin_classe' => $existingId,
                    ]);
                    continue;
                }

                $stmt = $pdo->prepare(
                    'INSERT INTO ct_plugin_classe (fk_plugin, fk_classe, attivo, configurazione_json)
                     VALUES (:fk_plugin, :fk_classe, :attivo, :configurazione_json)'
                );
                $stmt->execute($params);
            }

            if ($pdo->inTransaction()) {
                $pdo->commit();
            }

            return [
                'success' => true,
                'message' => 'plugin.manage.saved',
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function createPlugin(array $input, array $uploadedPackage = []): array
    {
        if (!$this->currentUserIsAdmin()) {
            return $this->error('plugin.manage.error.admin_only');
        }

        try {
            $package = $this->readPluginPackage($uploadedPackage);
        } catch (Throwable $exception) {
            return $this->error($exception->getMessage());
        }
        $manifest = $package['manifest'];

        $name = trim((string) ($manifest['nome_plugin'] ?? $manifest['name'] ?? $input['nome_plugin'] ?? ''));
        $code = $this->normalizePluginCode((string) ($manifest['codice_plugin'] ?? $manifest['code'] ?? $input['codice_plugin'] ?? ''));
        $version = trim((string) ($manifest['versione'] ?? $manifest['version'] ?? $input['versione'] ?? '1.0'));
        $description = trim((string) ($manifest['descrizione'] ?? $manifest['description'] ?? $input['descrizione'] ?? ''));
        $configuration = $this->buildPluginConfigurationFromManifest($manifest);
        if ($configuration !== []) {
            $encodedConfiguration = json_encode($configuration, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if (!is_string($encodedConfiguration)) {
                return $this->error('plugin.manage.error.invalid_json');
            }
            $configurationJson = $encodedConfiguration;
        } else {
            $configurationJson = trim((string) ($input['configurazione_json'] ?? ''));
        }
        $installSql = (string) ($package['install_sql'] ?? trim((string) ($input['install_sql'] ?? '')));

        if ($name === '' || $code === '') {
            return $this->error('plugin.manage.error.required');
        }

        if (!preg_match('/^[a-z0-9_]+$/', $code)) {
            return $this->error('plugin.manage.error.invalid_code');
        }

        if ($configurationJson !== '' && json_decode($configurationJson, true) === null && json_last_error() !== JSON_ERROR_NONE) {
            return $this->error('plugin.manage.error.invalid_json');
        }

        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            if ($this->getPluginByCode($code) !== null) {
                throw new \RuntimeException($this->translator->translate('plugin.manage.error.duplicate_code'));
            }

            $stmt = $pdo->prepare(
                'INSERT INTO ct_plugin (id_plugin, nome_plugin, codice_plugin, versione, attivo, descrizione, configurazione_json)
                 VALUES (:id_plugin, :nome_plugin, :codice_plugin, :versione, 1, :descrizione, :configurazione_json)'
            );
            $stmt->execute([
                'id_plugin' => $this->getNextPluginId(),
                'nome_plugin' => $name,
                'codice_plugin' => $code,
                'versione' => $version !== '' ? $version : '1.0',
                'descrizione' => $description !== '' ? $description : null,
                'configurazione_json' => $configurationJson !== '' ? $configurationJson : null,
            ]);

            if ($installSql !== '') {
                $this->runPluginCreateTableSql($installSql);
            }

            $storedPackage = $this->storePluginPackage($code, $uploadedPackage);
            if ($storedPackage !== null) {
                $this->extractPluginPackageFiles($storedPackage, $code);
            }

            if ($pdo->inTransaction()) {
                $pdo->commit();
            }

            return [
                'success' => true,
                'message' => 'plugin.manage.created',
            ];
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->error($exception->getMessage());
        }
    }

    public function applyRewardModifiers(array $rewardContext): array
    {
        $classId = isset($rewardContext['class_id']) ? (int) $rewardContext['class_id'] : null;
        $event = (string) ($rewardContext['event'] ?? '');

        if ($event === 'chest.earned' && $this->isActive('forzieri_bonus', $classId)) {
            $config = $this->getMergedConfiguration('forzieri_bonus', $classId);
            $multiplier = max(1, min(3, (int) ($config['moltiplicatore'] ?? 2)));
            $rewardContext['quantity'] = max(1, (int) ($rewardContext['quantity'] ?? 1)) * $multiplier;
        }

        return $rewardContext;
    }

    public function currentUserIsAdmin(): bool
    {
        $userId = $this->permissionService->getCurrentUserId();
        return $userId !== null && $this->permissionService->isAdmin($userId);
    }

    private function getMenuItems(string $area, ?int $classId): array
    {
        if ($classId === null) {
            $classId = $this->permissionService->getCurrentClassId();
        }

        if ($classId === null) {
            return [];
        }

        $items = [];
        foreach ($this->getPluginsForClass($classId) as $plugin) {
            $pluginCode = $this->normalizePluginCode((string) ($plugin['codice_plugin'] ?? ''));
            if ($pluginCode === '' || (int) ($plugin['attivo'] ?? 0) !== 1 || (int) ($plugin['classe_attivo'] ?? 0) !== 1) {
                continue;
            }

            foreach ($this->getPluginMenusForArea($pluginCode, $plugin, $area) as $menu) {
                $labelKey = (string) ($menu['label_key'] ?? '');
                $label = $labelKey !== '' ? $this->translator->translate($labelKey) : '';

                $items[] = [
                    'code' => $pluginCode,
                    'label' => $label !== '' ? $label : (string) ($menu['label'] ?? $plugin['nome_plugin'] ?? $pluginCode),
                    'url' => (string) ($menu['url'] ?? '#'),
                    'icon' => (string) ($menu['icon'] ?? 'fas fa-fw fa-puzzle-piece'),
                    'order' => (int) ($menu['order'] ?? 100),
                ];
            }
        }

        usort($items, fn (array $a, array $b): int => ($a['order'] <=> $b['order']) ?: strcmp($a['label'], $b['label']));

        return $items;
    }

    private function getPluginMenusForArea(string $pluginCode, array $plugin, string $area): array
    {
        $definition = self::BUILTIN_DEFINITIONS[$pluginCode] ?? [];
        $menus = [];

        if (isset($definition['menus'][$area]) && is_array($definition['menus'][$area])) {
            $menus = array_merge($menus, $definition['menus'][$area]);
        }

        $legacyDefinitionKey = $area === 'student' ? 'student_menu' : 'teacher_menu';
        if (isset($definition[$legacyDefinitionKey]) && is_array($definition[$legacyDefinitionKey])) {
            $menus[] = $definition[$legacyDefinitionKey];
        }

        $configurationMenus = $plugin['configuration']['menus'][$area] ?? [];
        if (is_array($configurationMenus)) {
            $menus = array_merge($menus, $this->normalizeMenuDefinitions($configurationMenus));
        }

        $uniqueMenus = [];
        $registeredMenuKeys = [];
        foreach ($menus as $menu) {
            if (!is_array($menu)) {
                continue;
            }

            $menuKey = (string) ($menu['url'] ?? '');
            if ($menuKey === '') {
                $menuKey = (string) ($menu['label_key'] ?? $menu['label'] ?? '');
            }

            if ($menuKey !== '' && isset($registeredMenuKeys[$menuKey])) {
                continue;
            }

            if ($menuKey !== '') {
                $registeredMenuKeys[$menuKey] = true;
            }

            $uniqueMenus[] = $menu;
        }

        return $uniqueMenus;
    }

    private function normalizeMenuDefinitions(array $menus): array
    {
        if (isset($menus['url']) || isset($menus['label']) || isset($menus['label_key'])) {
            return [$menus];
        }

        return $menus;
    }

    private function getPluginEventListeners(string $pluginCode, array $plugin, string $eventName): array
    {
        $definition = self::BUILTIN_DEFINITIONS[$pluginCode] ?? [];
        $listeners = [];

        if (isset($definition['events'][$eventName]) && is_array($definition['events'][$eventName])) {
            $listeners = array_merge($listeners, $definition['events'][$eventName]);
        }

        $configuredListeners = $plugin['configuration']['events'][$eventName] ?? [];
        if (is_string($configuredListeners)) {
            $listeners[] = $configuredListeners;
        } elseif (is_array($configuredListeners)) {
            $listeners = array_merge($listeners, $configuredListeners);
        }

        $listeners = array_filter($listeners, fn ($listener): bool => is_string($listener) && trim($listener) !== '');
        return array_values(array_unique($listeners));
    }

    private function getMergedConfiguration(string $pluginCode, ?int $classId): array
    {
        $plugin = $this->getPluginByCode($pluginCode);
        $globalConfig = is_array($plugin['configuration'] ?? null) ? $plugin['configuration'] : [];
        $defaultConfig = self::BUILTIN_DEFINITIONS[$pluginCode]['default_config'] ?? [];

        if ($classId === null || $plugin === null) {
            return array_replace($defaultConfig, $globalConfig);
        }

        $stmt = Database::getConnection()->prepare(
            'SELECT configurazione_json
             FROM ct_plugin_classe
             WHERE fk_plugin = :fk_plugin
               AND fk_classe = :fk_classe
             LIMIT 1'
        );
        $stmt->execute([
            'fk_plugin' => (int) $plugin['id_plugin'],
            'fk_classe' => $classId,
        ]);

        $classConfigJson = (string) ($stmt->fetchColumn() ?: '');
        $classConfig = $classConfigJson !== '' ? json_decode($classConfigJson, true) : [];
        if (!is_array($classConfig)) {
            $classConfig = [];
        }

        return array_replace($defaultConfig, $globalConfig, $classConfig);
    }

    private function getClassroom(int $classId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT c.id_classe, c.nome_classe, a.anno_scolastico
             FROM ct_classi c
             INNER JOIN ct_anni_scolastici a ON a.id_anno = c.fk_anno_scolastico
             WHERE c.id_classe = :id_classe
             LIMIT 1'
        );
        $stmt->execute(['id_classe' => $classId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    private function getNextPluginId(): int
    {
        $stmt = Database::getConnection()->prepare('SELECT COALESCE(MAX(id_plugin), 0) + 1 FROM ct_plugin');
        $stmt->execute();

        return max(1, (int) $stmt->fetchColumn());
    }

    private function decodePluginRow(array $row): array
    {
        $row['configuration'] = $this->decodeJson((string) ($row['configurazione_json'] ?? ''));
        $row['class_configuration'] = $this->decodeJson((string) ($row['classe_configurazione_json'] ?? ''));
        $row['classe_attivo'] = isset($row['classe_attivo']) ? (int) $row['classe_attivo'] : 0;

        return $row;
    }

    private function decodeJson(string $json): array
    {
        if (trim($json) === '') {
            return [];
        }

        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function normalizePluginCode(string $pluginCode): string
    {
        $pluginCode = strtolower(trim($pluginCode));
        $pluginCode = preg_replace('/[^a-z0-9_]+/', '_', $pluginCode) ?? $pluginCode;
        return trim($pluginCode, '_');
    }

    private function readPluginPackage(array $uploadedPackage): array
    {
        $error = (int) ($uploadedPackage['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            return [
                'manifest' => [],
                'install_sql' => null,
            ];
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->translator->translate('plugin.manage.error.upload'));
        }

        $tmpName = (string) ($uploadedPackage['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException($this->translator->translate('plugin.manage.error.upload'));
        }

        $zip = new ZipArchive();
        if ($zip->open($tmpName) !== true) {
            throw new \RuntimeException($this->translator->translate('plugin.manage.error.invalid_package'));
        }

        try {
            $manifestPath = $this->findFirstZipPath($zip, ['plugin.json', 'manifest.json', '.chronoquest/plugin.json']);
            if ($manifestPath === null) {
                throw new \RuntimeException($this->translator->translate('plugin.manage.error.missing_manifest'));
            }

            $manifestContent = $this->getZipEntryContent($zip, $manifestPath);
            if (is_string($manifestContent)) {
                $manifestContent = preg_replace('/^\xEF\xBB\xBF/', '', $manifestContent) ?? $manifestContent;
            }
            $manifest = is_string($manifestContent) ? json_decode($manifestContent, true) : null;
            if (!is_array($manifest)) {
                throw new \RuntimeException($this->translator->translate('plugin.manage.error.invalid_manifest'));
            }

            $installSql = null;
            $installSqlPath = trim((string) ($manifest['install_sql'] ?? $manifest['installSql'] ?? ''));
            if ($installSqlPath !== '') {
                $installSqlPath = $this->findManifestReferencedZipPath($zip, $manifestPath, $installSqlPath);
                $installSql = $this->getZipEntryContent($zip, $installSqlPath);
                if (!is_string($installSql)) {
                    throw new \RuntimeException($this->translator->translate('plugin.manage.error.missing_install_sql'));
                }
            } else {
                $defaultSqlPath = $this->findFirstZipPath($zip, ['sql/install.sql', 'install.sql']);
                if ($defaultSqlPath !== null) {
                    $installSql = (string) $this->getZipEntryContent($zip, $defaultSqlPath);
                }
            }

            return [
                'manifest' => $manifest,
                'install_sql' => $installSql,
            ];
        } finally {
            $zip->close();
        }
    }

    private function buildPluginConfigurationFromManifest(array $manifest): array
    {
        $configuration = [];
        if (isset($manifest['configuration']) && is_array($manifest['configuration'])) {
            $configuration = $manifest['configuration'];
        } elseif (isset($manifest['configurazione']) && is_array($manifest['configurazione'])) {
            $configuration = $manifest['configurazione'];
        }

        foreach (['menus', 'routes', 'events'] as $key) {
            if (isset($manifest[$key]) && is_array($manifest[$key])) {
                $configuration[$key] = $manifest[$key];
            }
        }

        return $configuration;
    }

    private function findFirstZipPath(ZipArchive $zip, array $paths): ?string
    {
        foreach ($paths as $path) {
            $path = $this->normalizeZipPath((string) $path);
            $entryPath = $this->findZipEntryPath($zip, $path);
            if ($entryPath !== null) {
                return $entryPath;
            }
        }

        $fileNames = array_map(fn ($path): string => basename($this->normalizeZipPath((string) $path)), $paths);
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $entryName = $this->normalizeZipPath((string) $zip->getNameIndex($index));
            if (in_array(basename($entryName), $fileNames, true)) {
                return $entryName;
            }
        }

        return null;
    }

    private function findZipEntryPath(ZipArchive $zip, string $path): ?string
    {
        $path = $this->normalizeZipPath($path);
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $entryName = (string) $zip->getNameIndex($index);
            if ($this->normalizeZipPath($entryName) === $path) {
                return $entryName;
            }
        }

        return null;
    }

    private function getZipEntryContent(ZipArchive $zip, string $path): string|false
    {
        $entryPath = $this->findZipEntryPath($zip, $path);
        if ($entryPath === null) {
            return false;
        }

        return $zip->getFromName($entryPath);
    }

    private function normalizeZipPath(string $path): string
    {
        return ltrim(str_replace('\\', '/', trim($path)), '/');
    }

    private function findManifestReferencedZipPath(ZipArchive $zip, string $manifestPath, string $path): string
    {
        $path = $this->normalizeZipPath($path);
        $entryPath = $this->findZipEntryPath($zip, $path);
        if ($entryPath !== null) {
            return $entryPath;
        }

        $manifestDir = trim(dirname($this->normalizeZipPath($manifestPath)), '.');
        $relativePath = $manifestDir !== '' ? $manifestDir . '/' . $path : $path;
        $relativeEntryPath = $this->findZipEntryPath($zip, $relativePath);
        return $relativeEntryPath ?? $this->normalizeZipPath($relativePath);
    }

    private function runPluginCreateTableSql(string $sql): void
    {
        $sql = preg_replace('/^\xEF\xBB\xBF/', '', $sql) ?? $sql;
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $statement) {
            $statement = preg_replace('/^\xEF\xBB\xBF/', '', $statement) ?? $statement;
            $isCreatePluginTable = preg_match('/^CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?ct_plugin_[a-z0-9_]+`?\s*\(/i', $statement) === 1;
            $isInsertPluginTable = preg_match('/^INSERT\s+INTO\s+`?ct_plugin_[a-z0-9_]+`?\s*(?:\(|\s)/i', $statement) === 1;
            if (!$isCreatePluginTable && !$isInsertPluginTable) {
                throw new \RuntimeException($this->translator->translate('plugin.manage.error.only_create_table'));
            }

            Database::getConnection()->exec($statement);
        }
    }

    private function storePluginPackage(string $pluginCode, array $uploadedPackage): ?string
    {
        $error = (int) ($uploadedPackage['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException($this->translator->translate('plugin.manage.error.upload'));
        }

        $tmpName = (string) ($uploadedPackage['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException($this->translator->translate('plugin.manage.error.upload'));
        }

        $originalName = preg_replace('/[^a-zA-Z0-9._-]+/', '_', (string) ($uploadedPackage['name'] ?? 'plugin.zip')) ?: 'plugin.zip';
        $relativeDir = '/plugin-packages/' . $pluginCode;
        $absoluteDir = dirname(__DIR__, 2) . '/upload' . $relativeDir;

        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            throw new \RuntimeException($this->translator->translate('plugin.manage.error.upload_folder'));
        }

        $destination = $absoluteDir . '/' . date('Ymd_His') . '_' . $originalName;
        if (!move_uploaded_file($tmpName, $destination)) {
            throw new \RuntimeException($this->translator->translate('plugin.manage.error.upload'));
        }

        return $destination;
    }

    private function extractPluginPackageFiles(string $zipPath, string $pluginCode): void
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException($this->translator->translate('plugin.manage.error.invalid_package'));
        }

        try {
            $projectRoot = dirname(__DIR__, 2);
            for ($index = 0; $index < $zip->numFiles; $index++) {
                $entryName = $this->normalizeZipPath((string) $zip->getNameIndex($index));
                if ($entryName === '' || str_ends_with($entryName, '/')) {
                    continue;
                }

                if (!$this->isInstallablePluginFile($entryName, $pluginCode)) {
                    continue;
                }

                $target = $projectRoot . '/' . $entryName;
                $targetDir = dirname($target);
                if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
                    throw new \RuntimeException($this->translator->translate('plugin.manage.error.upload_folder'));
                }

                $content = $zip->getFromIndex($index);
                if (is_string($content) && str_ends_with(strtolower($entryName), '.php')) {
                    $content = preg_replace('/^\xEF\xBB\xBF/', '', $content) ?? $content;
                }
                if (!is_string($content) || file_put_contents($target, $content) === false) {
                    throw new \RuntimeException($this->translator->translate('plugin.manage.error.extract'));
                }
            }
        } finally {
            $zip->close();
        }
    }

    private function isInstallablePluginFile(string $entryName, string $pluginCode): bool
    {
        if (str_contains($entryName, '../') || str_starts_with($entryName, '/')) {
            return false;
        }

        $pluginStudly = str_replace(' ', '', ucwords(str_replace('_', ' ', $pluginCode)));
        $allowedPrefixes = [
            'src/Plugin/' . $pluginStudly . '/',
            'public/views/plugin/' . $pluginCode . '/',
            'public/css/plugin_' . $pluginCode . '/',
            'public/js/plugin_' . $pluginCode . '/',
            'public/assets/plugin_' . $pluginCode . '/',
            'translations/it/plugin_' . $pluginCode . '.php',
            'translations/en/plugin_' . $pluginCode . '.php',
        ];

        foreach ($allowedPrefixes as $prefix) {
            if (str_starts_with($entryName, $prefix)) {
                return true;
            }
        }

        return false;
    }

    private function permissionError(int $permissionStatus): array
    {
        return match ($permissionStatus) {
            PermissionService::STATUS_NOT_LOGGED => $this->error('permission.nologin'),
            PermissionService::STATUS_NOT_TEACHER => $this->error('permission.noteacher'),
            PermissionService::STATUS_NO_CLASS => $this->error('plugin.manage.error.no_class'),
            default => $this->error('teacher.classes.select.error'),
        };
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }
}
