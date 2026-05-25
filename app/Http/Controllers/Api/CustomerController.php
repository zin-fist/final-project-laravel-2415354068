<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->query("status");

        $query = Customer::query();

        if ($status !== null) {

            if (!in_array($status, ["active", "inactive"], true)) {
                return response()->json([
                    "success" => false,
                    "message" => "Validation failed",
                    "errors" => [
                        "status" => ["The selected status is invalid."]
                    ],
                ], 422);
            }

            // FIX STRING
            $query->where("status", $status);
        }

        $customers = $query->latest()->get();

        return response()->json([
            "success" => true,
            "message" => "Customers retrieved successfully",
            "data" => $customers,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "customer_id" => ["required", "string", "unique:customers,customer_id"],
            "name" => ["required", "string"],
            "email" => ["nullable", "email", "unique:customers,email"],
            "phone" => ["nullable", "string"],
            "address" => ["nullable", "string"],

            // FIX STRING
            "status" => ["nullable", "in:active,inactive"],
        ]);

        // DEFAULT STATUS
        $data["status"] = $data["status"] ?? "active";

        $customer = Customer::query()->create($data);

        return response()->json([
            "success" => true,
            "message" => "Customer created successfully",
            "data" => $customer,
        ], 201);
    }

    public function update(Request $request, int $customer): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                "success" => false,
                "message" => "Customer not found",
            ], 404);
        }

        $data = $request->validate([
            "customer_id" => ["sometimes", "string", "unique:customers,customer_id," . $customer->id],
            "name" => ["sometimes", "string"],
            "email" => ["nullable", "email", "unique:customers,email," . $customer->id],
            "phone" => ["nullable", "string"],
            "address" => ["nullable", "string"],

            // FIX STRING
            "status" => ["nullable", "in:active,inactive"],
        ]);

        $customer->update($data);

        return response()->json([
            "success" => true,
            "message" => "Customer updated successfully",
            "data" => $customer,
        ]);
    }

    public function destroy(int $customer): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                "success" => false,
                "message" => "Customer not found"
            ], 404);
        }

        if ($customer->subscriptions()->exists()) {
            return response()->json([
                "success" => false,
                "message" => "Validation failed",
                "errors" => [
                    "customer" => ["Customer yang sudah memiliki Subscription tidak boleh dihapus."]
                ]
            ], 422); 
        }

        $customer->delete();

        return response()->json([
            "success" => true,
            "message" => "Customer deleted successfully"
        ]);
    }
}