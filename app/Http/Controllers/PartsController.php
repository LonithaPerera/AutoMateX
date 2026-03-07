<?php
namespace App\Http\Controllers;
use App\Models\Part;
use Illuminate\Http\Request;
class PartsController extends Controller
{
    public function index(Request $request)
    {
        $parts = collect();
        $makes = Part::distinct()->pluck('vehicle_make');
        if ($request->filled('make') || $request->filled('search')) {
            $query = Part::query();
            if ($request->filled('make')) {
                $query->where('vehicle_make', $request->make);
            }
            if ($request->filled('model')) {
                $query->where('vehicle_model', $request->model);
            }
            if ($request->filled('category')) {
                $query->where('part_category', $request->category);
            }
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('part_name', 'like', '%' . $request->search . '%')
                      ->orWhere('oem_part_number', 'like', '%' . $request->search . '%')
                      ->orWhere('alternative_part_number', 'like', '%' . $request->search . '%');
                });
            }
            $parts = $query->get();
        }
        $categories = Part::distinct()->pluck('part_category');
        $models     = Part::distinct()->pluck('vehicle_model');
        return view('parts.index', compact(
            'parts', 'makes', 'models', 'categories'
        ));
    }
}