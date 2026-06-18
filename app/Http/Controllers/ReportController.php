<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function monthly()
    {
        // 1. Crear tabla temporal
        DB::statement("
            CREATE TEMPORARY TABLE tmp_sales_summary (
                product_id BIGINT,
                product_name VARCHAR(255),
                total_units INT,
                total_income DECIMAL(10,2)
            )
        ");

        // 2. Insertar datos del mes actual
        DB::statement("
            INSERT INTO tmp_sales_summary
            SELECT
                p.id,
                p.name,
                SUM(s.quantity),
                ROUND(SUM(s.total_price), 2)
            FROM products p
            INNER JOIN sales s ON p.id = s.product_id
            WHERE MONTH(s.sale_date) = MONTH(CURDATE())
            AND YEAR(s.sale_date) = YEAR(CURDATE())
            GROUP BY p.id, p.name
        ");

        // 3. Leer tabla temporal
        $data = DB::select("SELECT * FROM tmp_sales_summary");

        // 4. Totales generales
        $totalUnits = collect($data)->sum('total_units');
        $totalIncome = round(collect($data)->sum('total_income'), 2);

        // 5. Generar PDF
        $pdf = Pdf::loadView('reports.monthly', [
            'data' => $data,
            'totalUnits' => $totalUnits,
            'totalIncome' => $totalIncome
        ]);

        return $pdf->download('reporte-mensual.pdf');
    }
}