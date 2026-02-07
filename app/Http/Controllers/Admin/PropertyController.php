<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Furnishing;
use App\Enums\GenderPreference;
use App\Enums\RentType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePropertyRequest;
use App\Http\Requests\Admin\UpdatePropertyRequest;
use App\Models\Property;
use App\Services\Admin\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    private  $propertyService;
    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }
    public function index(Request $request)
    {
        $query = Property::latest();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $properties = $query->paginate(10);

        return view('admin.properties.index', compact('properties'));
    }



    public function create()
    {
        $rentTypes = RentType::options();
        $genderTypes = GenderPreference::options();
        $furnishingTypes = Furnishing::options();
        $statusTypes = Status::options();
        return view(
            'admin.properties.create',
            compact('rentTypes', 'genderTypes', 'furnishingTypes', 'statusTypes')
        );
    }
    /*  */
    public function store(StorePropertyRequest $request)
    {
        $this->propertyService->storeProperty($request);
        return redirect()->route('admin.properties.index')
            ->with('success', 'Property created successfully.');
    }

    public function show(string $id)
    {
        $property = $this->propertyService->getPropertyById($id);
        return view('AdminPanel.property.show_property', compact('property'));
    }

    public function edit(string $id)
    {
        $property = $this->propertyService->getPropertyById($id);
        $rentTypes = RentType::options();
        $genderTypes = GenderPreference::options();
        $furnishingTypes = Furnishing::options();
        $statusTypes = Status::options();
        return view(
            'admin.properties.edit',
            compact('rentTypes', 'genderTypes', 'furnishingTypes', 'statusTypes', 'property')
        );
    }

    public function update(UpdatePropertyRequest $request, string $id)
    {
        $this->propertyService->updateProperty($request, $id);
        return redirect()->route('admin.properties.index')
            ->with('success', 'Property updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->propertyService->destroyByID($id);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}
