<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $produk = Produk::all();
        return response()->json([
            'success' => true,
            'data' => $produk,
            'message' => 'product successfully retrieved from server',
            'code' => 200
        ], 200);
    }

    public function show($id)
    {
        $produk = Produk::find($id);
        if ($produk) {
            return response()->json([
                'success' => true,
                'data' => $produk,
                'message' => 'products with ID ' . $id . ' are available',
                'code' => 200
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Products with ID ' . $id . ' not found',
                'code' => 404
            ], 404);
        }
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'harga' => 'required|integer',
            'warna' => 'required|string',
            'kondisi' => 'required|in:baru,lama',
            'deskripsi' => 'string'
        ]);

        $data = $request->all();
        $produk = Produk::create($data);

        return response()->json([
            'success' => true,
            'data' => $produk,
            'message' => 'product added successfully',
            'code' => 201
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Products with ID ' . $id . ' not found',
                'code' => 404
            ], 404);
        }
        $this->validate($request, [
            'nama' => 'string',
            'harga' => 'integer',
            'warna' => 'string',
            'kondisi' => 'in:baru,lama',
            'deskripsi' => 'string'
        ]);
        $data = $request->all();
        $produk->fill($data);
        $produk->save();
        return response()->json([
            'success' => true,
            'data' => $produk,
            'message' => 'product edited successfully',
            'code' => 201
        ], 201);
    }

    public function destroy($id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Products with ID ' . $id . ' not found',
                'code' => 404
            ], 404);
        }
        $produk->delete();
        return response()->json([
            'success' => true,
            'message' => 'product removed successfully',
            'code' => 200
        ], 200);
    }
}
