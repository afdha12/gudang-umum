<?php
namespace App\Exports;

use App\Models\User;
use App\Models\ItemDemand;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonthlyUserDemandExport implements WithMultipleSheets
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function sheets(): array
    {
        $sheets = [];
        $users = User::whereIn('id', ItemDemand::where('status', 1)
            ->when($this->from, fn($q) => $q->whereDate('dos', '>=', $this->from))
            ->when($this->to, fn($q) => $q->whereDate('dos', '<=', $this->to))
            ->pluck('user_id')->unique())->get();

        foreach ($users as $user) {
            $sheets[] = new UserDemandSheetExport($user, $this->from, $this->to);
        }
        return $sheets;
    }
}