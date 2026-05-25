<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->query("status");

        $query = Service::query();

        if ($status !== null) {

            if (!in_array($status, ["active", "inactive"], true)) {
                return response()->json([
                    "success" => false,
                    "message" => "Validation failed",
                ], 422);
            }

            // FIX STRING
            $query->where("status", $status);
        }

        $services = $query->latest()->get();

        return response()->json([
            "success" => true,
            "message" => "Services retrieved successfully",
            "data" => $services,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "name" => ["required", "string"],
            "price" => ["required", "integer", "min:0"],
            "description" => ["nullable", "string"],

            // FIX STRING
            "status" => ["nullable", "in:active,inactive"],
        ]);

        // DEFAULT STATUS
        $data["status"] = $data["status"] ?? "active";

        $service = Service::query()->create($data);

        return response()->json([
            "success" => true,
            "message" => "Service created successfully",
            "data" => $service,
        ], 201);
    }

    public function update(Request $request, int $service): JsonResponse
    {
        $service = Service::query()->find($service);

        if (!$service) {
            return response()->json([
                "success" => false,
                "message" => "Service not found",
            ], 404);
        }

        $data = $request->validate([
            "name" => ["sometimes", "string"],
            "price" => ["sometimes", "integer", "min:0"],
            "description" => ["nullable", "string"],

            // FIX STRING
            "status" => ["nullable", "in:active,inactive"],
        ]);

        $service->update($data);

        return response()->json([
            "success" => true,
            "message" => "Service updated successfully",
            "data" => $service,
        ]);
    }

    public function destroy(int $service): JsonResponse
    {
        $service = Service::query()->find($service);

        if (!$service) {
            return response()->json([
                "success" => false,
                "message" => "Service not found"
            ], 404);
        }

        // VALIDASI MODUL PART 2: Cek apakah service ini sedang dipakai di subscription
        // Pastikan di model Service kamu sudah ada fungsi relasi bernama 'subscriptions'
        if ($service->subscriptions()->exists()) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => [
                    "service" => ["Service yang sudah memiliki Subscription tidak boleh dihapus."]
                ]
            ], 422); // Status 422 biar langsung ditangkap sebagai error oleh JavaScript
        }

        $service->delete();

        return response()->json([
            "success" => true,
            "message" => "Service deleted successfully"
        ]);
    }
}