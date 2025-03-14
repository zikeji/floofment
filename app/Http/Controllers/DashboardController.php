<?php

namespace App\Http\Controllers;

use App\Models\PhoneRecording;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $phoneRecordingsChartData = DB::table(new PhoneRecording()->getTable())
                ->select([DB::raw('COALESCE ("nickname", "from") as name'), DB::raw('count(*) as count')])
                ->join('contacts', 'contacts.phone', '=', 'phone_recordings.from', 'left')
                ->orderBy('count', 'desc')
                ->groupBy('from', 'nickname')
                ->get();

        return Inertia::render('Dashboard', [
            'phoneRecordingsChartData' => $phoneRecordingsChartData,
        ]);
    }
}
