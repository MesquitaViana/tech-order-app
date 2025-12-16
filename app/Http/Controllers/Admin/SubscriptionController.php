<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));

        $query = Subscription::with('customer')->orderByDesc('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('flavor', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $subscriptions = $query->paginate(20)->appends($request->query());

        return view('admin.subscriptions.index', [
            'subscriptions' => $subscriptions,
            'search'        => $search,
        ]);
    }

    public function create(Request $request)
    {
        $customerId = $request->input('customer_id');
        $customer = null;

        if ($customerId) {
            $customer = Customer::find($customerId);
        }

        return view('admin.subscriptions.create', [
            'customer' => $customer,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'        => ['required', 'exists:customers,id'],
            'product_name'       => ['required', 'string', 'max:255'],
            'flavor'             => ['nullable', 'string', 'max:255'],
            'quantity'           => ['nullable', 'integer', 'min:1'],
            'frequency'          => ['nullable', 'string', 'max:100'],
            'status'             => ['required', 'string', 'max:50'],
            'next_delivery_date' => ['nullable', 'date'],
            'notes'              => ['nullable', 'string'],
        ]);

        $data['quantity'] = $data['quantity'] ?? 1;

        $subscription = Subscription::create($data);

        return redirect()
            ->route('admin.subscriptions.edit', $subscription->id)
            ->with('status_message', 'Assinatura criada com sucesso.');
    }

            public function edit($id)
            {
                $subscription = Subscription::with('customer')->findOrFail($id);

                $customer = $subscription->customer;

                $lastOrder = $customer
                    ? $customer->orders()->latest()->first()
                    : null;

                return view('admin.subscriptions.edit', [
                    'subscription' => $subscription,
                    'customer'     => $customer,
                    'lastOrder'    => $lastOrder,
                ]);
            }


    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $data = $request->validate([
            'product_name'       => ['required', 'string', 'max:255'],
            'flavor'             => ['nullable', 'string', 'max:255'],
            'quantity'           => ['nullable', 'integer', 'min:1'],
            'frequency'          => ['nullable', 'string', 'max:100'],
            'status'             => ['required', 'string', 'max:50'],
            'next_delivery_date' => ['nullable', 'date'],
            'notes'              => ['nullable', 'string'],
        ]);

        $data['quantity'] = $data['quantity'] ?? 1;

        $subscription->update($data);

        return redirect()
            ->route('admin.subscriptions.edit', $subscription->id)
            ->with('status_message', 'Assinatura atualizada com sucesso.');
    }
}
