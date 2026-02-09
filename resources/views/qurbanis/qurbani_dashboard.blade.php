@extends('layouts.app')

@section('content')
<div class="container">
     <div class="row  justify-content-center">
        <div class="col-md-2">
            <div class="card">
                    <div class="card-header">{{ __('Total Receipts') }}</div>
                    <div class="card-body">
                        {{ $receiptcount }}
                    </div>
                </div>
                </div>
                <div class="col-md-2">
                <div class="card">
                    <div class="card-header">{{ __('Total Hisse') }} <strong>{{ $qurbanihisse }}</strong></div>
                    <div class="card-body">
                        @forelse($dayWiseHisseCounts as $day => $count)
                        <p><strong>Day {{ $day }}</strong>: {{ $count }}</p>
                        @empty
                        <p>N/A</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card">
                    <div class="card-header">{{ __('Cash Collection') }}</div>
                    <div class="card-body">
                          Rs. {{ number_format($cashCollection, 2) }} ({{ $cashReceipts}})
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="card-header">{{ __('Online Collection') }}</div>
                    <div class="card-body">
                              Rs. {{ number_format($onlineCollection, 2) }} ({{ $onlineReceipts}})
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="card-header">{{ __('Qurbani Collection') }}</div>
                    <div class="card-body">
                            Rs. {{ number_format($totalQurbaniCollection, 2)}}
                    </div>
                </div>
            </div>
        </div>
        <br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <tr>
                            <th>User Name</th>
                            <th>Receipts</th>
                            <th>Cash Amount</th>
                            <th>Online Amount</th>
                            <th>Hisse</th>
                            <th>Total Amount</th>
                        </tr>
                        @foreach ($usersWithQurbaniCount as $user)
                        @php
                        $userReceipts = App\Models\Qurbani::where('user_id', $user->id)->get();
                        $receiptCount = $userReceipts->count();
                        $cashAmount = $userReceipts->where('payment_type', 'Cash')->sum('total_amount');
                        $cashReceipts = $userReceipts->where('payment_type', 'Cash')->count();
                        $onlineAmount = $userReceipts->where('payment_type', 'Online')->sum('total_amount');
                        $onlineReceipts = $userReceipts->where('payment_type', 'Online')->count();
                        $totalAmount = $cashAmount + $onlineAmount;
                        //$hisseCount = App\Models\QurbaniHisse::where('user_id', $user->id)->count();
                       $hisseCount = App\Models\QurbaniHisse::select('hissa')
                                    ->whereIn('qurbani_id', function($query) use ($user) {
                                        $query->select('id')
                                              ->from('qurbanis')
                                              ->where('user_id', $user->id);
                                    })
                                    ->count();
                            
                        @endphp
                        @if($receiptCount > 0 || $hisseCount > 0)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $receiptCount }}</td>
                            <td>Rs. {{ number_format($cashAmount, 2) }} ({{ $cashReceipts }})</td>
                            <td>Rs. {{ number_format($onlineAmount, 2) }} ({{ $onlineReceipts }})</td>
                            <td>{{ $hisseCount }}</td>
                            <td><strong>Rs. {{ number_format($totalAmount, 2) }}</strong></td>
                        </tr>
                        @endif
                        @endforeach

                    </table>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection


