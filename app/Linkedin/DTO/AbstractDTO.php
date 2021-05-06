<?php
namespace App\Linkedin\DTO;
use App\Linkedin\Contracts\DTO;
use App\Linkedin\Contracts\DTOFactory;
use App\Linkedin\Utils\DtoPropertyCaster;

abstract class AbstractDTO implements DTO, \JsonSerializable
{
    /**
     * Nested DTO key map
     *
     * @var array
     */
    protected $nestedMap = [];

    /**
     * Fields
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Dynamic DTOs are not safe.
     * Use it only when you don't know input keys. like Json
     *
     * @var bool
     */
    protected $dynamic = false;

    /**
     * Is DTO initialized
     *
     * @var bool
     */
    protected $initialized = false;

    /**
     * AbstractDTO constructor.
     *
     * @param mixed $attributes
     */
    public function __construct($attributes = [])
    {
        $attributes = $this->prepareAttributes($attributes);

        empty($attributes)?:$this->setAttributes($attributes);
        $this->setDefaults();
        $this->initConstants();
        $this->initialized = true;
    }

    /**
     * Prepare attributes array
     *
     * @param $attributes
     * @return array
     */
    private function prepareAttributes($attributes): array
    {
        if (is_string($attributes)) {
            $attributes = json_decode($attributes, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $attributes = [];
            }
        }

        if (is_null($attributes)) {
            $attributes = [];
        }

        if (!is_array($attributes)) {
            $attributes = [];
        }

        return $attributes;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $field)
    {
        return $this->attributes[$field] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getSafeAttributes(): array
    {
        return array_filter($this->attributes, function($key) {
            return !in_array($key, $this->hidden());
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @inheritDoc
     */
    public function setAttribute(string $field, $value): void
    {
        if (!$this->hasField($field) && !$this->dynamic) {
            return;
        }

        // If attribute is constant, and DTO has already been initialized, then return.
        // Constant attributes initialize only one time
        if ($this->isConstant($field) && $this->initialized) {
            return;
        }

        if (is_array($value)) {
            // Find from nestedMap
            $class = $this->nestedMap[$field] ?? null;
            if ($class) {
                $this->attributes[$field] = new $class($value);

                return;
            }

            // Find from factories
            $factory = $this->factories()[$field] ?? null;
            if ($factory && $factory instanceof DTOFactory) {
                $isMultiple = array_filter($value, function($item) {
                        return is_array($item) || $item instanceof DTO;
                    }) === $value;

                if ($isMultiple) {
                    $data = [];
                    foreach ($value as $nested) {
                        if ($nested instanceof DTO) {
                            $data[] = $nested;
                        } else {
                            $data[] = $factory->make($nested);
                        }
                    }
                    $this->attributes[$field] = $data;
                } else {
                    $this->attributes[$field] = $factory->make($value);
                }

                return;
            }
        }

        $this->attributes[$field] = DtoPropertyCaster::cast(get_class($this), $field, $value);
    }

    /**
     * @inheritDoc
     */
    public function setAttributes(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value ?? null);
        }
    }

    /**
     * @inheritDoc
     */
    public function setDefaults(bool $force = false): void
    {
        foreach ($this->defaults() as $attribute => $value) {
            if (is_null($this->getAttribute($attribute)) || $force) {
                $this->setAttribute($attribute, $value);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function hidden(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function initConstants(): void
    {
        foreach ($this->constants() as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function constants(): array
    {
        return [];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isConstant(string $key): bool
    {
        return isset($this->constants()[$key]);
    }

    /**
     * @inheritDoc
     */
    public function factories(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function hasField(string $field): bool
    {
        return in_array($field, $this->fields());
    }

    /**
     * Magic setter for auto calling methods instead of just setting in attributes array
     *
     * @param $name
     * @param $value
     * @return mixed|void
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->setAttribute($name, call_user_func_array([$this, $method], $value));
        }
        return $this->setAttribute($name, $value);
    }

    /**
     * Magic getter for auto calling methods instead of just getting from attributes array
     *
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $name);
        }
        return $this->getAttribute($name);
    }

    /**
     * Magic isset for checking in attributes array
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->attributes[$name]) || isset($this->$name);
    }

    /**
     * Convert DTO to string
     */
    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->getSafeAttributes();
    }
}
