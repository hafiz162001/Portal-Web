<?php

namespace App\Http\Controllers;

use \Carbon\Carbon;
use App\Models\Group;
use App\Models\TicketOrder;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\BeaconActivity;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ->whereRelation('user', 'user_category', '=', 'blocx')->orWhereRelation('user', 'user_category', '=', null)
        // $visitor = UserActivity::select('user_id')->distinct('user_id')->count();
        // $maleVisitor = UserActivity::select('user_apps.gender')->join('user_apps', 'user_activities.user_id', '=', 'user_apps.id')->where('gender', 'male')->count();
        // $femaleVisitor = UserActivity::select('user_apps.gender')->join('user_apps', 'user_activities.user_id', '=', 'user_apps.id')->where('gender', 'female')->count();
        // $nonGenderVisitor = UserActivity::select('user_apps.gender')->join('user_apps', 'user_activities.user_id', '=', 'user_apps.id')->where('gender', null)->count();

        $visitor = UserActivity::select('user_id')->whereDate('checkin_at', Carbon::today())->distinct('user_id')->count();
        $maleVisitor = UserActivity::select('user_apps.gender')->whereDate('checkin_at', Carbon::today())->join('user_apps', 'user_activities.user_id', '=', 'user_apps.id')->where('gender', 'male')->count();
        $femaleVisitor = UserActivity::select('user_apps.gender')->whereDate('checkin_at', Carbon::today())->join('user_apps', 'user_activities.user_id', '=', 'user_apps.id')->where('gender', 'female')->count();
        $nonGenderVisitor = UserActivity::select('user_apps.gender')->whereDate('checkin_at', Carbon::today())->join('user_apps', 'user_activities.user_id', '=', 'user_apps.id')->where('gender', null)->count();

        $totalGenderVisitor = $maleVisitor + $femaleVisitor + $nonGenderVisitor;

        $genderLabels = ["Pria", "Wanita", "-"];

        // check null
        if (empty($maleVisitor) && empty($femaleVisitor) && empty($nonGenderVisitor)) {
            $genderValues = [
                0, 0, 0
            ];

            $visitor = 0;
            $gender = 0;
            $todayIncome = 0;
            $displayedBeacon = 0;
            $displayedTodayVisitor = 0;
        } else {
            $genderValues = [
                round((($maleVisitor / $totalGenderVisitor) * 100), 2),
                round((($femaleVisitor / $totalGenderVisitor) * 100), 2),
                round((($nonGenderVisitor / $totalGenderVisitor) * 100), 2),
            ];

            $todayIncome = TicketOrder::whereDate('created_at', Carbon::today())->sum('total_price');

            $beaconVisitor = BeaconActivity::select('beacon_id')->with(['beacon'])->get()->groupBy('beacon.name')->map(function ($item, $key) {
                return count($item);
            })->sortDesc();
            $mostBeacon = $beaconVisitor->take(4);
            $otherBeacon = $beaconVisitor->skip(4)->sum();
            $mostBeacon = $mostBeacon->merge([
                'Other' => $otherBeacon
            ]);
            $displayedBeacon = [
                'label' => $mostBeacon->map(function ($val, $key) {
                    return $key;
                })->values(),
                'value' => $mostBeacon->map(function ($val, $key) {
                    return $val;
                })->values(),
            ];

            // $todayVisitor = UserActivity::distinct('user_id')->get()->sortBy("created_at");
            $todayVisitor = UserActivity::distinct('user_id')->whereDate('checkin_at', Carbon::today())->get()->sortBy("created_at");
            $todayVisitor = $todayVisitor->groupBy(function ($reg) {
                return date('H', strtotime($reg->created_at));
            });

            $displayedTodayVisitor = [
                'label' => $todayVisitor->map(function ($val, $key) {
                    return $key . ":00";
                })->values(),
                'value' => $todayVisitor->map(function ($val, $key) {
                    return count($val);
                })->values(),
            ];
        }
        $gender = [
            "labels" => $genderLabels,
            "values" => $genderValues,
        ];

        $groups = Group::select(['name', 'axis', 'yaxis'])->get();
        $heatMapArray = array();
        foreach ($groups as $key => $value) {
            $heatMapArray[] = "[" . $value->yaxis . "," . $value->axis . ",1000]";
        }

        $markerData = $groups->map(function ($item, $key) {
            return [
                (float)$item->yaxis, (float)$item->axis, $item->name
            ];
        });

        $heatMapData = implode(',', $heatMapArray);
        return view('home', compact('visitor', 'gender', 'todayIncome', 'displayedBeacon', 'displayedTodayVisitor', 'heatMapData', 'markerData'));
    }
}
