<?php

namespace Elvisthermiranda\Router;

use Elvisthermiranda\Router\Exceptions\ContainerException;
use ReflectionClass;

class Container
{
    private $instances = [];

    /**
     * Registra uma instância de um serviço.
     *
     * @param string $name
     * @param object $instance
     */
    public function set(string $name, $instance)
    {
        $this->instances[$name] = $instance;
    }

    /**
     * Obtém uma instância de um serviço.
     *
     * @param string $name
     * @return object
     * @throws ContainerException
     */
    public function get(string $name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $reflectionClass = new ReflectionClass($name);

        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Classe {$name} não é instanciável.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor)) {
            $object = new $name;
            $this->set($name, $object);
            return $object;
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        $object = $reflectionClass->newInstanceArgs($dependencies);
        $this->set($name, $object);

        return $object;
    }

    /**
     * Resolve as dependências de um método ou construtor.
     *
     * @param array $parameters
     * @return array
     * @throws ContainerException
     */
    private function resolveDependencies(array $parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            if ($type === null) {
                throw new ContainerException("Não é possível resolver a dependência de classe {$parameter->name}.");
            }

            $dependencies[] = $this->get($type->getName());
        }

        return $dependencies;
    }
}
