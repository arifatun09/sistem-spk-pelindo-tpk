<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\AlatMaster;
use Illuminate\Http\Request;

class AlatMasterController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->query('search');
        $alatMasterQuery = AlatMaster::query();

        if ($search) {
            $alatMasterQuery->where('kode', 'like', '%' . $search . '%')
                            ->orWhere('nama', 'like', '%' . $search . '%');
        }

        $alatMaster = $alatMasterQuery->paginate(10);
        return view('alat-master.index', compact('alatMaster'));
    }

    public function create()
    {
        return view('alat-master.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:alat_masters,nama',
            'kode' => 'required|unique:alat_masters,kode'
        ], [
            'name.unique' => 'Nama sudah ada!',
            'kode.unique' => 'Kode sudah ada!'
        ]);

        AlatMaster::create([
            'nama' => $request->name,
            'kode' => $request->kode
        ]);

        session()->flash('success', 'Data berhasil disimpan!');

        return redirect()->route('Alat-Master::index');
    }

    public function edit(AlatMaster $alat)
    {
        $alatMaster = $alat;
        return view('alat-master.edit', compact('alatMaster'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AlatMaster  $alat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlatMaster $alat)
    {

        //cek apakah ada kode atau nama yang sama pada tabel alat
        $alatCh = Alat::whereHas('alatMaster', function($query) use ($alat) {
            $query->where('nama', $alat->nama)->where('kode', $alat->kode);
        })->get();

        if ($alatCh->count() > 0) {
            session()->flash('failed', 'Data gagal diubah, Kode atau Nama dipakai!');
            return redirect()->route('Alat-Master::index');
        }

        $request->validate([
            'name' => 'required|unique:alat_masters,nama,' . $alat->id,
            'kode' => 'required|unique:alat_masters,kode,' . $alat->id
        ], [
            'name.required' => 'Nama harus diisi!',
            'name.unique' => 'Nama sudah ada!',
            'kode.required' => 'Kode harus diisi!',
            'kode.unique' => 'Kode sudah ada!'
        ]);

        $alat->update([
            'nama' => $request->name,
            'kode' => $request->kode
        ]);

        session()->flash('success', 'Data berhasil diubah!');

        return redirect()->route('Alat-Master::index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AlatMaster  $alat
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlatMaster $alat)
    {
        //cek apakah ada kode atau nama yang sama pada tabel alat
        $alatCh = Alat::whereHas('alatMaster', function($query) use ($alat) {
            $query->where('nama', $alat->nama)->where('kode', $alat->kode);
        })->get();

        if ($alatCh->count() > 0) {
            session()->flash('failed', 'Data gagal diubah, Kode atau Nama dipakai!');
            return redirect()->route('Alat-Master::index');
        }

        $alat->delete();
        session()->flash('success', 'Data berhasil dihapus!');
        return redirect()->route('Alat-Master::index');
    }
}
