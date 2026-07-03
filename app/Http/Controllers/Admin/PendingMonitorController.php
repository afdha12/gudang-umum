<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ItemDemand;
use App\Models\Stationery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PendingMonitorController extends Controller
{
    /**
     * Tampilkan monitoring semua permintaan yang masih pending di seluruh flow.
     * Mendukung 3 tampilan: per user, per tanggal, per barang.
     */
    public function index(Request $request)
    {
        $view = $request->get('view', 'user'); // user | date | item
        $search = $request->get('search', '');

        // Base query: semua item yang BELUM selesai (belum fully approved/rejected/cancelled)
        $baseQuery = ItemDemand::query()
            ->where(function ($q) {
                $q->whereNull('is_cancelled')->orWhere('is_cancelled', 0);
            })
            ->whereNull('status')
            ->where(function ($q) {
                // Belum final: salah satu level masih null DAN belum ada yang reject
                $q->where(function ($sub) {
                    // Ada level yang masih pending
                    $sub->whereNull('manager_approval')
                        ->orWhere(function ($inner) {
                            $inner->where('manager_approval', 1)
                                ->whereNull('coo_approval');
                        })
                        ->orWhere(function ($inner) {
                            $inner->where('manager_approval', 1)
                                ->where('coo_approval', 1)
                                ->whereNull('status');
                        });
                });
            })
            // Exclude yang sudah di-reject
            ->where(function ($q) {
                $q->where('manager_approval', '!=', 0)->orWhereNull('manager_approval');
            })
            ->where(function ($q) {
                $q->where('coo_approval', '!=', 0)->orWhereNull('coo_approval');
            });

        // Summary counts untuk cards
        $summaryQuery = ItemDemand::query()
            ->where(function ($q) {
                $q->whereNull('is_cancelled')->orWhere('is_cancelled', 0);
            })
            ->where(function ($q) {
                $q->where('manager_approval', '!=', 0)->orWhereNull('manager_approval');
            })
            ->where(function ($q) {
                $q->where('coo_approval', '!=', 0)->orWhereNull('coo_approval');
            })
            ->whereNull('status');

        $waitingManager = (clone $summaryQuery)->whereNull('manager_approval')->count();
        $waitingCoo = (clone $summaryQuery)->where('manager_approval', 1)->whereNull('coo_approval')->count();
        $waitingAdmin = (clone $summaryQuery)->where('manager_approval', 1)->where('coo_approval', 1)->whereNull('status')->count();
        $totalPending = $waitingManager + $waitingCoo + $waitingAdmin;

        $data = collect();

        switch ($view) {
            case 'user':
                $data = $this->getByUser($baseQuery, $search);
                break;
            case 'date':
                $data = $this->getByDate($baseQuery, $search);
                break;
            case 'item':
                $data = $this->getByItem($baseQuery, $search);
                break;
        }

        return view('admin.pending.index', compact(
            'data',
            'view',
            'search',
            'totalPending',
            'waitingManager',
            'waitingCoo',
            'waitingAdmin'
        ));
    }

    /**
     * Tampilan per User: group by user_id
     */
    private function getByUser($baseQuery, $search)
    {
        $query = ItemDemand::query()
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_items'),
                DB::raw("SUM(CASE WHEN manager_approval IS NULL THEN 1 ELSE 0 END) as waiting_manager"),
                DB::raw("SUM(CASE WHEN manager_approval = 1 AND coo_approval IS NULL THEN 1 ELSE 0 END) as waiting_coo"),
                DB::raw("SUM(CASE WHEN manager_approval = 1 AND coo_approval = 1 AND status IS NULL THEN 1 ELSE 0 END) as waiting_admin"),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('MAX(created_at) as last_request'),
                DB::raw('MIN(created_at) as first_request')
            )
            ->where(function ($q) {
                $q->whereNull('is_cancelled')->orWhere('is_cancelled', 0);
            })
            ->where(function ($q) {
                $q->where('manager_approval', '!=', 0)->orWhereNull('manager_approval');
            })
            ->where(function ($q) {
                $q->where('coo_approval', '!=', 0)->orWhereNull('coo_approval');
            })
            ->whereNull('status')
            ->where(function ($q) {
                $q->whereNull('manager_approval')
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->whereNull('coo_approval');
                    })
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->where('coo_approval', 1)->whereNull('status');
                    });
            })
            ->groupBy('user_id')
            ->orderByDesc('last_request');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                // This won't work on aggregate query, we'll filter after
            });
        }

        $result = $query->paginate(15);
        $result->load('user');

        // Filter by search after loading (since we group by)
        if ($search) {
            $userIds = User::where('name', 'like', "%{$search}%")->pluck('id');
            // Re-query with user filter
            $result = ItemDemand::query()
                ->select(
                    'user_id',
                    DB::raw('COUNT(*) as total_items'),
                    DB::raw("SUM(CASE WHEN manager_approval IS NULL THEN 1 ELSE 0 END) as waiting_manager"),
                    DB::raw("SUM(CASE WHEN manager_approval = 1 AND coo_approval IS NULL THEN 1 ELSE 0 END) as waiting_coo"),
                    DB::raw("SUM(CASE WHEN manager_approval = 1 AND coo_approval = 1 AND status IS NULL THEN 1 ELSE 0 END) as waiting_admin"),
                    DB::raw('SUM(amount) as total_amount'),
                    DB::raw('MAX(created_at) as last_request'),
                    DB::raw('MIN(created_at) as first_request')
                )
                ->whereIn('user_id', $userIds)
                ->where(function ($q) {
                    $q->whereNull('is_cancelled')->orWhere('is_cancelled', 0);
                })
                ->where(function ($q) {
                    $q->where('manager_approval', '!=', 0)->orWhereNull('manager_approval');
                })
                ->where(function ($q) {
                    $q->where('coo_approval', '!=', 0)->orWhereNull('coo_approval');
                })
                ->whereNull('status')
                ->where(function ($q) {
                    $q->whereNull('manager_approval')
                        ->orWhere(function ($inner) {
                            $inner->where('manager_approval', 1)->whereNull('coo_approval');
                        })
                        ->orWhere(function ($inner) {
                            $inner->where('manager_approval', 1)->where('coo_approval', 1)->whereNull('status');
                        });
                })
                ->groupBy('user_id')
                ->orderByDesc('last_request')
                ->paginate(15);
            $result->load('user');
        }

        return $result;
    }

    /**
     * Tampilan per Tanggal: group by dos (date of submission)
     */
    private function getByDate($baseQuery, $search)
    {
        $query = ItemDemand::query()
            ->select(
                'dos',
                DB::raw('COUNT(*) as total_items'),
                DB::raw('COUNT(DISTINCT user_id) as total_users'),
                DB::raw("SUM(CASE WHEN manager_approval IS NULL THEN 1 ELSE 0 END) as waiting_manager"),
                DB::raw("SUM(CASE WHEN manager_approval = 1 AND coo_approval IS NULL THEN 1 ELSE 0 END) as waiting_coo"),
                DB::raw("SUM(CASE WHEN manager_approval = 1 AND coo_approval = 1 AND status IS NULL THEN 1 ELSE 0 END) as waiting_admin"),
                DB::raw('SUM(amount) as total_amount')
            )
            ->where(function ($q) {
                $q->whereNull('is_cancelled')->orWhere('is_cancelled', 0);
            })
            ->where(function ($q) {
                $q->where('manager_approval', '!=', 0)->orWhereNull('manager_approval');
            })
            ->where(function ($q) {
                $q->where('coo_approval', '!=', 0)->orWhereNull('coo_approval');
            })
            ->whereNull('status')
            ->where(function ($q) {
                $q->whereNull('manager_approval')
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->whereNull('coo_approval');
                    })
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->where('coo_approval', 1)->whereNull('status');
                    });
            })
            ->groupBy('dos')
            ->orderByDesc('dos');

        if ($search) {
            $query->where('dos', 'like', "%{$search}%");
        }

        return $query->paginate(15);
    }

    /**
     * Tampilan per Barang: group by stationery_id
     */
    private function getByItem($baseQuery, $search)
    {
        $query = ItemDemand::query()
            ->select(
                'stationery_id',
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('COUNT(DISTINCT user_id) as total_users'),
                DB::raw("SUM(CASE WHEN manager_approval IS NULL THEN 1 ELSE 0 END) as waiting_manager"),
                DB::raw("SUM(CASE WHEN manager_approval = 1 AND coo_approval IS NULL THEN 1 ELSE 0 END) as waiting_coo"),
                DB::raw("SUM(CASE WHEN manager_approval = 1 AND coo_approval = 1 AND status IS NULL THEN 1 ELSE 0 END) as waiting_admin"),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('MAX(created_at) as last_request')
            )
            ->where(function ($q) {
                $q->whereNull('is_cancelled')->orWhere('is_cancelled', 0);
            })
            ->where(function ($q) {
                $q->where('manager_approval', '!=', 0)->orWhereNull('manager_approval');
            })
            ->where(function ($q) {
                $q->where('coo_approval', '!=', 0)->orWhereNull('coo_approval');
            })
            ->whereNull('status')
            ->where(function ($q) {
                $q->whereNull('manager_approval')
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->whereNull('coo_approval');
                    })
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->where('coo_approval', 1)->whereNull('status');
                    });
            })
            ->groupBy('stationery_id')
            ->orderByDesc('total_amount');

        if ($search) {
            $stationeryIds = Stationery::where('nama_barang', 'like', "%{$search}%")->pluck('id');
            $query->whereIn('stationery_id', $stationeryIds);
        }

        $result = $query->paginate(15);
        $result->load('stationery');

        return $result;
    }

    /**
     * Detail permintaan pending per user
     */
    public function detailByUser($userId)
    {
        $user = User::findOrFail($userId);

        $items = ItemDemand::with('stationery')
            ->where('user_id', $userId)
            ->where(function ($q) {
                $q->whereNull('is_cancelled')->orWhere('is_cancelled', 0);
            })
            ->where(function ($q) {
                $q->where('manager_approval', '!=', 0)->orWhereNull('manager_approval');
            })
            ->where(function ($q) {
                $q->where('coo_approval', '!=', 0)->orWhereNull('coo_approval');
            })
            ->whereNull('status')
            ->where(function ($q) {
                $q->whereNull('manager_approval')
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->whereNull('coo_approval');
                    })
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->where('coo_approval', 1)->whereNull('status');
                    });
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.pending.detail', compact('user', 'items'));
    }

    /**
     * Detail permintaan pending per tanggal
     */
    public function detailByDate($date)
    {
        $items = ItemDemand::with(['stationery', 'user'])
            ->whereDate('dos', $date)
            ->where(function ($q) {
                $q->whereNull('is_cancelled')->orWhere('is_cancelled', 0);
            })
            ->where(function ($q) {
                $q->where('manager_approval', '!=', 0)->orWhereNull('manager_approval');
            })
            ->where(function ($q) {
                $q->where('coo_approval', '!=', 0)->orWhereNull('coo_approval');
            })
            ->whereNull('status')
            ->where(function ($q) {
                $q->whereNull('manager_approval')
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->whereNull('coo_approval');
                    })
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->where('coo_approval', 1)->whereNull('status');
                    });
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.pending.detail_date', compact('date', 'items'));
    }

    /**
     * Detail permintaan pending per barang
     */
    public function detailByItem($stationeryId)
    {
        $stationery = Stationery::findOrFail($stationeryId);

        $items = ItemDemand::with('user')
            ->where('stationery_id', $stationeryId)
            ->where(function ($q) {
                $q->whereNull('is_cancelled')->orWhere('is_cancelled', 0);
            })
            ->where(function ($q) {
                $q->where('manager_approval', '!=', 0)->orWhereNull('manager_approval');
            })
            ->where(function ($q) {
                $q->where('coo_approval', '!=', 0)->orWhereNull('coo_approval');
            })
            ->whereNull('status')
            ->where(function ($q) {
                $q->whereNull('manager_approval')
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->whereNull('coo_approval');
                    })
                    ->orWhere(function ($inner) {
                        $inner->where('manager_approval', 1)->where('coo_approval', 1)->whereNull('status');
                    });
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.pending.detail_item', compact('stationery', 'items'));
    }
}
