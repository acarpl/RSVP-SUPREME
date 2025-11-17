<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Display a listing of the vouchers.
     */
    public function index()
    {
        // Menampilkan voucher milik partner yang login
        $vouchers = Voucher::where('partner_id', Auth::id())->get();

        return view('vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new voucher.
     */
    public function create()
    {
        return view('vouchers.create');
    }

    /**
     * Store a newly created voucher in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'          => 'required|unique:vouchers,code',
            'description'   => 'nullable|string',
            'type'          => 'required|in:percentage,fixed',
            'value'         => 'required|integer',
            'min_amount'    => 'nullable|integer',
            'max_discount'  => 'nullable|integer',
            'quota'         => 'required|integer|min:1',
            'expires_at'    => 'nullable|date',
        ]);

        Voucher::create([
            'partner_id'    => Auth::id(),
            'code'          => strtoupper($request->code),
            'description'   => $request->description,
            'type'          => $request->type,
            'value'         => $request->value,
            'min_amount'    => $request->min_amount ?? 0,
            'max_discount'  => $request->max_discount,
            'quota'         => $request->quota,
            'expires_at'    => $request->expires_at,
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dibuat!');
    }

    /**
     * Show the form for editing the voucher.
     */
    public function edit($id)
    {
        $voucher = Voucher::where('partner_id', Auth::id())->findOrFail($id);

        return view('vouchers.edit', compact('voucher'));
    }

    /**
     * Update the voucher.
     */
    public function update(Request $request, $id)
    {
        $voucher = Voucher::where('partner_id', Auth::id())->findOrFail($id);

        $request->validate([
            'description'   => 'nullable|string',
            'type'          => 'required|in:percentage,fixed',
            'value'         => 'required|integer',
            'min_amount'    => 'nullable|integer',
            'max_discount'  => 'nullable|integer',
            'quota'         => 'required|integer|min:1',
            'expires_at'    => 'nullable|date',
        ]);

        $voucher->update([
            'description'   => $request->description,
            'type'          => $request->type,
            'value'         => $request->value,
            'min_amount'    => $request->min_amount ?? 0,
            'max_discount'  => $request->max_discount,
            'quota'         => $request->quota,
            'expires_at'    => $request->expires_at,
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui!');
    }

    /**
     * Remove the voucher.
     */
    public function destroy($id)
    {
        $voucher = Voucher::where('partner_id', Auth::id())->findOrFail($id);

        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus!');
    }
}
