<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\Models\Laporan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Home extends Controller
{
    public function index()
    {
        return view('home');
    }
    public function dashboard()
    {
        $METABASE_SITE_URL = 'http://localhost:3000';
        $METABASE_SECRET_KEY = '2e1d26b2ec691edfdef23d4c09053ab59397e5cc7083c8dbeb3c3d9f0651f691';

        $exp = time() + (10 * 60); // 10 menit

        // Question 3
        $payload1 = [
            'resource' => ['question' => 41],
            'params' => new \stdClass(),
            'exp' => $exp,
        ];
        $token1 = JWT::encode($payload1, $METABASE_SECRET_KEY, 'HS256');
        $iframeUrl1 = $METABASE_SITE_URL . "/embed/question/" . $token1 . "#bordered=true&titled=true";

        // Question 7
        $payload2 = [
            'resource' => ['question' => 7],
            'params' => new \stdClass(),
            'exp' => $exp,
        ];
        $token2 = JWT::encode($payload2, $METABASE_SECRET_KEY, 'HS256');
        $iframeUrl2 = $METABASE_SITE_URL . "/embed/question/" . $token2 . "#bordered=true&titled=true";

        // Question 8
        $payload3 = [
            'resource' => ['question' => 40],
            'params' => new \stdClass(),
            'exp' => $exp,
        ];
        $token3 = JWT::encode($payload3, $METABASE_SECRET_KEY, 'HS256');
        $iframeUrl3 = $METABASE_SITE_URL . "/embed/question/" . $token3 . "#bordered=true&titled=true";


        // Ambil data laporan
        $totalLaporan = Laporan::count();
        $pendingLaporan = Laporan::where('status', 'tertunda')->count();
        $approvedLaporan = Laporan::where('status', 'diterima')->count();
        $processedLaporan = Laporan::where('status', 'diproses')->count();
        $rejectedLaporan = Laporan::where('status', 'ditolak')->count();
        $finishedLaporan = Laporan::where('status', 'selesai')->count();

        $latestLaporan = Laporan::with('fasilitas:id,nama_fasilitas,kode_fasilitas')
            ->select('id', 'id_fasilitas', 'deskripsi_kerusakan', 'created_at', 'approved_at', 'status')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'iframeUrl1',
            'iframeUrl2',
            'iframeUrl3',
            'totalLaporan',
            'pendingLaporan',
            'approvedLaporan',
            'processedLaporan',
            'rejectedLaporan',
            'finishedLaporan',
            'latestLaporan'
        ));

    }
    public function chartData()
    {
        // Tanggal 7 hari terakhir
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::today()->subDays($i)->format('Y-m-d'));
        }

        // Data laporan harian
        $rawData = Laporan::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $weeklyData = $dates->map(function ($date) use ($rawData) {
            return [
                'date' => $date,
                'total' => $rawData->get($date, 0)
            ];
        });

        // Data laporan per status
        $statusList = ['tertunda', 'diproses', 'diterima', 'selesai', 'ditolak'];
        $statusLast7Days = collect($statusList)->mapWithKeys(function ($status) {
            return [
                $status => Laporan::where('status', $status)
                    ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                    ->count()
            ];
        });

        return response()->json([
            'weekly' => $weeklyData,
            'status' => $statusLast7Days,
        ]);
    }
}
