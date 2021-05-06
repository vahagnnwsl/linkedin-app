<?php
namespace App\Linkedin\Contracts;


/**
 * Main DTOFactory Contract
 */
interface DTOFactory
{
    /**
     * Factory main method to create DTO based on $data array
     *
     * @param array $data
     * @return DTO|null
     */
    public function make(array $data): ?DTO;
}
