@extends('theme.main')
{{-- @extends('theme.layouts.report') --}}

@section('pagecss')
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">

            <div class="col-md-6">
                <h4 class="text-uppercase">{{ $page->name }}</h4>
            </div>
            
        </div>
        
        <div class="row mt-5 justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Stock Card</div>

                    <div class="card-body">

                        <table class="table table-borderless">
                            <tr>
                                <td width="10%">SKU</td>
                                <td width="1%">:</td>
                                <td>{{ $item->sku }}</td>
                            </tr>
                            <tr>
                                <td width="10%">Title</td>
                                <td width="1%">:</td>
                                <td>{{ $item->name }}<br><small>{{$item->subtitle}}</small></td>
                            </tr>
                            <tr>
                                <td width="10%">Inventory</td>
                                <td width="1%">:</td>
                                <td>{{ $item->Inventory }}</td>
                            </tr>
                        </table>

                        
                        <div class="table-responsive mt-5">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th align="center">Transaction ID</th>
                                        <th align="right">Quantity</th>
                                        <th align="right">Running Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @forelse($stock_card as $entry)
                                        @php
                                            $trans_id = '';
                                            if($entry['type'] == 'Receiving'){
                                                $trans_id = '<a href="' . route('receiving.transactions.show', ['id' => $entry['transaction_id']]) . '">' . $entry['ref_no']  . '</a>';

                                            }
                                            if($entry['type'] == 'Issuance'){
                                                $trans_id = '<a href="' . route('issuance.transactions.show', ['id' => $entry['transaction_id']]) . '">' . $entry['ref_no']  . '</a>';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($entry['date'])->format('m/d/Y') }}</td>
                                            <td>{{ $entry['type'] }}</td>
                                            <td align="center">{!! $trans_id !!}</td>
                                            <td align="right">{{ $entry['quantity'] }}</td>
                                            <td align="right">{{ $entry['running_balance'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-danger text-center">No transaction history</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <a href="javascript:window.history.back()" class="btn btn-secondary mt-4">Back</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('pagejs')
@endsection