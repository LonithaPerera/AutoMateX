<?php

namespace App\Http\Controllers;

use App\Models\Garage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GarageController extends Controller
{
    // List all garages (for vehicle owners to browse)
    public function index()
    {
        $garages = Garage::where('is_active', true)
                         ->withCount('ratings')
                         ->withAvg('ratings', 'rating')
                         ->get();
        return view('garages.index', compact('garages'));
    }

    // Show garage profile setup form
    public function create()
    {
        return view('garages.create');
    }

    // Save new garage profile
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:150',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'description'    => 'nullable|string|max:500',
            'specialization' => 'nullable|string|max:150',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('garages', 'public');
        }

        Garage::create([
            'user_id'        => Auth::id(),
            'name'           => $request->name,
            'address'        => $request->address,
            'city'           => $request->city,
            'phone'          => $request->phone,
            'description'    => $request->description,
            'specialization' => $request->specialization,
            'photo'          => $photoPath,
        ]);

        return redirect()->route('garage.dashboard')
                         ->with('success', __('app.garage_created'));
    }

    // Show garage profile edit form
    public function edit()
    {
        $garage = Auth::user()->garage;
        if (!$garage) {
            return redirect()->route('garages.create')
                             ->with('info', 'Please set up your garage profile first.');
        }
        return view('garages.edit', compact('garage'));
    }

    // Save updated garage profile
    public function update(Request $request)
    {
        $garage = Auth::user()->garage;
        if (!$garage) abort(403);

        $request->validate([
            'name'           => 'required|string|max:150',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'description'    => 'nullable|string|max:500',
            'specialization' => 'nullable|string|max:150',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name'           => $request->name,
            'address'        => $request->address,
            'city'           => $request->city,
            'phone'          => $request->phone,
            'description'    => $request->description,
            'specialization' => $request->specialization,
        ];

        if ($request->hasFile('photo')) {
            if ($garage->photo) {
                Storage::disk('public')->delete($garage->photo);
            }
            $data['photo'] = $request->file('photo')->store('garages', 'public');
        }

        $garage->update($data);

        return redirect()->route('garage.dashboard')
                         ->with('success', __('app.garage_updated'));
    }

    // Update monthly revenue target
    public function updateTarget(Request $request)
    {
        $garage = Auth::user()->garage;
        if (!$garage) abort(403);

        $request->validate([
            'monthly_target' => 'nullable|integer|min:0|max:99999999',
        ]);

        $garage->update(['monthly_target' => $request->monthly_target ?: null]);

        return redirect()->route('garage.dashboard')
                         ->with('success', __('app.target_saved'));
    }

    // Garage invoice history — all completed invoices
    public function invoices()
    {
        $garage = Auth::user()->garage;
        if (!$garage) {
            return redirect()->route('garages.create');
        }

        $invoices = $garage->bookings()
                           ->with('vehicle.user')
                           ->where('status', 'completed')
                           ->whereNotNull('invoice_amount')
                           ->orderBy('booking_date', 'desc')
                           ->get();

        $totalRevenue = $invoices->sum('invoice_amount');

        return view('garages.invoices', compact('garage', 'invoices', 'totalRevenue'));
    }

    // Garage owner dashboard — see all bookings
    public function bookingsList()
    {
        $garage = Auth::user()->garage;
        if (!$garage) {
            return redirect()->route('garages.create');
        }

        $upcomingRaw = $garage->bookings()->with('vehicle.user')
                              ->whereIn('status', ['pending','confirmed'])
                              ->orderBy('booking_date','asc')->get();
        $overdueUp = $upcomingRaw->filter(fn($b) => $b->booking_date->lt(today()));
        $futureUp  = $upcomingRaw->filter(fn($b) => !$b->booking_date->lt(today()));
        $upcoming  = $overdueUp->concat($futureUp)->values();

        $history  = $garage->bookings()->with('vehicle.user')
                           ->whereIn('status', ['completed','cancelled'])
                           ->orderBy('booking_date','desc')->get();

        $bookings = $upcoming->concat($history);

        $returningUserIds = $bookings
            ->groupBy(fn($b) => optional($b->vehicle)->user_id)
            ->filter(fn($g, $k) => $k !== null && $g->count() > 1)
            ->keys()->toArray();

        $customerHistory = $bookings
            ->filter(fn($b) => optional($b->vehicle)->user_id !== null)
            ->groupBy(fn($b) => $b->vehicle->user_id)
            ->map(fn($group) => [
                'count'    => $group->count(),
                'services' => $group->sortByDesc('booking_date')->take(3)->pluck('service_type')->toArray(),
            ]);

        $calendarDates = $bookings->map(fn($b) => [
            'date'   => $b->booking_date->format('Y-m-d'),
            'status' => $b->status,
        ])->values();

        return view('garages.bookings', compact('garage', 'bookings', 'returningUserIds', 'customerHistory', 'calendarDates'));
    }

    public function dashboard()
    {
        $garage = Auth::user()->garage;

        if (!$garage) {
            return redirect()->route('garages.create')
                             ->with('info', 'Please set up your garage profile first.');
        }

        // Upcoming (pending/confirmed) — overdue first, then future by date asc
        $upcomingRaw = $garage->bookings()->with('vehicle.user')
                              ->whereIn('status', ['pending','confirmed'])
                              ->orderBy('booking_date','asc')->get();

        $overdueUp  = $upcomingRaw->filter(fn($b) => $b->booking_date->lt(today()));
        $futureUp   = $upcomingRaw->filter(fn($b) => !$b->booking_date->lt(today()));
        $upcoming   = $overdueUp->concat($futureUp)->values();

        $history    = $garage->bookings()->with('vehicle.user')
                             ->whereIn('status', ['completed','cancelled'])
                             ->orderBy('booking_date','desc')->get();

        $bookings = $upcoming->concat($history);

        // All-time revenue & job count
        $revenue           = $garage->bookings()->where('status','completed')->whereNotNull('invoice_amount')->sum('invoice_amount');
        $completedJobsTotal = $garage->bookings()->where('status','completed')->whereNotNull('invoice_amount')->count();

        // This month revenue & job count
        $revenueThisMonth       = $garage->bookings()->where('status','completed')->whereNotNull('invoice_amount')
                                         ->whereYear('booking_date', now()->year)->whereMonth('booking_date', now()->month)
                                         ->sum('invoice_amount');
        $completedJobsThisMonth = $garage->bookings()->where('status','completed')->whereNotNull('invoice_amount')
                                         ->whereYear('booking_date', now()->year)->whereMonth('booking_date', now()->month)
                                         ->count();

        // Last 6 months for chart (includes job count per month)
        $chartData = collect(range(5, 0))->map(function ($i) use ($garage) {
            $date = now()->subMonths($i);
            return [
                'month'   => $date->format('M y'),
                'revenue' => (float) $garage->bookings()
                                ->where('status','completed')->whereNotNull('invoice_amount')
                                ->whereYear('booking_date', $date->year)->whereMonth('booking_date', $date->month)
                                ->sum('invoice_amount'),
                'jobs'    => (int) $garage->bookings()
                                ->where('status','completed')
                                ->whereYear('booking_date', $date->year)->whereMonth('booking_date', $date->month)
                                ->count(),
            ];
        });

        // Today's schedule — pending/confirmed bookings for today, sorted by time
        $todayBookings = $garage->bookings()->with('vehicle.user')
                                ->whereIn('status', ['pending','confirmed'])
                                ->whereDate('booking_date', today())
                                ->orderBy('booking_time', 'asc')
                                ->get();

        // Quick stats — average invoice value
        $avgInvoice = $completedJobsTotal > 0 ? (int) round($revenue / $completedJobsTotal) : 0;

        // Quick stats — most booked service type
        $mostBookedService = $garage->bookings()
                                    ->selectRaw('service_type, COUNT(*) as cnt')
                                    ->groupBy('service_type')
                                    ->orderByDesc('cnt')
                                    ->first();

        // Profile completeness score (0–100)
        $profileFields = ['name', 'address', 'city', 'phone', 'description', 'specialization'];
        $profileScore  = (int) round(
            collect($profileFields)->filter(fn($f) => !empty($garage->$f))->count() / count($profileFields) * 100
        );

        // Returning customer IDs — users who have 2+ bookings with this garage
        $returningUserIds = $bookings
            ->groupBy(fn($b) => optional($b->vehicle)->user_id)
            ->filter(fn($g, $k) => $k !== null && $g->count() > 1)
            ->keys()
            ->toArray();

        // Customer history map: user_id → ['count' => n, 'services' => [...last 3 service types]]
        $customerHistory = $bookings
            ->filter(fn($b) => optional($b->vehicle)->user_id !== null)
            ->groupBy(fn($b) => $b->vehicle->user_id)
            ->map(fn($group) => [
                'count'    => $group->count(),
                'services' => $group->sortByDesc('booking_date')->take(3)->pluck('service_type')->toArray(),
            ]);

        // Average rating & count
        $avgRating    = round($garage->ratings()->avg('rating') ?? 0, 1);
        $totalRatings = $garage->ratings()->count();

        // Chart: bookings by status
        $bookingsByStatus = [
            'pending'   => $bookings->where('status', 'pending')->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
        ];

        // Chart: top 5 service types by booking count
        $topServices = $garage->bookings()
            ->selectRaw('service_type, COUNT(*) as cnt')
            ->groupBy('service_type')
            ->orderByDesc('cnt')
            ->take(5)
            ->get();

        return view('garages.dashboard', compact(
            'garage', 'bookings', 'todayBookings',
            'revenue', 'completedJobsTotal',
            'revenueThisMonth', 'completedJobsThisMonth',
            'chartData', 'avgInvoice', 'mostBookedService',
            'profileScore', 'returningUserIds',
            'customerHistory', 'avgRating', 'totalRatings',
            'bookingsByStatus', 'topServices'
        ));
    }
}