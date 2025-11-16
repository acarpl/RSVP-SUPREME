<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /* =============================
       PARTNER + ADMIN CRUD VOUCHER
       ============================= */

    public function index()
    {
        // User hanya lihat voucher yg aktif
        if (Auth::user()->role == 'user') {
            $vouchers = Voucher::where('expires_at', '>=', now())->get();
        }
        // Admin & partner lihat semua
        else {
            $vouchers = Voucher::where('partner_id', Auth::id())
                        ->orWhereNull('partner_id') // admin
                        ->get();
        }

        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        if (!$this->canManage()) abort(403);
        return view('vouchers.create');
    }

    public function store(Request $request)
    {
        if (!$this->canManage()) abort(403);

        $request->validate([
            'code' => 'required|unique:vouchers',
            'type' => 'required',
            'value' => 'required|integer|min:1',
            'quota' => 'required|integer|min:0',
            'expires_at' => 'required|date'
        ]);

        Voucher::create([
            'partner_id' => Auth::user()->role == 'partner' ? Auth::id() : null,
            'code' => $request->code,
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'min_amount' => $request->min_amount,
            'max_discount' => $request->max_discount,
            'quota' => $request->quota,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('vouchers.index')
            ->with('success', 'Voucher berhasil dibuat');
    }

    public function edit(Voucher $voucher)
    {
        if (!$this->canManageVoucher($voucher)) abort(403);
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        if (!$this->canManageVoucher($voucher)) abort(403);

        $request->validate([
            'type' => 'required',
            'value' => 'required|integer|min:1',
            'quota' => 'required|integer|min:0',
            'expires_at' => 'required|date'
        ]);

        $voucher->update($request->all());

        return redirect()->route('vouchers.index')
            ->with('success', 'Voucher berhasil diperbarui');
    }

    public function destroy(Voucher $voucher)
    {
        if (!$this->canManageVoucher($voucher)) abort(403);

        $voucher->delete();

        return redirect()->route('vouchers.index')
            ->with('success', 'Voucher berhasil dihapus');
    }

    /* =============================
            USER APPLY VOUCHER
       ============================= */

    public function use(Voucher $voucher)
    {
        if ($voucher->expires_at < now()->toDateString()) {
            return back()->with('error', 'Voucher sudah kadaluarsa!');
        }

        if ($voucher->quota <= 0) {
            return back()->with('error', 'Voucher sudah habis!');
        }

        $voucher->decrement('quota');

        return back()->with('success', 'Voucher berhasil digunakan!');
    }

    /* =============================
            ROLE VALIDATION
       ============================= */

    private function canManage()
    {
        return in_array(Auth::user()->role, ['admin', 'partner']);
    }

    private function canManageVoucher(Voucher $voucher)
    {
        return Auth::user()->role == 'admin'
            || (Auth::user()->role == 'partner' && $voucher->partner_id == Auth::id());
    }
}
