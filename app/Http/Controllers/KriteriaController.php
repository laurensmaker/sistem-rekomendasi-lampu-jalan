<?php
// app/Http/Controllers/KriteriaController.php
namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KriteriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Kriteria::query();
        
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_kriteria', 'LIKE', "%{$request->search}%");
        }
        
        $kriteria = $query->latest()->paginate(10);
        $totalBobot = Kriteria::sum('bobot');
        
        if ($request->has('search')) {
            $kriteria->appends(['search' => $request->search]);
        }
        
        return view('backend.kriteria.index', compact('kriteria', 'totalBobot'));
    }

    public function create()
    {
        return view('backend.kriteria.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kriteria' => 'required|string|max:255|unique:kriteria',
            'bobot' => 'required|numeric|min:0|max:100',
            'atribut' => 'required|in:benefit,cost',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $totalBobot = Kriteria::sum('bobot') + $request->bobot;
        if ($totalBobot > 100) {
            return redirect()->back()
                ->with('error', 'Total bobot melebihi 100%!')
                ->withInput();
        }

        try {
            Kriteria::create($request->all());
            return redirect()->route('kriteria.index')
                ->with('success', 'Kriteria berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Kriteria $kriterium)
    {
        return view('backend.kriteria.edit', compact('kriterium'));
    }

    public function update(Request $request, Kriteria $kriterium)
    {
        $validator = Validator::make($request->all(), [
            'nama_kriteria' => 'required|string|max:255|unique:kriteria,nama_kriteria,' . $kriterium->id,
            'bobot' => 'required|numeric|min:0|max:100',
            'atribut' => 'required|in:benefit,cost',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $totalBobotLain = Kriteria::where('id', '!=', $kriterium->id)->sum('bobot');
        $totalBobot = $totalBobotLain + $request->bobot;
        
        if ($totalBobot > 100) {
            return redirect()->back()
                ->with('error', 'Total bobot melebihi 100%!')
                ->withInput();
        }

        try {
            $kriterium->update($request->all());
            return redirect()->route('kriteria.index')
                ->with('success', 'Kriteria berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Kriteria $kriterium)
    {
        try {
            $kriterium->delete();
            return redirect()->route('kriteria.index')
                ->with('success', 'Kriteria berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:kriteria,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal'
            ], 422);
        }

        try {
            Kriteria::whereIn('id', $request->ids)->delete();
            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' kriteria berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}