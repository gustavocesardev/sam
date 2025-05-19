<?php

namespace App\Domain\Model\Abstract;

use Illuminate\Database\Eloquent\Model;

abstract class PublicacaoKeywordAbstract extends Model
{
    public $timestamps = false;
    public const FIELDS = [
        'id_publicacao',
        'keyword',
        'frequencia',
    ];
    protected $fillable = self::FIELDS;
}
