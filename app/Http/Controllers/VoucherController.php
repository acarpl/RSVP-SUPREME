<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::where('partner_id', Auth::id())->latest()->get();
        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('vouchers.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'code' => 'required|string|unique:vouchers,code',
        'discount_type' => 'required|in:percentage,fixed',
        'discount_value' => 'required|numeric|min:0',
        'min_amount' => 'required|numeric|min:0',

        'valid_from' => 'required|date',
        'valid_until' => 'required|date|after:valid_from',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('vouchers', 'public');
    }

    Voucher::create([
        'partner_id' => Auth::id(),
        'name' => $request->name,
        'description' => $request->description,
        'code' => strtoupper($request->code),
        
        // 🔥 ISI KOLom LAMA (WAJIB!)
        'type' => $request->discount_type,       // Sesuaikan dengan 'discount_type'
        'value' => $request->discount_value,     // Sesuaikan dengan 'discount_value'
        
        // Kolom baru
        'discount_type' => $request->discount_type,
        'discount_value' => $request->discount_value,
        'min_amount' => $request->min_amount,
        
        'valid_from' => $request->valid_from,
        'valid_until' => $request->valid_until,
        'image' => $imagePath,
        'is_active' => true,
    ]);

    return redirect()->route('partner.vouchers.index')
                    ->with('success', 'Voucher berhasil dibuat!');
}

    public function edit(Voucher $voucher)
    {
        $this->authorizeVoucher($voucher);
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $this->authorizeVoucher($voucher);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:vouchers,code,' . $voucher->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $voucher->image;
        if ($request->hasFile('image')) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image')->store('vouchers', 'public');
        }

        $voucher->update([
    'name' => $request->name,
    'description' => $request->description,
    'code' => strtoupper($request->code),
    
    // 🔥 ISI KOLom LAMA (WAJIB!)
    'type' => $request->discount_type,
    'value' => $request->discount_value,
    
    // Kolom baru
    'discount_type' => $request->discount_type,
    'discount_value' => $request->discount_value,
    'min_amount' => $request->min_amount,
    'valid_from' => $request->valid_from,
    'valid_until' => $request->valid_until,
    'image' => $imagePath,
]);

        return redirect()->route('partner.vouchers.index')
                        ->with('success', 'Voucher berhasil diperbarui!');
    }

    public function destroy(Voucher $voucher)
    {
        $this->authorizeVoucher($voucher);
        if ($voucher->image) Storage::disk('public')->delete($voucher->image);
        $voucher->delete();
        return back()->with('success', 'Voucher berhasil dihapus!');
    }

    private function authorizeVoucher(Voucher $voucher)
    {
        if ($voucher->partner_id !== Auth::id()) {
            abort(403);
        }
    }
}