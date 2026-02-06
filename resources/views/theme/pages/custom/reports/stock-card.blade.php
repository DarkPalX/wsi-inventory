@extends('theme.layouts.report')

@section('pagecss')
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">

            <div class="col-md-12 text-center">
                <h4 class="text-uppercase">{{ $page->name }}<br><small>{{ Setting::info()->company_name }}</small></h4>
            </div>
            
        </div>
        
        <div class="row mt-5 justify-content-center">
            <div class="col-md-12">
                
                
                <form id="select_item_form" class="d-flex justify-content-between align-items-end" action="{{ route('reports.stock-card') }}" method="get">
                    <div class="col-6">
                        <strong>Select an item</strong>
                        <select name="id" class="selectpicker border w-100 form-control" data-live-search="true" onchange="document.getElementById('select_item_form').submit()">
                            <option disabled selected>-- SELECT ITEM --</option>
                            @foreach($items as $selection)
                                <option value="{{ $selection->id }}" @if($item->id == $selection->id) selected @endif>{{ $selection->sku }}: {{ $selection->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                
                <div class="card">
                    <div class="card-header">Stock Card</div>

                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="10%">Item SKU</td>
                                <td width="1%">:</td>
                                <td>{{ $item->sku ?? 'Select a item first' }}</td>
                            </tr>
                            <tr>
                                <td width="10%">Title</td>
                                <td width="1%">:</td>
                                <td>{{ $item->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td width="10%">Inventory</td>
                                <td width="1%">:</td>
                                <td>{{ $item->Inventory }}</td>
                            </tr>
                            <tr>
                                <td width="10%">Cost</td>
                                <td width="1%">:</td>
                                <td>{{ $item->price }}</td>
                            </tr>
                            <tr>
                                <td width="10%">Total Cost</td>
                                <td width="1%">:</td>
                                <td>{{ number_format(($item->price * $item->Inventory),2) }}</td>
                            </tr>
                        </table>
                        
                        <div class="table-responsive mt-5">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Transaction ID</th>
                                        <th>Quantity</th>
                                        <th>Running Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stock_card as $entry)
                                        @php
                                            $trans_id = '';
                                            if($entry['type'] == 'Receiving'){
                                                $trans_id = '<a href="' . route('receiving.transactions.show', ['id' => $entry['transaction_id']]) . '">' . $entry['ref_no'] . '</a>';

                                            }
                                            if($entry['type'] == 'Issuance'){
                                                $trans_id = '<a href="' . route('issuance.transactions.show', ['id' => $entry['transaction_id']]) . '">' . $entry['ref_no'] . '</a>';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($entry['date'])->format('m/d/Y') }}</td>
                                            <td>{{ $entry['type'] }}</td>
                                            <td>{!! $trans_id !!}</td>
                                            <td>{{ $entry['quantity'] }}</td>
                                            <td>{{ $entry['running_balance'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-danger text-center">No transaction history</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- <a href="javascript:window.history.back()" class="btn btn-secondary mt-4">Back</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
@endsection
