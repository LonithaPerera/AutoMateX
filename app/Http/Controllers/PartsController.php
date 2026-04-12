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

    public function create()
    {
        return view('parts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_name'               => 'required|string|max:200',
            'part_category'           => 'required|string|max:100',
            'oem_part_number'         => 'required|string|max:100',
            'alternative_part_number' => 'nullable|string|max:100',
            'brand'                   => 'nullable|string|max:100',
            'vehicle_make'            => 'required|string|max:100',
            'vehicle_model'           => 'required|string|max:100',
            'vehicle_year_from'       => 'required|integer|min:1900|max:2099',
            'vehicle_year_to'         => 'required|integer|min:1900|max:2099',
            'description'             => 'nullable|string|max:500',
        ]);

        Part::create($request->only([
            'part_name', 'part_category', 'oem_part_number', 'alternative_part_number',
            'brand', 'vehicle_make', 'vehicle_model', 'vehicle_year_from',
            'vehicle_year_to', 'description',
        ]));

        return redirect()->route('parts.index')->with('success', __('app.part_created'));
    }

    public function edit(Part $part)
    {
        return view('parts.edit', compact('part'));
    }

    public function update(Request $request, Part $part)
    {
        $request->validate([
            'part_name'               => 'required|string|max:200',
            'part_category'           => 'required|string|max:100',
            'oem_part_number'         => 'required|string|max:100',
            'alternative_part_number' => 'nullable|string|max:100',
            'brand'                   => 'nullable|string|max:100',
            'vehicle_make'            => 'required|string|max:100',
            'vehicle_model'           => 'required|string|max:100',
            'vehicle_year_from'       => 'required|integer|min:1900|max:2099',
            'vehicle_year_to'         => 'required|integer|min:1900|max:2099',
            'description'             => 'nullable|string|max:500',
        ]);

        $part->update($request->only([
            'part_name', 'part_category', 'oem_part_number', 'alternative_part_number',
            'brand', 'vehicle_make', 'vehicle_model', 'vehicle_year_from',
            'vehicle_year_to', 'description',
        ]));

        return redirect()->route('parts.index')->with('success', __('app.part_updated'));
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success', __('app.part_deleted'));
    }
}