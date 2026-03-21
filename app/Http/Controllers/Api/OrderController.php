<?php

namespace App\Http\Controllers\Api;

use App\Enums\DeliveryStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with(['orderItems.product', 'payment', 'delivery'])
            ->get();

        return response()->json(OrderResource::collection($orders));
    }

    public function show(Request $request, Order $order)
    {
        $user = $request->user();

        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->load(['orderItems.product', 'payment', 'delivery']);

        return response()->json(new OrderResource($order));
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $order = $user->orders()->create([
            'total_price' => 0,
            'total_quantity' => 0,
            'status' => OrderStatus::Pending,
            'comment' => $data['comment'] ?? null,
        ]);

        $totalPrice = 0;
        $totalQuantity = 0;

        $items = collect($data['items'])
            ->groupBy('product_id')
            ->map(fn($group) => [
                'product_id' => $group[0]['product_id'],
                'quantity' => array_sum(array_column($group->toArray(), 'quantity')),
            ])
            ->values();

        $productIds = $items->pluck('product_id')->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($items as $item) {
            $product = $products[$item['product_id']];

            $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);

            $totalPrice += $product->price * $item['quantity'];
            $totalQuantity += $item['quantity'];
        }

        $order->update([
            'total_price' => $totalPrice,
            'total_quantity' => $totalQuantity,
        ]);

        $order->payment()->create([
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
        ]);

        $order->delivery()->create([
            'status' => DeliveryStatus::Pending,
        ]);

        $order->load(['orderItems.product', 'payment', 'delivery']);

        return response()->json(new OrderResource($order), 201);
    }

    public function cancel(Request $request, Order $order)
    {
        $user = $request->user();

        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status === OrderStatus::Cancelled) {
            return response()->json(['message' => 'Order already cancelled'], 400);
        }

        $order->markCancelled();

        if ($order->delivery) {
            $order->delivery->markCancelled();
        }

        return response()->json(['message' => 'Order cancelled']);
    }
}
