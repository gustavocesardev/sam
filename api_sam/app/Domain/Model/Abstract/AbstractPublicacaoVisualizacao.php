<?php

namespace App\Domain\Model\Abstract;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractPublicacaoVisualizacao extends Model
{
    public const FIELDS = [
        'id_publicacao',
    ];

    protected $fillable = self::FIELDS;
}
