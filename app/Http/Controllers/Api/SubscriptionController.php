<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(): JsonResponse
    {
        $subscriptions = Subscription::query()
            ->with(['customer', 'service'])
            ->latest()
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Subscriptions retrieved successfully",
            "data" => $subscriptions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "customer_id" => ["required", "exists:customers,id"],
            "service_id" => ["required", "exists:services,id"],
            "start_date" => ["nullable", "date"],
            "end_date" => ["nullable", "date"],
            "status" => [
                "required",
                "in:active,inactive,trial,isolir,dismantle"
            ],
        ]);

        $subscription = Subscription::query()->create($data);

        return response()->json([
            "success" => true,
            "message" => "Subscription created successfully",
            "data" => $subscription,
        ], 201);
    }

    public function show(int $subscription): JsonResponse
    {
        $subscription = Subscription::query()
            ->with(['customer', 'service'])
            ->find($subscription);

        if (!$subscription) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
                "errors" => [],
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Subscription retrieved successfully",
            "data" => $subscription,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        // 1. Cari data subscription berdasarkan ID
        $subscription = Subscription::query()->find($id);

        // 2. Jika data tidak ditemukan, kirim error 404
        if (!$subscription) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
                "errors" => [],
            ], 404);
        }

        // 3. Validasi data yang mau diubah (semuanya dibuat 'sometimes' agar opsional saat dikirim sebagian)
        $data = $request->validate([
            "customer_id" => ["sometimes", "required", "exists:customers,id"],
            "service_id" => ["sometimes", "required", "exists:services,id"],
            "start_date" => ["nullable", "date"],
            "end_date" => ["nullable", "date"],
            "status" => [
                "sometimes",
                "required",
                "in:active,inactive,trial,isolir,dismantle"
            ],
        ]);

        // 4. Update data ke database XAMPP
        $subscription->update($data);

        // 5. Kembalikan response sukses bersama data terbaru
        return response()->json([
            "success" => true,
            "message" => "Subscription updated successfully",
            "data" => $subscription->load(['customer', 'service']),
        ]);
    }

    public function destroy(int $id): JsonResponse
{
    $service = Service::find($id);

    if (!$service) {
        return response()->json([
            "success" => false,
            "message" => "Service not found"
        ], 404);
    }

    // VALIDASI MODUL PART 2: Service yang sudah memiliki Subscription tidak boleh dihapus.
    // Asumsi nama relasi di model Service kamu adalah 'subscriptions' atau 'subscription'
    if ($service->subscriptions()->exists()) {
        return response()->json([
            "success" => false,
            "message" => "Validation failed",
            "errors" => [
                "service" => ["Service yang sudah memiliki Subscription tidak boleh dihapus."]
            ]
        ], 422);
    }

    $service->delete();

    return response()->json([
        "success" => true,
        "message" => "Service deleted successfully"
    ]);
}

    

}


