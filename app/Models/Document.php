<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [

        'document_type_id',
        'user_id',
        'user_info_id',
        'ref_id',
        'path',
        'pushed'
    ];



    public function user(){
        return $this->belongsTo(User::class);
    }

    public function userInfo(){
        return $this->belongsTo(UserInfo::class);
    }

    public function documentType(){
        return $this->belongsTo(DocumentType::class);
    }

}
