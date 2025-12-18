<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderEmailLog;
use App\Services\Brevo\BrevoMailer;

class OrderEmailService
{
    public function __construct(private BrevoMailer $mailer) {}

    private function once(int $orderId, string $type, ?string $fingerprint = null): bool
    {
        // retorna true se JÁ EXISTE (ou seja, não deve enviar)
        return OrderEmailLog::where('order_id', $orderId)
            ->where('type', $type)
            ->where('fingerprint', $fingerprint)
            ->exists();
    }

    private function mark(int $orderId, string $type, ?string $fingerprint = null): void
    {
        OrderEmailLog::create([
            'order_id'     => $orderId,
            'type'         => $type,
            'fingerprint'  => $fingerprint,
        ]);
    }

    public function pedidoCriado(Order $order): void
    {
        $customer = $order->customer;
        if (! $customer?->email) return;

        if ($this->once($order->id, 'pedido_criado')) return;

        $this->mailer->sendView(
            $customer->email,
            $customer->name ?? 'Cliente',
            "Pedido criado #{$order->external_id}",
            'emails.orders.pedido-criado',
            ['order' => $order, 'customer' => $customer]
        );

        $this->mark($order->id, 'pedido_criado');
    }

    public function aguardandoPagamento(Order $order): void
    {
        $customer = $order->customer;
        if (! $customer?->email) return;

        if ($this->once($order->id, 'aguardando_pagamento')) return;

        $this->mailer->sendView(
            $customer->email,
            $customer->name ?? 'Cliente',
            "Aguardando pagamento #{$order->external_id}",
            'emails.orders.aguardando-pagamento',
            ['order' => $order, 'customer' => $customer]
        );

        $this->mark($order->id, 'aguardando_pagamento');
    }

    public function pagamentoAprovado(Order $order): void
    {
        $customer = $order->customer;
        if (! $customer?->email) return;

        if ($this->once($order->id, 'pagamento_aprovado')) return;

        $this->mailer->sendView(
            $customer->email,
            $customer->name ?? 'Cliente',
            "Pagamento aprovado #{$order->external_id}",
            'emails.orders.pagamento-aprovado',
            ['order' => $order, 'customer' => $customer]
        );

        $this->mark($order->id, 'pagamento_aprovado');
    }

    public function statusAtualizado(Order $order, string $oldStatus, string $newStatus): void
    {
        $customer = $order->customer;
        if (! $customer?->email) return;

        // fingerprint evita duplicar a mesma transição
        $fp = $oldStatus . '->' . $newStatus;
        if ($this->once($order->id, 'status_atualizado', $fp)) return;

        $this->mailer->sendView(
            $customer->email,
            $customer->name ?? 'Cliente',
            "Atualização do pedido #{$order->external_id}",
            'emails.orders.status-atualizado',
            ['order' => $order, 'customer' => $customer, 'oldStatus' => $oldStatus, 'newStatus' => $newStatus]
        );

        $this->mark($order->id, 'status_atualizado', $fp);
    }

    public function pedidoEmTransporte(Order $order, string $trackingCode): void
    {
        $customer = $order->customer;
        if (! $customer?->email) return;

        // fingerprint = trackingCode (não reenvia se repetir o mesmo código)
        if ($this->once($order->id, 'em_transporte', $trackingCode)) return;

        $this->mailer->sendView(
            $customer->email,
            $customer->name ?? 'Cliente',
            "Pedido em transporte #{$order->external_id}",
            'emails.orders.em-transporte',
            ['order' => $order, 'customer' => $customer, 'trackingCode' => $trackingCode]
        );

        $this->mark($order->id, 'em_transporte', $trackingCode);
    }

    public function acessoConta(Order $order): void
    {
        $customer = $order->customer;
        if (! $customer?->email) return;

        if ($this->once($order->id, 'acesso_conta')) return;

        $this->mailer->sendView(
            $customer->email,
            $customer->name ?? 'Cliente',
            "Acesso à sua conta — Tech Market Brasil",
            'emails.account.acesso',
            ['order' => $order, 'customer' => $customer]
        );

        $this->mark($order->id, 'acesso_conta');
    }
}
