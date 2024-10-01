<?php

namespace App\Enums;

enum PhysicalActivities: string
{
    case WALKING = 'Walking';
    case RUNNING = 'Running';
    case CYCLING = 'Cycling';
    case SWIMMING = 'Swimming';
    case YOGA = 'Yoga';
    case ZUMBA = 'Zumba';
    case AEROBICS = 'Aerobics';
    case DANCING = 'Dancing';

    public static function getActivities(): array
    {
        return [
            self::WALKING,
            self::RUNNING,
            self::CYCLING,
            self::SWIMMING,
            self::YOGA,
            self::ZUMBA,
            self::AEROBICS,
            self::DANCING,
        ];
    }

    public function getHTMLbadge(): string
    {
        return match ($this) {
            self::WALKING => '<span class="badge badge-primary">Walking</span>',
            self::RUNNING => '<span class="badge badge-secondary">Running</span>',
            self::CYCLING => '<span class="badge badge-success">Cycling</span>',
            self::SWIMMING => '<span class="badge badge-danger">Swimming</span>',
            self::YOGA => '<span class="badge badge-warning">Yoga</span>',
            self::ZUMBA => '<span class="badge badge-info">Zumba</span>',
            self::AEROBICS => '<span class="badge badge-light">Aerobics</span>',
            self::DANCING => '<span class="badge badge-dark">Dancing</span>',
        };
    }
}
