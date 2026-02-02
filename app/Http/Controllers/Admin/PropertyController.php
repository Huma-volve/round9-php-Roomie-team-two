<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Furnishing;
use App\Enums\GenderPreference;
use App\Enums\RentType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePropertyRequest;
use App\Http\Requests\Admin\UpdatePropertyRequest;
use App\Services\Admin\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    private  $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function index()
    {
        $properties = $this->propertyService->getAll();
        return view('AdminPanel.property.table_properties', compact('properties'));
    }

    public function create()
    {
        $rentTypes = RentType::options();
        $genderTypes = GenderPreference::options();
        $furnishingTypes = Furnishing::options();
        $statusTypes = Status::options();
        return view(
            'AdminPanel.property.create_property',
            compact('rentTypes', 'genderTypes', 'furnishingTypes', 'statusTypes')
        );
    }
    /*  */
    public function store(StorePropertyRequest $request)
    {
        $this->propertyService->storeProperty($request);
        return view('AdminPanel.property.table_properties');
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
            'AdminPanel.property.create_property',
            compact('rentTypes', 'genderTypes', 'furnishingTypes', 'statusTypes', 'property')
        );
    }

    public function update(UpdatePropertyRequest $request, string $id)
    {
        $this->propertyService->updateProperty($request, $id);
        return view('AdminPanel.property.table_properties');
    }

    public function destroy(string $id)
    {
        $this->propertyService->destroyByID($id);
        return view('AdminPanel.property.table_properties');
    }
}
