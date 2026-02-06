@extends('theme.main')

@section('pagecss')
<!-- Add any specific CSS for the show page here -->
@endsection

@section('content')
    <div class="wrapper p-5">
        
        <div class="row">
            <div class="col-md-6">
                <strong class="text-uppercase">Issuance Transaction Details</strong>
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
                        <span>Issuance Transaction Details</span>
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
                                <label class="col-sm-3 col-form-label">RIS #</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $transaction->ris_no }}</p>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-sm-3 col-form-label">Technical Report #</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $transaction->technical_report_no }}</p>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Receiving Agency</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        @php
                                            $receiverNames = [];
                                            foreach ($receivers as $receiver) {
                                                if (in_array($receiver->id, json_decode($transaction->receiver_id ?? '[]', true))) {
                                                    $receiverNames[] = $receiver->name;
                                                }
                                            }
                                        @endphp

                                        {{ implode(', ', $receiverNames) }}
                                    </p>
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Receiver</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        {{ $transaction->actual_receiver }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Truck Plate #</label>
                                <div class="col-sm-9">
                                    <select class="vehicle_id select-tags form-select {{ $errors->has('vehicle_id') ? 'is-invalid' : '' }}" multiple style="width:100%;" required disabled>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ in_array($vehicle->id, array_map('intval', (array) (json_decode($transaction->vehicle_id, true) ?? $transaction->vehicle_id))) ? 'selected' : '' }}>{{ $vehicle->plate_no }}</option>
                                            {{-- <option value="{{ $vehicle->id }}" {{ in_array($vehicle->id, json_decode($transaction->vehicle_id ?? '[]', true)) ? 'selected' : '' }}>{{ $vehicle->plate_no }}</option> --}}
                                        @endforeach
                                    </select>
                                    <select id="vehicle_id" name="vehicle_id[]" class="vehicle_id form-select {{ $errors->has('vehicle_id') ? 'is-invalid' : '' }}" multiple aria-hidden="true" style="width:100%;" required hidden>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ in_array($vehicle->id, array_map('intval', (array) (json_decode($transaction->vehicle_id, true) ?? $transaction->vehicle_id))) ? 'selected' : '' }}>{{ $vehicle->plate_no }}</option>
                                            {{-- <option value="{{ $vehicle->id }}" {{ in_array($vehicle->id, json_decode($transaction->vehicle_id ?? '[]', true)) ? 'selected' : '' }}>{{ $vehicle->plate_no }}</option> --}}
                                        @endforeach
                                    </select>
                                    @error('vehicle_id')
                                        <small class="text-danger">The truck plate # is required</small>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Truck Plate #</label>
                                <div class="col-sm-9">
                                    <select class="vehicle_id select-tags form-select {{ $errors->has('vehicle_id') ? 'is-invalid' : '' }}" multiple style="width:100%;" required disabled>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ in_array($vehicle->id, json_decode($transaction->vehicle_id ?? '[]', true)) ? 'selected' : '' }}>{{ $vehicle->plate_no }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_id')
                                        <small class="text-danger">The truck plate # is required</small>
                                    @enderror
                                </div>
                            </div> --}}
                            {{-- <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Truck Plate #</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        {{ $transaction->vehicle->plate_no ?? '' }}
                                    </p>
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Date Received</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ $transaction->date_released }}</p>
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
                                            <th width="10%">ID</th>
                                            <th width="15%">SKU</th>
                                            <th width="20%">Item</th>
                                            <th width="10%">Unit</th>
                                            <th width="15%" class="text-end">Cost</th>
                                            <th width="10%" class="text-end">Qty</th>
                                            <th width="10%" class="text-end">Subtotal</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($issuance_total_quantity = 0)
                                        @php($issuance_total_cost = 0)
                                        @php($issuance_total_price = 0)

                                        @foreach($issuance_details as $issuance_detail)

                                            @php($issuance_total_quantity += $issuance_detail->quantity)
                                            @php($issuance_total_cost += $issuance_detail->cost * $issuance_detail->quantity)
                                            @php($issuance_total_price += $issuance_detail->price * $issuance_detail->quantity)

                                    
                                            <tr>
                                                <td>
                                                    {{ $issuance_detail->item_id }}
                                                    <input name="item_id[]" type="text" value="{{ $issuance_detail->item_id }}" hidden>
                                                </td>
                                                <td>
                                                    {{ $issuance_detail->sku }}
                                                    <input name="sku[]" type="text" value="{{ $issuance_detail->sku }}" hidden>
                                                </td>
                                                <td>
                                                    {{ $issuance_detail->item()->withTrashed()->first()->name }}
                                                    <input name="sku[]" type="text" value="{{ $issuance_detail->item()->withTrashed()->first()->name }}" hidden>
                                                </td>
                                                <td>
                                                    {{ $issuance_detail->item->type->name }}
                                                </td>
                                                <td class="text-end">
                                                    <input name="price[]" type="number" step="0.1" class="text-end border-0 price-info" value="{{ number_format($issuance_detail->item->price ?? 0, 2) }}" readonly>
                                                    <input name="cost[]" type="number" step="0.1" class="form-control form-control-sm bg-light" value="{{ number_format($issuance_detail->item->price ?? 0, 2) }}" readonly hidden>
                                                </td>
                                                <td>
                                                    <input name="quantity[]" 
                                                        type="number" step="1" value="{{ $issuance_detail->quantity }}" min="1" max="{{ $issuance_detail->item->inventory }}" onclick="this.select()" class="text-end border-0"
                                                        style="width:100%;"
                                                        oninput="
                                                            this.value = this.value < 1 ? 1 : Math.min(this.value, this.max);
                                                            var price = {{ $issuance_detail->item->price }};  // price as a number, no .toFixed(2)
                                                            var quantity = parseFloat(this.value); 
                                                            var subtotal = (price * quantity);  // Perform calculation without rounding here
                                                            this.closest('tr').querySelector('.subtotal').value = subtotal.toFixed(2); 
                                                        " 
                                                        readonly
                                                    >
                                                </td>
                                                <td class="text-end">
                                                    <input class="subtotal text-end border-0" name="subtotal[]" type="text" value="{{ number_format(($issuance_detail->item->price ?? 0) * ($issuance_detail->quantity ?? 1), 2, '.', ',') }}" readonly>
                                                </td>
                                                <td>&nbsp;</td>
                                            </tr>

                                            {{-- <tr>
                                                <td>
                                                    {{ $issuance_detail->sku }}
                                                </td>
                                                <td>
                                                    {{ $issuance_detail->book->name }}
                                                </td>
                                                <td class="text-end">
                                                    {{ $issuance_detail->quantity }}
                                                </td>

                                                @if($transaction->is_for_sale == 0)
                                                    <td class="text-end">
                                                        ₱{{ number_format($issuance_detail->cost,2) }}
                                                    </td>
                                                    <td class="text-end">
                                                        ₱{{ number_format(($issuance_detail->cost * $issuance_detail->quantity),2) }}
                                                    </td>
                                                @else
                                                    <td class="text-end">
                                                        ₱{{ number_format($issuance_detail->price,2) }}
                                                    </td>
                                                    <td class="text-end">
                                                        ₱{{ number_format(($issuance_detail->price * $issuance_detail->quantity),2) }}
                                                    </td>
                                                @endif
                                            </tr> --}}
                                        @endforeach          
                                        
                                        <div id="computation-row">
                                            <tr style="pointer-events: none;">
                                                <td colspan="5"><input name="item_id[]" type="text" value="0" hidden></td>
                                                <td class="text-end">Net Total</td>
                                                <td class="text-end"><input type="text" name="net_total" value="{{ number_format($transaction->net_total, 2, '.', ',') }}" class="text-end border-0" readonly></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr style="pointer-events: auto;" class="table-borderless" hidden>
                                                <td colspan="5"><input name="item_id[]" type="text" value="0" hidden></td>
                                                <td class="text-end">VAT (%)</td>
                                                <td class="text-end"><input type="number" name="vat" value="12" class="text-end border-0" step="1" min="0" onclick="this.select()" oninput="this.value = this.value < 0 ? 0 : this.value;" ></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr style="pointer-events: none;" hidden>
                                                <td colspan="5"><input name="item_id[]" type="text" value="0" hidden></td>
                                                <td class="text-end">Grand Total</td>
                                                <td class="text-end"><input type="number" name="grand_total" value="0.00" class="text-end border-0 fw-bold" style="font-size:17px;" readonly></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </div>       

                                        {{-- <tr>
                                            <td colspan="2"><strong>Total</strong></td>
                                            <td class="text-end text-primary"><strong>{{ $issuance_total_quantity }}</strong></td>
                                            <td>&nbsp;</td>
                                            <td class="text-end text-primary"><strong>₱{{ number_format($transaction->is_for_sale == 0 ? $issuance_total_cost : $issuance_total_price, 2) }}</strong></td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- <a href="javascript:void(0);" onclick="window.history.back();" class="btn btn-secondary mt-4">Back</a> --}}
                        <a href="javascript:void(0);" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ route('issuance.transactions.index') }}'; }" class="btn btn-secondary mt-4">Back</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
	<script>
		jQuery(document).ready( function(){
			// select Tags
			jQuery(".select-tags").select2({
				tags: true
			});
		});
	</script>
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
