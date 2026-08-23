<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\RepairOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RepairOrderController extends Controller
{
    /**
     * Listado general de órdenes de reparación con filtros.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = RepairOrder::with(['client', 'technician'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_code', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('imei_serial', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($clientQ) use ($search) {
                      $clientQ->where('name', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $repairOrders = $query->paginate(15)->withQueryString();

        return view('repairs.index', compact('repairOrders', 'search', 'status'));
    }

    /**
     * Formulario para recibir un nuevo dispositivo.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $technicians = User::whereIn('role', ['admin', 'tecnico'])->get();

        // Generar código aleatorio único: REP-XXXXX
        do {
            $trackingCode = 'REP-' . strtoupper(Str::random(5));
        } while (RepairOrder::where('tracking_code', $trackingCode)->exists());

        return view('repairs.create', compact('clients', 'technicians', 'trackingCode'));
    }

    /**
     * Guardar la nueva orden de servicio técnico.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tracking_code' => 'required|string|unique:repair_orders,tracking_code',
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'nullable|string|max:150',
            'client_phone' => 'nullable|string|max:30',
            'client_ci' => 'nullable|string|max:30',
            'technician_id' => 'nullable|exists:users,id',
            'device_type' => 'required|string|max:100',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'imei_serial' => 'nullable|string|max:100',
            'reported_fault' => 'required|string',
            'diagnosis' => 'nullable|string',
            'status' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'advance_payment' => 'required|numeric|min:0|lte:estimated_cost',
            'promised_date' => 'nullable|date',
        ]);

        $clientId = $validated['client_id'] ?? null;

        if (!$clientId && !empty($validated['client_name'])) {
            $newClient = Client::create([
                'name' => $validated['client_name'],
                'phone' => $validated['client_phone'] ?? null,
                'ci_nit' => $validated['client_ci'] ?? null,
            ]);
            $clientId = $newClient->id;
        }

        if (!$clientId) {
            return back()->withInput()->with('error', 'Debes seleccionar un cliente registrado o ingresar el nombre de un cliente.');
        }

        $validated['client_id'] = $clientId;

        $repairOrder = RepairOrder::create($validated);

        return redirect()->route('repairs.show', $repairOrder)->with('success', 'Orden de reparación creada con código: ' . $repairOrder->tracking_code);
    }

    /**
     * Detalle y hoja de recepción del servicio técnico.
     */
    public function show(RepairOrder $repair)
    {
        $repair->load(['client', 'technician']);
        return view('repairs.show', compact('repair'));
    }

    /**
     * Formulario de edición y actualización de estado / diagnóstico.
     */
    public function edit(RepairOrder $repair)
    {
        $clients = Client::orderBy('name')->get();
        $technicians = User::whereIn('role', ['admin', 'tecnico'])->get();

        return view('repairs.edit', compact('repair', 'clients', 'technicians'));
    }

    /**
     * Actualizar orden de servicio.
     */
    public function update(Request $request, RepairOrder $repair)
    {
        $validated = $request->validate([
            'technician_id' => 'nullable|exists:users,id',
            'device_type' => 'required|string|max:100',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'imei_serial' => 'nullable|string|max:100',
            'reported_fault' => 'required|string',
            'diagnosis' => 'nullable|string',
            'status' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'advance_payment' => 'required|numeric|min:0',
            'promised_date' => 'nullable|date',
        ]);

        $repair->update($validated);

        return redirect()->route('repairs.show', $repair)->with('success', 'Orden de reparación actualizada correctamente.');
    }
}
