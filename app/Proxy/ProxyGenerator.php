<?php

namespace App\Proxy;

use App\Attributes\CheckFeatureFlag;
use ReflectionClass;
use ReflectionMethod;

class ProxyGenerator
{
    public function make(object $originalService): object
    {
        $reflection = new ReflectionClass($originalService);
        $shortName = $reflection->getShortName().'FeatureProxy';
        $namespace = 'App\\GeneratedProxies';
        $fullProxyClass = $namespace.'\\'.$shortName;
        $filePath = storage_path("framework/proxies/{$shortName}.php");

        // Генеруємо файл проксі, якщо його ще немає в кеші
        if (! file_exists($filePath)) {
            $code = $this->compileProxyCode($reflection, $namespace, $shortName);
            if (! is_dir(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }
            file_put_contents($filePath, $code);
        }

        require_once $filePath;

        return new $fullProxyClass($originalService);
    }

    private function compileProxyCode(ReflectionClass $reflection, string $namespace, string $shortName): string
    {
        $originalClass = '\\'.$reflection->getName();
        $methodsCode = '';

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(CheckFeatureFlag::class);
            if (empty($attributes)) {
                continue;
            }

            /** @var CheckFeatureFlag $attr */
            $attr = $attributes[0]->newInstance();
            $methodName = $method->getName();

            // Збираємо сигнатуру параметрів методу
            $params = [];
            $args = [];
            foreach ($method->getParameters() as $param) {
                $type = $param->getType() ? $param->getType()->getName().' ' : '';
                $params[] = "{$type}\${$param->getName()}";
                $args[] = "\${$param->getName()}";
            }

            $paramsStr = implode(', ', $params);
            $argsStr = implode(', ', $args);
            $returnType = $method->getReturnType() ? ': '.$method->getReturnType()->getName() : '';
            $fallbackExport = var_export($attr->fallbackReturn, true);

            // Коректно обробляємо методи з типом повернення void
            $fallbackStatement = ($method->getReturnType()?->getName() === 'void')
                ? 'return;'
                : "return {$fallbackExport};";

            $methodsCode .= <<<PHP

    public function {$methodName}({$paramsStr}){$returnType}
    {
        if (config('features.{$attr->flagName}')) {
            return \$this->inner->{$methodName}({$argsStr});
        }

        {$fallbackStatement}
    }
PHP;
        }

        return <<<PHP
<?php

namespace {$namespace};

class {$shortName} extends {$originalClass}
{
    public function __construct(private readonly {$originalClass} \$inner) {}
{$methodsCode}
}
PHP;
    }
}
