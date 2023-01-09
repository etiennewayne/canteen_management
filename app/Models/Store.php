<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';
    protected $primaryKey = 'store_id';


    protected $fillable = ['user_id', 'store', 'owner', 'contact_no', 'is_online'];

    public function owner_info(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
}
