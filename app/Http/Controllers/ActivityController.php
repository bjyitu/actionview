<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Project\Provider;
use DB;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project_key)
    {
        $time_points = [ 'just' => time(), 
                         '15m' => strtotime('-15 minute'), 
                         '30m' => strtotime('-30 minute'), 
                         '1h' => strtotime('-1 hour'), 
                         '3h' => strtotime('-3 hour') , 
                         '5h' => strtotime('-5 hour'), 
                         '7h' => strtotime('-7 hour'), 
                         '9h' => strtotime('-9 hour'), 
                         '1d' => strtotime('-1 day'), 
                         '2d' => strtotime('-2 day'), 
                         '3d' => strtotime('-3 day'), 
                         '4d' => strtotime('-4 day'), 
                         '5d' => strtotime('-5 day'), 
                         '6d' => strtotime('-6 day'), 
                         '1w' => strtotime('-1 week'), 
                         '2w' => strtotime('-2 week'), 
                         '3w' => strtotime('-3 week'), 
                         '4w' => strtotime('-4 week'), 
                         '1m' => strtotime('-1 month'), 
                         '3m' => strtotime('-3 month'), 
                         '5m' => strtotime('-5 month'), 
                         '7m' => strtotime('-7 month'), 
                         '9m' => strtotime('-9 month'), 
                         '11m' => strtotime('-11 month'), 
                         '1y' => strtotime('-1 year'), 
                         '2y' => strtotime('-2 year'), 
                       ];

        $query = DB::collection('activity_' . $project_key);
        $query->orderBy('created_at', 'desc');
        $activities = $query->get();
        foreach ($activities as $key => $activity)
        {
            if (isset($activity['issue_id']))
            {
                $issue = DB::collection('issue_' . $project_key)->where('_id', $activity['issue_id'])->first();
                $activities[$key]['issue'] = [ 'id' => $issue['_id'], 'name' => isset($issue['title']) ? $issue['title'] : '' ];
            }

            $pre_timepoint_key = 'just';
            foreach ($time_points as $tkey => $time_point)
            {
                if ($activity['created_at'] > $time_point) 
                {
                    break;
                }
                $pre_timepoint_key = $tkey;
            }

            $activities[$key]['ago_key'] = $pre_timepoint_key;

        }
        return Response()->json(['ecode' => 0, 'data' => $activities]);
    }
}