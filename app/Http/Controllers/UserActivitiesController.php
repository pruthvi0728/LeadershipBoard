<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserActivitiesController extends Controller
{
    public function index(Request $request)
    {
        $paginateNumber = 5;
        $sort = $request->input('sort', 'rank'); // Default to 'rank' if 'sort' is not provided
        if($sort == 'today') {
            $sort = 'today_rank';
        } elseif($sort == 'month') {
            $sort = 'monthly_rank';
        } elseif($sort == 'year') {
            $sort = 'yearly_rank';
        }

        if($request->has('user_id') && !empty($request->input('user_id'))) {
            // Get the data of the user with the given id first and then get the data of other users
            $userId = $request->input('user_id');
            $specificUser = User::with(['activityPoints'])->where('id', $userId)->first();
            $otherUsers = User::with(['activityPoints'])
                ->where('id', '!=', $userId)
                ->orderBy($sort)
                ->paginate($paginateNumber);

            $currentPage = $otherUsers->currentPage();
            $perPage = $otherUsers->perPage();
            $total = $otherUsers->total();

            if ($currentPage == 1) {
                $users = collect([$specificUser])->merge($otherUsers->getCollection());
            } else {
                $users = $otherUsers->getCollection();
            }

            $paginatedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
                $users,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // Get the data of all users
            $paginatedUsers = User::with(['activityPoints'])->orderBy($sort)->paginate($paginateNumber);
        }
        return view('user-activities.index', compact('paginatedUsers'));
    }
}
