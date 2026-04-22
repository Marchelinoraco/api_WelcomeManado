<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transportation;
use App\Models\TransportationBooking;
use Illuminate\Http\Request;

class TransportationBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = TransportationBooking::query()->with('transportation');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($date = $request->get('date')) {
            $query->whereDate('booking_date', $date);
        }

        $perPage = $request->integer('per_page', 15);
        $items = $query->orderBy('created_at', 'desc')->paginate($perPage);
        return response()->json(['success' => true, 'data' => $items]);
    }

    public function show($id)
    {
        $item = TransportationBooking::with('transportation')->find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transportation_id' => 'required|integer|exists:transportations,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'booking_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $transportation = Transportation::find((int) $request->transportation_id);
        if (! $transportation) {
            return response()->json(['success' => false, 'message' => 'Transportation not found'], 404);
        }

        $item = TransportationBooking::create([
            'transportation_id' => $transportation->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'booking_date' => $request->booking_date,
            'status' => 'new',
            'notes' => $request->notes,
        ]);

        return response()->json(['success' => true, 'data' => $item->load('transportation')], 201);
    }

    public function update(Request $request, $id)
    {
        $item = TransportationBooking::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        $request->validate([
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $item->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $item->notes,
        ]);

        return response()->json(['success' => true, 'data' => $item->load('transportation')]);
    }

    public function destroy($id)
    {
        $item = TransportationBooking::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        $item->delete();
        return response()->json(['success' => true]);
    }
}

