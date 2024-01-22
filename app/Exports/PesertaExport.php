<?php

namespace App\Exports;
use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\FromCollection;

class PesertaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Peserta::join('users','users.id','=','peserta.users_id')
                        ->select('users.id','nama','username','email','password_awal')->get();
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['ID','Nama','Username', 'Email','Password'];
    }
}
