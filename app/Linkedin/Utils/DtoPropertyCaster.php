<?php
namespace App\Linkedin\Utils;

use App\Linkedin\Contracts\DTO;
use Illuminate\Contracts\Container\BindingResolutionException;

class DtoPropertyCaster
{
    /**
     * Cached docs
     */
    private static $dmCache = [];

    /**
     * Get class doc comment
     *
     * @param string $class
     * @return string|null
     */
    private static function getDocComment(string $class): ?string
    {
        if (!isset(self::$dmCache[$class])) {
            try {
                $reflector = new \ReflectionClass($class);
                self::$dmCache[$class] = $reflector->getDocComment();
            } catch (\ReflectionException $e) {
                self::$dmCache[$class] = '';
            }
        }
        return self::$dmCache[$class];
    }

    /**
     * Find property simple type comment by attributeName in provided class
     *
     * @param string $class
     * @param string $attributeName
     * @return string|null
     */
    private static function findSimplePropertyType(string $class, string $attributeName): ?string
    {
        try {
            $documentation = self::getDocComment($class);

            $regex = "/@property (bool|boolean|int|integer|float|string|array) $attributeName\s+/";
            preg_match($regex, $documentation, $matches);
            return $matches[1] ?? null;
        } catch (\ReflectionException $e) {
            return null;
        }
    }

    /**
     * Find property DTO type comment by attributeName in provided class
     *
     * @param string $class
     * @param string $attributeName
     * @return array|null
     */
    private static function findDtoPropertyType(string $class, string $attributeName): ?array
    {
        try {
            $documentation = self::getDocComment($class);

            $regex = "/@property ([A-Z][^\s|]+) $attributeName\s+/";
            preg_match($regex, $documentation, $matches);
            if (!isset($matches[1])) {
                return null;
            }

            $params = [
                'abstract' => $matches[1],
                'multiple' => false
            ];

            if (strpos($matches[1], '[]') !== false) {
                $params['multiple'] = true;
                $params['abstract'] = str_replace('[]', '', $params['abstract']);
            }

            return $params;
        } catch (\ReflectionException $e) {
            return null;
        }
    }

    /**
     * Cast value to bool
     *
     * @param mixed $value
     * @return bool
     */
    private static function castToBool($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        //String to bool
        if (is_string($value)) {
            if ($value === 'yes' || $value === 'no') {
                return ($value === 'yes');
            }
            if ($value === 'on' || $value === 'off') {
                return ($value === 'on');
            }
            if ($value === 'true' || $value === 'false') {
                return ($value === 'true');
            }
            if ($value === '1' || $value === '0') {
                return ($value === '1');
            }
            if ($value === '' || strlen($value) > 0) {
                return $value === '';
            }
        }

        return (bool) $value;
    }

    /**
     * Cast value to string
     *
     * @param $value
     * @return string
     */
    private static function castToString($value): string
    {
        if (is_string($value)) {
            return $value;
        }

        // Float to string
        if (is_float($value) || is_double($value)) {
            return rtrim(sprintf('%f', $value), 0);
        }

        // Array to string (Without Notice)
        if (is_array($value)) {
            return "Array";
        }

        return (string) $value;
    }

    /**
     * Cast value to int
     *
     * @param $value
     * @return int
     */
    private static function castToInt($value): int
    {
        return (int) $value;
    }

    /**
     * Cast value to float
     *
     * @param $value
     * @return float
     */
    private static function castToFloat($value): float
    {
        return (float) $value;
    }

    /**
     * Cast value to array
     *
     * @param $value
     * @return array
     */
    private static function castToArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        return [$value];
    }

    /**
     * Cast value to DTO object
     *
     * @param array|null $params
     * @param $value
     * @return DTO[]|DTO|null
     */
    private static function castToDTO(?array $params, $value)
    {
        $abstract = $params['abstract'] ?? null;
        $multipleMode = $params['multiple'] ?? false;

        // Binding does not exist
        if (!$abstract || !app()->has($abstract)) {
            return $value;
        }

        // Value is empty, and DTO is registered as collection, return empty array
        if ($multipleMode && empty($value)) {
            return [];
        }

        try {
            // Make Object from Container and check it's DTO or not
            $obj = app()->make($abstract);
            if (!($obj instanceof DTO)) {
                return $value;
            }

            // Return $value, because it is the same type of $obj
            if (is_object($value) && $obj instanceof $value) {
                return $value;
            }

            // If value contains multiple items, then make DTO for each item in $value
            $containsMultipleItems = is_array($value) && !empty($value) && array_keys($value) === range(0, count($value) - 1);
            if ($containsMultipleItems) {
                $returnValue = [];
                foreach ($value as $item) {
                    $currentOne = clone $obj;
                    $currentOne->setAttributes($item instanceof DTO ? $item->getAttributes() : $item);

                    $returnValue[] = $currentOne;
                }
            } else {
                $obj->setAttributes($value);
                $returnValue = $obj;
            }

            // If multiple mode set, then wrap in array
            if ($multipleMode) {
                $returnValue = !is_array($returnValue) ? [$returnValue] : $returnValue;
            }

            return $returnValue;
        } catch (BindingResolutionException $e) {
            return null;
        }
    }

    /**
     * Casts value type to Class's DocComment property type
     *
     * @param string $class
     * @param string $attributeName
     * @param $value
     * @return mixed
     */
    public static function cast(string $class, string $attributeName, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        // Cast to simple type (int, bool, string etc...)
        $simplePropertyType = self::findSimplePropertyType($class, $attributeName);
        if (!is_null($simplePropertyType)) {
            return self::castSimple($simplePropertyType, $value);
        }

        // Cast to DTO object (or multiple DTO objects of same type)
        $params = self::findDtoPropertyType($class, $attributeName);
        if (!is_null($params)) {
            return self::castToDTO($params, $value);
        }

        return $value;
    }

    /**
     * Cast $value to simple type (bool, int, float, string, array)
     *
     * @param string $propertyType
     * @param $value
     * @return array|bool|float|int|string
     */
    private static function castSimple(string $propertyType, $value)
    {
        switch ($propertyType) {
            case 'bool':
                return self::castToBool($value);
            case 'int':
                return self::castToInt($value);
                break;
            case 'float':
                return self::castToFloat($value);
                break;
            case 'string':
                return self::castToString($value);
                break;
            case 'array':
                return self::castToArray($value);
                break;
            default:
                return $value;
        }
    }
}
