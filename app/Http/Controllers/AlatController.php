<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alat;
use App\Imports\AlatImport;
use Maatwebsite\Excel\Facades\Excel;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $query = Alat::query();

        $month = $request->get('month');
        $searchTerm = $request->get('search');

        if ($month) {
            // Ubah format dari YYYY-MM menjadi MM-YYYY
            $formattedMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('m-Y');
        } else {
            // Jika tidak ada periode yang diberikan, gunakan bulan saat ini dalam format MM-YYYY
            $formattedMonth = \Carbon\Carbon::now()->format('m-Y');
        }

        // Filter berdasarkan bulan
        $query->where('periode', '=', $formattedMonth);

        // Filter berdasarkan search term
        if ($searchTerm) {
            $query->whereHas('alatMaster', function ($q) use ($searchTerm) {
                $q->where('kode', 'like', "%{$searchTerm}%")
                  ->orWhere('nama', 'like', "%{$searchTerm}%");
            });
        }

        // Paginate results
        $alat = $query->paginate(10)->appends($request->query());

        return view('alat.index', compact('alat', 'month'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ], [
            'file.required' => 'Pilih file terlebih dahulu.',
            'file.mimes' => 'File harus berformat Excel (xlsx) atau CSV.',
        ]);

        $data = $request->file('file');
        $namaFile = $data->getClientOriginalName();
        $data->move('DataAlat', $namaFile);

        $import = new AlatImport(); 

        try {
            // Import file
            Excel::import($import, public_path('/DataAlat/' . $namaFile));

            // Ambil data dari file
            $dataFromExcel = Excel::toArray($import, public_path('/DataAlat/' . $namaFile));

            // Loop melalui setiap baris data dan lakukan operasi update atau insert
            foreach ($dataFromExcel[0] as $row) {
                if (!$import->cekDataMaster($row)) {
                    return redirect()->back()->withErrors(['file' => 'Gagal import, kode alat ' . $row[2] . ' dengan nama ' . $row[3] . ' belum terdaftar pada master alat.']);
                }

                // Update or Insert data
                $import->updateOrInsertData($row);
            }

            return redirect()->route('Alat::index')->with('success', 'Import data berhasil');
        } catch (\Exception $e) {
            // Tangani kesalahan format isi file
            return redirect()->back()->withErrors(['file' => 'Format isi file tidak sesuai. Silakan periksa file dan coba lagi.']);
        }
    }
}
