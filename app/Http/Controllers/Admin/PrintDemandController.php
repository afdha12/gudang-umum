<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Division;
use App\Models\ItemDemand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlyUserDemandExport;
use Barryvdh\DomPDF\Facade\Pdf as Dompdf;
// use Spatie\LaravelPdf\Facades\Pdf as Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PrintDemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil user yang pernah mengajukan barang
        $users = User::whereIn('id', ItemDemand::pluck('user_id')->unique())->get();

        $approvedItems = ItemDemand::with(['user.division', 'stationery'])
            ->where('status', 1)
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->from, function ($query) use ($request) {
                $query->whereDate('dos', '>=', $request->from);
            })
            ->when($request->to, function ($query) use ($request) {
                $query->whereDate('dos', '<=', $request->to);
            })
            ->orderByDesc('dos')
            ->paginate(10)
            ->appends($request->all());

        // Ambil daftar bulan dan tahun unik dari ItemDemand
        $availablePeriods = ItemDemand::selectRaw('YEAR(dos) as tahun, MONTH(dos) as bulan')
            ->where('status', 1)
            ->distinct()
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        return view('pages.print.index', compact('approvedItems', 'users', 'availablePeriods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.print.cek');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $query = ItemDemand::where('status', 1);

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->from) {
            $query->whereDate('dos', '>=', $request->from);
        }
        if ($request->to) {
            $query->whereDate('dos', '<=', $request->to);
        }

        $approvedData = $query->with(['user.division', 'stationery'])->get();

        if ($approvedData->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data yang dapat dicetak!');
        }

        $totalJumlah = $approvedData->sum('amount');
        $division = optional($approvedData->first())->user->division_id;
        $manager = User::where('role', 'manager')->where('division_id', $division)->first() ??
            User::where('role', 'coo')->first();
        $coo = User::where('role', 'coo')->first();
        $admin = User::where('role', 'admin')->first();

        // Render view sebagai HTML
        $html = view('pages.print.pengajuan_barang', compact('approvedData', 'manager', 'admin', 'coo', 'totalJumlah'))->render();

        // Generate PDF pakai Laravel Snappy (wkhtmltopdf)
        return Pdf::loadHTML($html)
            ->setPaper('A4')
            ->setOption('enable-local-file-access', true)
            ->inline('pengajuan_barang.pdf');

        // Uncomment the following lines to generate the PDF using spatie
        // $pdf = Pdf::view('pages.print.pengajuan_barang', compact('approvedData', 'manager', 'admin', 'coo', 'totalJumlah'))
        //     ->format('A4')
        //     ->name('pengajuan_barang.pdf');
        // return $pdf->inline();

        // $pdf = Dompdf::loadView('pages.print.pengajuan_barang', compact('approvedData', 'manager', 'admin', 'coo', 'totalJumlah'))
        //     ->setPaper('A4')
        //     ->setWarnings(false);

        // return $pdf->stream();
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function exportExcel(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        // Nama bulan Indonesia
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $namaBulan = $bulanIndo[(int) $bulan] ?? $bulan;

        $filename = 'Rekap_Permintaan_' . $namaBulan . '_' . $tahun . '.xlsx';
        return Excel::download(new MonthlyUserDemandExport($from, $to), $filename);
    }
}
