<?php

namespace App\Imports;
use App\Models\User;
use App\Models\Peserta;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PesertaImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $user = User::create([
            'email' => $row['email'],
        ]);

        return new Peserta([
            'user_id' => $user->id, 
            'nama' => $row['nama'],
            'noHP' => $row['noHP'],
            'alamat' => $row['alamat'],
        ]);
    }
}
