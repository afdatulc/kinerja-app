<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Imports\PegawaiImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::with('user')->get();
        return view('pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|unique:pegawais,nip',
            'nama' => 'required|string',
            'email_bps' => 'nullable|email|unique:pegawais,email_bps',
            'jabatan' => 'nullable|string',
            'unit_kerja' => 'nullable|string',
            'status' => 'required|in:PNS,PPPK,Outsourcing,Lainnya',
            'seksi' => 'required|in:Sosial,Produksi,Distribusi,Nerwilis,IPDS,Umum,Lainnya',
        ]);

        $pegawai = Pegawai::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data pegawai berhasil ditambahkan',
                'data' => $pegawai
            ]);
        }

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function show(Pegawai $pegawai)
    {
        return response()->json($pegawai);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'nip' => 'required|string|unique:pegawais,nip,' . $pegawai->id,
            'nama' => 'required|string',
            'email_bps' => 'nullable|email|unique:pegawais,email_bps,' . $pegawai->id,
            'jabatan' => 'nullable|string',
            'unit_kerja' => 'nullable|string',
            'status' => 'required|in:PNS,PPPK,Outsourcing,Lainnya',
            'seksi' => 'required|in:Sosial,Produksi,Distribusi,Nerwilis,IPDS,Umum,Lainnya',
        ]);

        $pegawai->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data pegawai berhasil diperbarui',
                'data' => $pegawai
            ]);
        }

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function activateAccount($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        if (!$pegawai->email_bps) {
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pegawai tidak memiliki email BPS. Harap update data email terlebih dahulu.'
                ], 422);
            }
            return redirect()->back()->with('error', 'Pegawai tidak memiliki email BPS. Harap update data email terlebih dahulu.');
        }

        $user = \App\Models\User::updateOrCreate(
            ['email' => $pegawai->email_bps],
            [
                'name' => $pegawai->nama,
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'pegawai',
                'pegawai_id' => $pegawai->id
            ]
        );

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => "Akun untuk {$pegawai->nama} berhasil diaktifkan. Password default: password"
            ]);
        }

        return redirect()->back()->with('success', "Akun untuk {$pegawai->nama} berhasil diaktifkan. Password default: password");
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data pegawai berhasil dihapus'
            ]);
        }

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new PegawaiImport, $request->file('file'));
        return redirect()->route('pegawai.index')->with('success', 'Data Pegawai berhasil diimport.');
    }

    public function downloadTemplate()
    {
        $headers = ['nip', 'nama', 'email_bps', 'jabatan', 'unit_kerja', 'status', 'seksi'];
        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            protected $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array { return [$this->headers]; }
        }, 'template_import_pegawai.xlsx');
    }
}
