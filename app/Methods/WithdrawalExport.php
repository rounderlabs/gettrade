<?php
namespace App\Methods;
use App\Models\WithdrawalHistory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WithdrawalExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = WithdrawalHistory::with('user');

        if (!empty($this->filters['username'])) {
            $query->whereHas('user', fn ($q) =>
            $q->where('username', 'like', "%{$this->filters['username']}%")
            );
        }

        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['from_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['from_date']);
        }

        if (!empty($this->filters['to_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['to_date']);
        }

        return $query->latest()->get()->map(fn ($w) => [
            $w->user->username,
            $w->txn_id,
            $w->amount,
            $w->fees,
            $w->receivable_amount,
            $w->type,
            $w->status,
            $w->created_at->format('Y-m-d H:i'),
        ]);
    }

    public function headings(): array
    {
        return [
            'Username',
            'Txn ID',
            'Amount',
            'Fees',
            'Receivable',
            'Type',
            'Status',
            'Date',
        ];
    }
}
