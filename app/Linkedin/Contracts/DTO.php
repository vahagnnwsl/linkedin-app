<?php


namespace App\Linkedin\Contracts;

/**
 * Main DTO Contract
 */
interface DTO
{
    /**
     * Get dto available fields list
     *
     * @return array
     */
    public function fields():  array;

    /**
     * Check if DTO has field with name $field
     *
     * @param string $field
     * @return bool
     */
    public function hasField(string $field): bool;

    /**
     * Get field value
     *
     * @param string $field
     * @return mixed
     */
    public function getAttribute(string $field);

    /**
     * Returns all fields' values
     *
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Returns all fields' values except hidden ones
     *
     * @return array
     */
    public function getSafeAttributes(): array;

    /**
     * Set single field value
     *
     * @param string $field
     * @param $value
     */
    public function setAttribute(string $field, $value): void;

    /**
     * Set multiple fields' values
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void;

    /**
     * Reset/Set DTO attributes to their defaults
     *
     * @param bool $force
     */
    public function setDefaults(bool $force = false): void;

    /**
     * Get default attribute values
     *
     * @return array
     */
    public function defaults(): array;

    /**
     * Get hidden attributes' keys
     *
     * @return array
     */
    public function hidden(): array;

    /**
     * Set DTO constant attributes' initial values
     */
    public function initConstants(): void;

    /**
     * Get list of constant attribute keys with initial values
     *
     * @return array
     */
    public function constants(): array;

    /**
     * Check if attribute is constant
     *
     * @param string $key
     * @return bool
     */
    public function isConstant(string $key): bool;

    /**
     * Return array of factories for attributes which are DTOs
     *
     * @return array
     */
    public function factories(): array;
}
