<?php
namespace App\Overrides;

use Illuminate\Routing\ResourceRegistrar as BaseResourceRegistrar;

class ResourceRegistrar extends BaseResourceRegistrar
{

    protected $resourceDefaults = [
        'index',
        'create',
        'store',
        'tablesettings',
        'savetablesettings',
        'edit',
        'update',
        'destroy',
    ];

    protected static $verbs = [
        'create' => 'create',
        'edit' => 'edit',
        'tablesettings' => 'tablesettings',
    ];



    /**
     * Настройки отображения таблиц
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceTablesettings($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/' . static::$verbs['tablesettings'];

        $action = $this->getResourceAction($name, $controller, 'tablesettings', $options);

        return $this->router->get($uri, $action);
    }
    /**
     * Настройки отображения таблиц
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceSavetablesettings($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/' . static::$verbs['tablesettings'];

        $action = $this->getResourceAction($name, $controller, 'save_tablesettings', $options);

        return $this->router->match(['POST'], $uri, $action);
    }

  
}
