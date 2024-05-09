<?php

namespace App\Exports;

use App\Models\SubAgent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubAgentExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $subagents = SubAgent::all();

        $datas = $subagents->map(function ($subagent) {
            return [
                'id' => $subagent->id,
                'agent_id' => $subagent->agent_id,
                'name' => $subagent->name,
                'address' => $subagent->address,
                'phone_number' => $subagent->phone_number
            ];
        });

        return $datas;
    }

    public function headings(): array
    {
        return [
            'id',
            'agent_id',
            'name',
            'address',
            'phone_number',
        ];
    }
}
