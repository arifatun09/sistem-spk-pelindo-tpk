<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Criteria::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('jenis', 'LIKE', "%{$search}%");
        }

        $kriteria = $query->paginate(10);
        return view('kriteria.index', compact('kriteria'));
    }

    public function create()
    {
        return view('kriteria.create');
    }

    public function store(Request $request)
    {
        Criteria::updateOrCreate([
            'name' => $request->name
        ], [
            'jenis' => $request->jenis
        ]);

        session()->flash('success', 'Data berhasil disimpan!');

        return redirect()->route('Kriteria::index');
    }

    public function edit($id)
    {
        $kriteria = Criteria::find($id);
        return view('kriteria.edit', ['kriteria' => $kriteria]);
    }

    public function update(Request $request, $id)
    {
        $kriteria = Criteria::find($id);
        $kriteria->name = $request->name;
        $kriteria->jenis = $request->jenis;
        $kriteria->save();
        $request->session()->flash('success', 'Data berhasil diperbarui!');

        return redirect()->route('Kriteria::index');
    }

    public function destroy($id)
    {
        $kriteria = Criteria::find($id);
        $kriteria->delete();
        session()->flash('success', 'Data berhasil dihapus!');
        return redirect()->route('Kriteria::index');
    }
}
