<?php

namespace App\Enums;

enum SubjectRelationEnum: string
{
    case MAIN = 'main';
    case CO = 'co';
    case RIVAL = 'rival';


    public function persian(): string
    {
        return match ( $this ) {
            self::MAIN  => 'اصلی',
            self::CO    => 'همکار',
            self::RIVAL => 'رقیب',
        };
    }
}
