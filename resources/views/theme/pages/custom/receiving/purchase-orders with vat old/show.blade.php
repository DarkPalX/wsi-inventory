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
                        <li class="breadcrumb-item"><a href="{{ route('receiving.purchase-orders.index') }}">Transactions</a></li>
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
                            <a href="{{ route('receiving.purchase-orders.print', ['id' => $purchase_order->id]) }}" class="text-decoration-none">
                                <i class="fa fa-print"></i> Print
                            </a>
                            {{-- <a href="javascript:void(0)" class="text-decoration-none" onclick="print_area('print-area')">
                                <i class="fa fa-print"></i> Print
                            </a> --}}
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="print-area">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <p class="form-control-plaintext">
                                        <strong><small class="rounded text-white {{ $purchase_order->status == 'SAVED' ? 'bg-warning' : ($purchase_order->status == 'CANCELLED' ? 'bg-danger' : 'bg-success') }} p-1">{{ $purchase_order->status }}</small></strong>
                                        <small class="text-secondary" {{ $purchase_order->status == 'SAVED' ? 'hidden' : '' }}> | 
                                            @if($purchase_order->status == 'POSTED')
                                                {{ User::getName($purchase_order->posted_by) }} ({{ $purchase_order->posted_at }})
                                            @else
                                                {{ User::getName($purchase_order->cancelled_by) }} ({{ $purchase_order->cancelled_at }})
                                            @endif
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-sm-3 col-form-label">Reference #</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $purchase_order->ref_no }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Supplier</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        @php
                                            $supplierNames = [];
                                            foreach ($suppliers as $supplier) {
                                                if (in_array($supplier->id, json_decode($purchase_order->supplier_id ?? '[]', true))) {
                                                    $supplierNames[] = $supplier->name;
                                                }
                                            }
                                        @endphp

                                        {{ implode(', ', $supplierNames) }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Date Ordered</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $purchase_order->date_ordered }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Created by</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ User::getName($purchase_order->created_by) }} <small class="text-secondary">({{ $purchase_order->created_at }})</small></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Remarks</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $purchase_order->remarks }}</p>
                                </div>
                            </div>

                            <div class="divider text-uppercase divider-center"><small>Item Details</small></div>

                            <div class="table-responsive-faker">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="10%">ID</th>
                                            <th width="15%">SKU</th>
                                            <th width="20%">Item</th>
                                            <th width="10%">Unit</th>
                                            <th width="15%">Price</th>
                                            <th width="10%" class="vat-col text-center" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>VAT({{ env('VAT_RATE') }}%)</th>
                                            <th width="15%">RIS#</th>
                                            <th width="10%">Requested Qty</th>
                                            <th width="15%" class="text-end">Subtotal</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach($purchase_order_details as $purchase_order_detail)
                                            <tr>
                                                <td>
                                                    {{ $purchase_order_detail->item_id }}
                                                    <input name="item_id[]" type="text" value="{{ $purchase_order_detail->item_id }}" hidden>
                                                </td>
                                                <td>
                                                    {{ $purchase_order_detail->sku }}
                                                    <input name="sku[]" type="text" value="{{ $purchase_order_detail->sku }}" hidden>
                                                </td>
                                                <td>
                                                    {{ $purchase_order_detail->item()->withTrashed()->first()->name }}
                                                    <input name="sku[]" type="text" value="{{ $purchase_order_detail->item()->withTrashed()->first()->name }}" hidden>
                                                </td>
                                                <td>
                                                    {{ $purchase_order_detail->item->type->name }}
                                                </td>
                                                <td>
                                                    {{ number_format($purchase_order_detail->price ?? 0, 2) }}
                                                </td>
                                                <td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>
                                                    <input type="hidden" name="vat_rate[]" class="vat-input" value="{{ $purchase_order_detail->vat }}">
                                                    <input type="number" name="vat_inclusive_price[]" 
                                                        class="vat-inclusive-price border-0 text-end"
                                                        value="{{ $purchase_order_detail->vat_inclusive_price }}" readonly style="width:80px; @if($purchase_order->supplier->is_vatable == 0) display:none; @endif">
                                                </td>
                                                <td>
                                                    {{ $purchase_order_detail->ris_no }}
                                                </td>
                                                <td>
                                                    <input name="quantity[]" class="border-0 text-end" type="number" value="{{ $purchase_order_detail->quantity }}" readonly>
                                                </td>
                                                <td class="text-end">
                                                    <input class="subtotal text-end border-0" name="subtotal[]" type="number" 
                                                    value="{{ 
                                                        $purchase_order->supplier->is_vatable == 0 ?
                                                            number_format(($purchase_order_detail->price ?? 0) * ($purchase_order_detail->quantity ?? 1), 2, '.', '') 
                                                        :
                                                            number_format(($purchase_order_detail->vat_inclusive_price ?? 0) * ($purchase_order_detail->quantity ?? 1), 2, '.', '') 
                                                    }}" 
                                                    readonly>
                                                </td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        @endforeach
                                            
                                        {{-- COMPUTATIONS --}}
                                            <div id="computation-row">
                                                <tr style="pointer-events: none;">
                                                    <td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
                                                    <td colspan="6"><input name="item_id[]" type="text" value="0" hidden></td>
                                                    <td class="text-end">Net Total</td>
                                                    <td class="text-end"><input type="number" name="net_total" value="{{ $purchase_order->net_total }}" class="text-end border-0" readonly></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr style="pointer-events: auto;" class="table-borderless" hidden>
                                                    <td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
                                                    <td colspan="6"><input name="item_id[]" type="text" value="0" hidden></td>
                                                    <td class="text-end">VAT (%)</td>
                                                    <td class="text-end"><input type="number" name="vat" value="{{ $purchase_order->vat > 0 ? $purchase_order->vat : 0 }}" class="text-end border-0" step="1" min="0" onclick="this.select()" oninput="this.value = this.value < 0 ? 0 : this.value;" readonly></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr style="pointer-events: none;">
                                                    <td class="vat-col" @if($purchase_order->supplier->is_vatable == 0) style="display:none;" @endif>&nbsp;</td>
                                                    <td colspan="6"><input name="item_id[]" type="text" value="0" hidden></td>
                                                    <td class="text-end">Grand Total</td>
                                                    <td class="text-end"><input type="number" name="grand_total" value="{{ $purchase_order->grand_total }}" class="text-end border-0 fw-bold" style="font-size:17px;" readonly></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </div>
                                    </tbody>
                                    {{-- <tbody>
                                        @php($purchase_order_total_quantity = 0)
                                        @php($purchase_order_price = 0)

                                        @foreach($purchase_order_details as $purchase_order_detail)

                                            @php($purchase_order_total_quantity += $purchase_order_detail->quantity)
                                            @php($purchase_order_price += $purchase_order_detail->item->price ?? 0)

                                            <tr>
                                                <td>{{ $purchase_order_detail->item_id }}</td>
                                                <td>{{ $purchase_order_detail->sku }}</td>
                                                <td>{{ $purchase_order_detail->item->name }}</td>
                                                <td class="text-end">{{ $purchase_order_detail->order }}</td>
                                                <td class="text-end">{{ $purchase_order_detail->quantity }}</td>
                                                <td class="text-end">{{ number_format($purchase_order_detail->item->price ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4"><strong>Total</strong></td>
                                            <td class="text-end text-primary"><strong>{{ $purchase_order_total_quantity }}</strong></td>
                                            <td class="text-end text-primary"><strong>{{ number_format($purchase_order_price, 2) }}</strong></td>
                                        </tr>
                                    </tbody> --}}
                                </table>
                            </div>
                        </div>
                        <a href="javascript:void(0);" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ route('receiving.purchase-orders.index') }}'; }" class="btn btn-secondary mt-4">Back</a>
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
