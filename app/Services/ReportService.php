<?php

namespace App\Services;

use App\Repositories\ReportRepository;
use Carbon\Carbon;

class ReportService
{
    protected ReportRepository $repository;

    public function __construct(ReportRepository $repository)
    {
        $this->repository = $repository;
    }

    public function generateSalesReport(Carbon $start, Carbon $end): array
    {
        return $this->repository->generateSalesReport($start, $end);
    }

    public function generateProfitReport(Carbon $start, Carbon $end): array
    {
        return $this->repository->generateProfitReport($start, $end);
    }

    public function generateVendorReport(int $vendorId, Carbon $start, Carbon $end): array
    {
        $vendorService = app(VendorService::class);
        return $vendorService->getVendorAnalytics($vendorId, $start->toDateString(), $end->toDateString());
    }

    public function generateCustomerReport(int $customerId): array
    {
        $customerService = app(CustomerService::class);
        return $customerService->getCustomerAnalytics($customerId);
    }

    public function generateInventoryReport(): array
    {
        return $this->repository->generateInventoryReport();
    }

    public function exportReport(string $type, string $format, array $parameters = [])
    {
        $data = match ($type) {
            'sales' => $this->generateSalesReport(
                Carbon::parse($parameters['start'] ?? now()->startOfMonth()),
                Carbon::parse($parameters['end'] ?? now()->endOfMonth())
            ),
            'profit' => $this->generateProfitReport(
                Carbon::parse($parameters['start'] ?? now()->startOfMonth()),
                Carbon::parse($parameters['end'] ?? now()->endOfMonth())
            ),
            'inventory' => $this->generateInventoryReport(),
            default => throw new \InvalidArgumentException("Unknown report type: {$type}"),
        };

        if ($format === 'pdf') {
            $pdf = app('dompdf.wrapper')->loadView('reports.export', compact('data', 'type'));
            return $pdf->download("{$type}_report.pdf");
        }

        if ($format === 'csv') {
            $callback = function () use ($data) {
                $file = fopen('php://output', 'w');
                foreach ($data as $row) {
                    fputcsv($file, (array) $row);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$type}_report.csv",
            ]);
        }

        return $data;
    }
}
