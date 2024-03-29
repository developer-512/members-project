<?php

namespace App\Http\Controllers\Admin;

use App\Models\Member;
use Carbon\Carbon;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
{
    public function index()
    {
        $members_birthday_this_month=Member::whereMonth('date_of_birth', Carbon::now()->format('m'))->get();

        $settings1 = [
            'chart_title'           => 'Member Email Verified',
            'chart_type'            => 'bar',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Member',
            'group_by_field'        => 'email_verified_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'email_verified_at',
            'filter_days'           => '14',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-6',
            'entries_number'        => '30',
            'translation_key'       => 'member',
        ];

        $chart1 = new LaravelChart($settings1);

        $settings2 = [
            'chart_title'           => 'Members Registered',
            'chart_type'            => 'bar',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Member',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_days'           => '30',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-6',
            'entries_number'        => '30',
            'translation_key'       => 'member',
        ];

        $chart2 = new LaravelChart($settings2);

        return view('home', compact('chart1', 'chart2','members_birthday_this_month'));
    }
}
