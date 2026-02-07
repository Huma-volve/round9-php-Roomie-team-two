<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoomBedType;
use App\Enums\RoomType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoomRequest;
use App\Models\Property;
use App\Models\Room;
use App\Services\Admin\RoomService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private $roomService;
    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function index(Request $request)
    {
        $query = Room::query()->latest();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('room_number', 'like', "%{$search}%");
        }

        $rooms = $query->paginate(10); 

        return view("admin.rooms.index", compact("rooms"));
    }

    public function create()
    {
        $roomType = RoomType::options();
        $statusType = Status::options();
        $roomBedType = RoomBedType::options();
        $properties = Property::select('id', 'title')->get();

        return view("admin.rooms.create", compact("roomType", "statusType", "roomBedType", 'properties'));
    }

    public function store(StoreRoomRequest $request)
    {
        $this->roomService->storeRoom($request);
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room created successfully.');
    }

    public function show(string $id)
    {
        $room = $this->roomService->getRoomById($id);
        return view("AdminPanel.room.show_room", compact("room"));
    }

    public function edit(string $id)
    {
        $roomType = RoomType::options();
        $statusType = Status::options();
        $roomBedType = RoomBedType::options();
        $properties = Property::select('id', 'title')->get();
        $room = $this->roomService->getRoomById($id);
        return view("admin.rooms.edit", compact("roomType", "statusType", "roomBedType", 'properties', 'room'));
    }

    public function update(Request $request, string $id)
    {
        $this->roomService->updateRoom($request, $id);
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->roomService->destroyRoom($id);
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}
