<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $items = collect($request->validated('items'))
            ->map(fn (array $item) => [
                'book' => Book::where('slug', $item['slug'])->firstOrFail(),
                'quantity' => $item['quantity'],
            ]);

        $subtotal = $items->sum(fn ($item) => $item['book']->price * $item['quantity']);
        $shipping = $subtotal >= 999 ? 0 : 99;

        $order = DB::transaction(function () use ($request, $items, $subtotal, $shipping) {
            $order = Order::create([
                'order_number' => 'RTS-'.strtoupper(Str::random(8)),
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'phone' => $request->validated('phone'),
                'company' => $request->validated('company'),
                'address' => $request->validated('address'),
                'city' => $request->validated('city'),
                'state' => $request->validated('state'),
                'pin_code' => $request->validated('pin_code'),
                'payment_method' => $request->validated('payment_method') ?? 'mock',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $subtotal + $shipping,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'book_id' => $item['book']->id,
                    'title' => $item['book']->title,
                    'price' => $item['book']->price,
                    'quantity' => $item['quantity'],
                ]);
            }

            return $order;
        });

        return response()->json([
            'message' => 'Order received.',
            'orderNumber' => $order->order_number,
            'total' => $order->total,
        ], 201);
    }
}
