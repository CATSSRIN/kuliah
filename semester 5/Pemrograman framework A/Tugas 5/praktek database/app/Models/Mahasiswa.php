<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswas'; // opsional jika nama plural standar
    protected $fillable = ['npm','nama','prodi'];
}
