<?php

namespace App\Domain\Model\Abstract;

use Illuminate\Database\Eloquent\Model;

abstract class PublicacaoReacaoAbstract extends Model
{
    public const FIELDS = [
        'id_publicacao',
        'situacao'
    ];

    protected $fillable = self::FIELDS;

    public function inativar(): bool
    {
        $this->situacao = 'I';
        return $this->save();
    }

    public function ativar(): bool
    {
        $this->situacao = 'A';
        return $this->save();
    }
}
