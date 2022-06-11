<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyReader extends Model
{
    use HasFactory;
    public $table = 'verify_readers';
    protected $fillable=[
        'reader_id',
        'token'
    ];
    public function user(){
        return $this->belongsTo(Reader::class);
    }
}
