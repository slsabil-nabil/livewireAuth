<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Browsershot\Browsershot;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesPdf(Request $request)
{
    $pdfPath = storage_path('app/public/sales_report.pdf');

    $user = Auth::user();
    $agency = $user->agency;

    $query = Sale::with(['customer', 'serviceType', 'provider', 'intermediary', 'account']);

    // فلترة بالتاريخ
    if ($request->filled('start_date')) {
        $query->whereDate('sale_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('sale_date', '<=', $request->end_date);
    }

    $sales = $query->latest()->get();

    // الحقول المطلوبة من الطلب
    $fields = $request->input('fields', []);

    // إذا لم يتم تحديد أي حقول، استخدم القيم الافتراضية
    if (empty($fields)) {
        $fields = [
            'sale_date', 'beneficiary_name', 'customer', 'serviceType',
            'provider', 'intermediary', 'usd_buy', 'usd_sell',
            'sale_profit', 'amount_received', 'account',
            'reference', 'pnr', 'route'
        ];
    }

    $html = view('reports.sales-pdf', [
        'sales' => $sales,
        'agency' => $agency,
        'startDate' => $request->start_date,
        'endDate' => $request->end_date,
        'fields' => $fields
    ])->render();

    Browsershot::html($html)
        ->setChromePath('C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe')
        ->format('A4')
        ->landscape()
        ->timeout(60)
        ->waitUntilNetworkIdle()
        ->savePdf($pdfPath);

    return response()->download($pdfPath)->deleteFileAfterSend();
}
}

