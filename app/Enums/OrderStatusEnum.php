<?php

namespace App\Enums;

class OrderStatusEnum
{
    // const WAITING_PAYMENT = 0;
    // const SUCCESS = 1;
    // const CANCELED = 2;
    // const EXPIRED = 3;
    // const FINISH = 4;
    // const PROCESS = 5;

    const MENUNGGU_PEMBAYARAN = 0;
    const TERBAYAR = 1;
    const DIBATALKAN = 2;
    const TELAH_LEWAT = 3;
    const DI_PROSES = 4;
    const SELESAI = 5;

    public static function getAllStatus()
    {
        return [
            [
                'id' => static::MENUNGGU_PEMBAYARAN,
                'name' => 'Menunggu Pembayaran',
            ],
            [
                'id' => static::TERBAYAR,
                'name' => 'Terbayar',
            ], [
                'id' => static::DIBATALKAN,
                'name' => 'Dibatalkan',
            ], [
                'id' => static::TELAH_LEWAT,
                'name' => 'Telah Lewat',
            ], [
                'id' => static::DI_PROSES,
                'name' => 'Di Proses',
            ], [
                'id' => static::SELESAI,
                'name' => 'Selesai',
            ],
        ];
    }
}
