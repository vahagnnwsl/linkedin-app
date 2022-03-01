<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConversationExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    protected $search;

    public function __construct($recurse)
    {
        $this->resurse = $recurse;
    }

    public function headings(): array
    {
        return [
            'account',
            'connection',
            'hash',
            'status',
        ];
    }

    public function collection()
    {
        return $this->resurse;
    }



    public function map($conversation): array
    {
        return [
            $conversation['account'],
            $conversation['connection'],
            route('moderators.conversation',$conversation['hash']),
            $conversation['status'],
        ];
    }
}
