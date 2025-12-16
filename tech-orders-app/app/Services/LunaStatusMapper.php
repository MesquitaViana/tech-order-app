<?php

namespace App\Services;

class LunaStatusMapper
{
    /**
     * Retorna:
     * - status (interno)
     * - gateway_status (paid/pending/canceled/refunded/chargeback/etc)
     */
    public static function map(?string $eventName, ?string $gatewayStatus = null): array
    {
        $event = strtolower(trim((string) $eventName));
        $gw    = strtolower(trim((string) $gatewayStatus));

        // 1) Se o gateway status veio, ele é a melhor fonte
        if ($gw !== '') {
            return match ($gw) {
                'paid', 'approved'       => ['status' => 'em_separacao', 'gateway_status' => 'paid'],
                'pending', 'waiting'     => ['status' => 'novo',         'gateway_status' => 'pending'],
                'canceled', 'cancelled'  => ['status' => 'cancelado',    'gateway_status' => 'canceled'],
                'refunded'               => ['status' => 'cancelado',    'gateway_status' => 'refunded'],
                'chargeback'             => ['status' => 'cancelado',    'gateway_status' => 'chargeback'],
                default                  => ['status' => 'novo',         'gateway_status' => $gw],
            };
        }

        // 2) Fallback por nome do evento (cobre os nomes que você já viu)
        return match ($event) {
            // modelos antigos
            'event_sale_pending'  => ['status' => 'novo',         'gateway_status' => 'pending'],
            'event_sale_paid'     => ['status' => 'em_separacao', 'gateway_status' => 'paid'],
            'event_sale_canceled' => ['status' => 'cancelado',    'gateway_status' => 'canceled'],
            'event_sale_refunded' => ['status' => 'cancelado',    'gateway_status' => 'refunded'],

            // eventos reais da Luna (ex: sale_approved)
            'sale_pending', 'sale_created'   => ['status' => 'novo',         'gateway_status' => 'pending'],
            'sale_approved', 'sale_paid'     => ['status' => 'em_separacao', 'gateway_status' => 'paid'],
            'sale_canceled', 'sale_cancelled'=> ['status' => 'cancelado',    'gateway_status' => 'canceled'],
            'sale_refunded'                => ['status' => 'cancelado',    'gateway_status' => 'refunded'],
            'sale_chargeback'              => ['status' => 'cancelado',    'gateway_status' => 'chargeback'],

            default => ['status' => 'novo', 'gateway_status' => 'pending'],
        };
    }
}
