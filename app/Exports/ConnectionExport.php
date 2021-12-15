<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConnectionExport implements FromCollection, WithMapping, WithHeadings
{

    protected $resurse;
    protected $search;

    public function __construct($recurse, $search)
    {
        $this->resurse = $recurse;
        $this->search = $search;

    }

    public function headings(): array
    {
        return [
            'Id',
            'First name',
            'Last name',
            'Occupation',
            'Link',
            'Image',
            'Search',
        ];
    }

    public function collection()
    {
        return $this->resurse;
    }


    /**
     * @param mixed $connection
     * @return array
     */
    public function map($connection): array
    {
        return [
            $connection->id,
            $connection->firstName,
            $connection->lastName,
            $connection->occupation,
            'https://www.linkedin.com/in/' . $connection->entityUrn,
            $connection->image,
            $this->search,
        ];
    }
}
