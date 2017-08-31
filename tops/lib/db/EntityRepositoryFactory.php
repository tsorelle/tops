<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 8/31/2017
 * Time: 6:58 AM
 */

namespace Tops\db;


use Tops\sys\TConfiguration;

class EntityRepositoryFactory
{
    /**
     * @param $name
     * @param string $namespace
     * @return IEntityRepository
     * @throws \Exception
     */
    public static function Get($name,$namespace=null) {
        if (empty($name)) {
            throw new \Exception('no repository name provided.');
        }
        if (empty($namespace)) {
            $namespace = TConfiguration::getValue('repositoryNamespace','services');
            if (empty($namespace)) {
                throw new \Exception('Namespace for repositories not configured.');
            }
        }
        $className = $namespace.'\\'.strtoupper(substr($name,0,1)).substr($name,1).'Repository';
        return new $className();
    }
}