@extends('theme.main')

@section('pagecss')
<!-- Add any specific CSS for the show page here -->
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">
            <div class="col-md-6">
                <strong class="text-uppercase">Receiving Transaction Details</strong>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('receiving.transactions.index') }}">Transactions</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mt-5 justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Receiving Transaction Details</span>
                        <div class="card-tools">
                            <a href="javascript:void(0)" class="text-decoration-none" onclick="print_area('print-area')">
                                <i class="fa fa-print"></i> Print
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="print-area">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <p class="form-control-plaintext">
                                        <strong><small class="rounded text-white {{ $transaction->status == 'SAVED' ? 'bg-warning' : ($transaction->status == 'CANCELLED' ? 'bg-danger' : 'bg-success') }} p-1">{{ $transaction->status }}</small></strong>
                                        <small class="text-secondary" {{ $transaction->status == 'SAVED' ? 'hidden' : '' }}> | 
                                            @if($transaction->status == 'POSTED')
                                                {{ User::getName($transaction->posted_by) }} ({{ $transaction->posted_at }})
                                            @else
                                                {{ User::getName($transaction->cancelled_by) }} ({{ $transaction->cancelled_at }})
                                            @endif
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-sm-3 col-form-label">Reference #</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $transaction->ref_no }}</p>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-sm-3 col-form-label">P.O. #</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $transaction->po_number ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-sm-3 col-form-label">S.I. #</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $transaction->si_number ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Supplier</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        @php
                                            $supplierNames = [];
                                            foreach ($suppliers as $supplier) {
                                                if (in_array($supplier->id, json_decode($transaction->supplier_id ?? '[]', true))) {
                                                    $supplierNames[] = $supplier->name;
                                                }
                                            }
                                        @endphp

                                        {{ implode(', ', $supplierNames) }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Date Received</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $transaction->date_received }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Created by</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ User::getName($transaction->created_by) }} <small class="text-secondary">({{ $transaction->created_at }})</small></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Remarks</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $transaction->remarks }}</p>
                                </div>
                            </div>

                            <div class="divider text-uppercase divider-center"><small>Item Details</small></div>

                            <div class="table-responsive-faker">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th width="10%">SKU</th>
                                            <th width="20%">Item</th>
                                            <th width="10%">Unit</th>
                                            <th width="10%" class="text-end">Price</th>
                                            <th width="10%" class="text-end" {{ $is_vatable ? '' : 'hidden' }}>Vat({{ env('VAT_RATE') }}%)</th>
                                            <th width="10%" class="text-end" {{ $is_vatable ? '' : 'hidden' }}>Net Price</th>
                                            <th width="10%" class="text-end">Qty Ordered</th>
                                            <th width="10%" class="text-end">Qty Received</th>
                                            <th width="10%" class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($receiving_total_ordered = 0)
                                        @php($receiving_total_quantity = 0)
                                        @php($receiving_net_total = 0)

                                        @foreach($receiving_details as $receiving_detail)

                                            @php($receiving_total_ordered += $receiving_detail->order)
                                            @php($receiving_total_quantity += $receiving_detail->quantity)
                                            @php($receiving_net_total = $receiving_net_total  + ($receiving_detail->price * $receiving_detail->quantity) ?? 0)

                                            {{-- <tr>
                                                <td>{{ $receiving_detail->item_id }}</td>
                                                <td>{{ $receiving_detail->sku }}</td>
                                                <td>{{ $receiving_detail->item->name }}</td>
                                                <td class="text-end">{{ $receiving_detail->order }}</td>
                                                <td class="text-end">{{ $receiving_detail->quantity }}</td>
                                                <td class="text-end">{{ number_format($receiving_detail->item->price ?? 0, 2) }}</td>
                                            </tr> --}}
                                            
                                            <tr>
                                                <td>{{ $receiving_detail->item_id }} <input name="item_id[]" type="text" value="{{ $receiving_detail->item_id }}" hidden></td>
                                                <td>{{ $receiving_detail->sku }} <input name="sku[]" type="text" value="{{ $receiving_detail->sku }}" hidden></td>
                                                <td>{{ $receiving_detail->item()->withTrashed()->first()->name }}</td>
                                                <td>{{ optional(optional($receiving_detail->item)->type)->name ?? 'N/A' }}</td>
                                                <td class="text-end">{{ number_format($receiving_detail->price, 2) }}</td>
                                                <td class="text-end" {{ $is_vatable ? '' : 'hidden' }}>{{ number_format($receiving_detail->vat, 2) }}</td>
                                                <td class="text-end" {{ $is_vatable ? '' : 'hidden' }}>{{ number_format($receiving_detail->vat_inclusive_price, 2) }}</td>
                                                <td class="text-end">{{ $receiving_detail->order }}</td>
                                                <td class="text-end">{{ $receiving_detail->quantity }}</td>
                                                <td class="text-end">{{ number_format($receiving_detail->price * $receiving_detail->quantity, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="{{ $is_vatable ? '7' : '5' }}" class="text-end"><strong>Total</strong></td>
                                            <td class="text-end text-primary"><strong>{{ $receiving_total_ordered }}</strong></td>
                                            <td class="text-end text-primary"><strong>{{ $receiving_total_quantity }}</strong></td>
                                            <td class="text-end text-primary"><strong>{{ number_format($receiving_net_total, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="javascript:void(0);" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ route('receiving.transactions.index') }}'; }" class="btn btn-secondary mt-4">Back</a>
                        {{-- <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-secondary mt-4">Back</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script>
        function print_area(area) {
            var printContents = document.querySelector('.' + area).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = '<div class="' + area + '">' + printContents + '</div>';

            window.print();

            document.body.innerHTML = originalContents;
            window.location.reload(); // Optionally reload the page to restore the original state
        }
    </script>
@endsection
