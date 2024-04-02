<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Company extends Model
{
    use HasFactory;
    protected $fillable=[
        'razon_social',
        'ruc',
        'direccion',
        'logo_path',
        'sol_user',
        'sol_pass',
        'cert_path',
        'client_id',
        'client_secret',
        'production',
        'user_id'
    ];

    //relacion uno a muchos
    public function user(){
        return $this->belongsTo(User::class);
    }
}
