<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CarPark;
use App\Models\ParkingRecords;
use App\Models\ParkingSlots;
use App\Models\User;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;

class CarParkController extends Controller
{
    //WEB
    public function index()
    {
        $users = User::where('usertype', 2)->get();
        return view('pages.parkings', compact('users'));
    }

    public function enroll(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2',
            'mobile' => 'required|string|min:10|max:11',
            'address' => 'required|string|min:2',
            'lng' => 'required|numeric',
            'ltd' => 'required|numeric',
            'user' => 'required|numeric',
            'status' => 'required|numeric',
            'isnew' => 'required|numeric',
            'record' => 'nullable|numeric',
        ]);

        if ($request->isnew == 1) {
            CarPark::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'lng' => $request->lng,
                'ltd' => $request->ltd,
                'status' => $request->status,
                'user' => $request->user,
            ]);
        } else {
            CarPark::where('id', $request->record)->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'lng' => $request->lng,
                'ltd' => $request->ltd,
                'status' => $request->status,
                'user' => $request->user
            ]);
        }

        return redirect()->back()->with(['code' => 1, 'color' => 'success', 'msg' => 'Successfully ' . (($request->isnew == 1) ? 'Registered' : 'Updated')]);
    }

    public function enroll_slots(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2',
            'no' => 'required|numeric',
            'price' => 'required|numeric',
            'status' => 'required|numeric',
            'isnew' => 'required|numeric',
            'carpark' => 'nullable|numeric',
        ]);

        if ($request->isnew == 1) {
            ParkingSlots::create([
                'name' => $request->name,
                'no' => $request->no,
                'charge_per_hour' => $request->price,
                'status' => $request->status,
                'carpark' => $request->carpark,
            ]);
            CarPark::where('id',$request->carpark)->increment('slots');
        } else {
            ParkingSlots::where('id', $request->record)->update([
                'name' => $request->name,
                'no' => $request->no,
                'charge_per_hour' => $request->price,
                'status' => $request->status,
                'carpark' => $request->carpark,
            ]);
        }

        return 1;
    }

    public function getSlotOne(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:parking_slots,id'
        ]);
        return ParkingSlots::where('id', $request->id)->first();
    }


    public function deleteOne(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:car_parks,id'
        ]);
        CarPark::where('id', $request->id)->update(['status' => 3]);

        return redirect()->back()->with(['code' => 1, 'color' => 'danger', 'msg' => 'Successfully Removed']);
    }

    public function deleteSlotOne(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:parking_slots,id'
        ]);
        ParkingSlots::where('id', $request->id)->update(['status' => 3]);
        $record=ParkingSlots::where('id', $request->id)->first();
        CarPark::where('id',$record->carpark)->decrement('slots');

        return redirect()->back()->with(['code' => 1, 'color' => 'danger', 'msg' => 'Successfully Removed']);
    }

    public function list(Request $request)
    {
        return Laratables::recordsOf(CarPark::class);
    }

    public function getOne(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:car_parks,id'
        ]);
        return CarPark::where('id', $request->id)->first();
    }

    public function getSlots(Request $request)
    {
        $id = $request->id;
        $slots = ParkingSlots::where('status', 1)->where('carpark', $id)->get();
        return view('pages.sub-contents.parkings-slots-modal', compact('slots', 'id'));
    }
    //WEB

    //API
    public function getCarParkings(Request $request)
    {
        $query = CarPark::where('status', 1);
        if ($request->has("search") && $request->filled("search")) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $query->orderBy('feedbacks', 'DESC')->orderBy('slots', 'DESC');

        return $query->get();
    }

    public function getCarParkingSlots(Request $request)
    {
        $request->validate(['carpark' => 'required|numeric|exists:car_parks,id']);
        return ParkingSlots::where('carpark', $request->carpark)->get();
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate(['carpark' => 'required|numeric|exists:car_parks,id']);
        return ParkingSlots::where('carpark', $request->carpark)->where('available', 1)->get();
    }


    public function getCarParkingWithSlots(Request $request)
    {
        $request->validate(['carpark' => 'required|numeric|exists:car_parks,id']);
        return CarPark::where('id', $request->carpark)->with('slots_data')->first();
    }

    public function getUserParkingInformations(Request $request)
    {
        return ParkingRecords::where('status', 1)->where('user', $request->user)->with('slotdata')->with('parkingdata')->first();
    }

    public function getUserParkingHistory(Request $request)
    {
        return ParkingRecords::where('status', "!=", 3)->whereNotNull('end')->where('user', $request->user)->with('slotdata')->with('parkingdata')->get();
    }
    //API
}
