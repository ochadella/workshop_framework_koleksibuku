<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Tampilkan data customer
     */
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customer.index', compact('customers'));
    }

    /**
     * Form tambah customer 1
     * Simpan foto ke database (blob/base64)
     */
    public function createBlob()
    {
        return view('customer.create-blob');
    }

    /**
     * Simpan customer 1
     * Foto disimpan ke database
     */
    public function storeBlob(Request $request)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto_blob' => 'required|string',
        ], [
            'nama_customer.required' => 'Nama customer wajib diisi.',
            'foto_blob.required' => 'Foto customer wajib diambil.',
        ]);

        Customer::create([
            'nama_customer' => $request->nama_customer,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'foto_blob' => $request->foto_blob,
            'foto_path' => null,
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan dengan foto blob.');
    }

    /**
     * Form tambah customer 2
     * Simpan foto ke file, path disimpan ke database
     */
    public function createFile()
    {
        return view('customer.create-file');
    }

    /**
     * Simpan customer 2
     * Foto disimpan sebagai file
     */
    public function storeFile(Request $request)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto_file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_customer.required' => 'Nama customer wajib diisi.',
            'foto_file.required' => 'Foto customer wajib diupload.',
            'foto_file.image' => 'File harus berupa gambar.',
        ]);

        $path = null;

        if ($request->hasFile('foto_file')) {
            $path = $request->file('foto_file')->store('customers', 'public');
        }

        Customer::create([
            'nama_customer' => $request->nama_customer,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'foto_blob' => null,
            'foto_path' => $path,
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan dengan foto file.');
    }

    /**
     * Detail customer
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.show', compact('customer'));
    }

    /**
     * Form edit customer
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto_file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_blob' => 'nullable|string',
        ], [
            'nama_customer.required' => 'Nama customer wajib diisi.',
        ]);

        $data = [
            'nama_customer' => $request->nama_customer,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ];

        if ($request->filled('foto_blob')) {
            $data['foto_blob'] = $request->foto_blob;
        }

        if ($request->hasFile('foto_file')) {
            if ($customer->foto_path && Storage::disk('public')->exists($customer->foto_path)) {
                Storage::disk('public')->delete($customer->foto_path);
            }

            $data['foto_path'] = $request->file('foto_file')->store('customers', 'public');
        }

        $customer->update($data);

        return redirect()->route('customer.index')
            ->with('success', 'Data customer berhasil diupdate.');
    }

    /**
     * Hapus customer
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->foto_path && Storage::disk('public')->exists($customer->foto_path)) {
            Storage::disk('public')->delete($customer->foto_path);
        }

        $customer->delete();

        return redirect()->route('customer.index')
            ->with('success', 'Data customer berhasil dihapus.');
    }
}