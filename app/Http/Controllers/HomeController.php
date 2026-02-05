<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        return view('home');
    }

    public function select(Request $request)
    {
        $plan = Plan::findOrFail($request->plan_id);

        if ($plan->price == 0) {
            $subscription = Subscription::create([
                'user_id' => $request->user()->id,
                'plan_id' => $plan->id,
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addMonth(),
                'status' => 'active',
            ]);

            return response()->json([
                'message' => auto_trans('Plan activado correctamente'),
                'subscription' => $subscription
            ]);
        }

        $subscription = Subscription::create([
            'user_id' => $request->user()->id,
            'plan_id' => $plan->id,
            'starts_at' => null,
            'ends_at' => null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => auto_trans('Suscripción creada, pendiente de pago'),
            'subscription_id' => $subscription->id,
            'checkout_url' => route('checkout.show', $plan)
        ]);
    }


}
