<?php

namespace App\Imports;

use App\Models\User;
use App\Models\AgentProfile;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AgentImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $agent = new User([
            'email' => $row['email'],
            'password' => Hash::make('password'),
            'active' => 0
        ]);

        $agent->save();

        $agent->assignRole('agent');

        return new AgentProfile([
            'user_id' => $agent->id,
            'name'=> $row['name'],
            'phone_number' => $row['phone_number'],
            'address' => $row['address'],
            'rt'=> $row['rt'],
            'rw'=> $row['rw'],
            'village'=> $row['village'],
            'district'=> $row['district'],
            'regency'=> $row['regency'],
            'province'=> $row['province'],
        ]);
    }
}
