<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserActivity;
use App\Models\UserApps;
use \Carbon\Carbon;
use Throwable;

class CheckoutUserActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkout:activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checkout User Activity';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $activities = UserActivity::whereDate('checkin_at', '<', Carbon::now()->subHours(24)->toDateTimeString())->where('checkout_at', null)->get();
            foreach ($activities as $key => $activity) {
                $activity->update([
                    'checkout_at' => Carbon::now()
                ]);
                if($activity){
                    $lastActivity = UserActivity::whereDate('checkin_at', '>=', Carbon::now()->subHours(24)->toDateTimeString())->where('checkout_at', null)->where('user_id', $activity->user_id)->first();
                    if(empty($lastActivity)){
                        $activity->user->update([
                            'isCheckin' => false,
                        ]);
                    }
                }
            }

            $users = UserApps::with(['ticket'])
            ->whereHas('ticket', function ($query) {
                $queryTmp = $query->whereDate('selected_date', '!=', Carbon::today());
                return $queryTmp;
            })
            ->orWhereHas('event')
            ->get();

            foreach ($users as $key => $user) {
                $user->update([
                    'active_event_id' => null,
                    'active_ticket_order_id' => null,
                ]);
            }
            return \Log::info('successfully added');
    
        } catch (\Throwable $e) {
           return \Log::info($e->getMessage());
        }
    }
}
