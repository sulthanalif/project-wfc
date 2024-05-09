<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgentExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $agents = User::role('agent')->with('agentProfile')->get();
        $datas = $agents->map(function ($agent) {
            return [
                'id' => $agent->id,
                'name' => $agent->agentProfile->name,
                'email' => $agent->email,
                'phone_number' => $agent->agentProfile->phone_number,
                'rt' => $agent->agentProfile->rt,
                'rw' => $agent->agentProfile->rw,
                'village' => $agent->agentProfile->village,
                'district' => $agent->agentProfile->district,
                'regency' => $agent->agentProfile->regency,
                'province' => $agent->agentProfile->province,
                'active' => $agent->active == 1 ? 'aktif' : 'tidak aktif'
            ];
        });

        return $datas;
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone_number',
            'rt',
            'rw',
            'village',
            'district',
            'regency',
            'province',
            'active'
        ];
    }
}
