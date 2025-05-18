<?php

namespace App\Domain\Model\Abstract;

use Illuminate\Database\Eloquent\Model;

abstract class PublicacaoVisualizacaoAbstract extends Model
{
    public const FIELDS = [
        'id_publicacao',
    ];

    protected $fillable = self::FIELDS;
}
