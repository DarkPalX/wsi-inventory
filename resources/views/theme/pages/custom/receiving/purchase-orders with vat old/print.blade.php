<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Purchase Order</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 20px;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 10px;
            }
            th, td {
                border: 1px solid #000;
                padding: 4px 6px;
                font-size: 12px;
            }
            th {
                background-color: #f5f5b0;
                text-align: left;
            }
            .no-border td {
                border: none;
            }
            .section-title {
                font-weight: bold;
                margin-top: 15px;
                margin-bottom: 5px;
            }
            .text-right {
                text-align: right;
            }
            .text-center {
                text-align: center;
            }
            .highlight {
                background-color: #f5f5b0;
            }
            .small-text {
                font-size: 11px;
            }
        </style>
    </head>
    <body>

        <table class="no-border">
            <tr>
                <td>
                    <strong>WSI Inventory, INC</strong><br>
                    5F Aloba Bldg, 2100 Madre Ignacia St. Malate Manila 1004<br>
                    TEL: +63-2-8528-1093 / FAX: +63-2-5310-0817<br>
                    2182 A3 Daystar Industrial Park Brgy. Pulong Sta Cruz Sta. Rosa Laguna<br>
                    TEL: (049) 542-4670
                </td>
                <td style="border:1px solid #000; width: 350px;">
                    <strong>PURCHASE ORDER</strong><br>
                    <strong>PO No:</strong> {{ $purchase_order->ref_no }}<br>
                    <strong>Issued Date:</strong> {{ $purchase_order->posted_at }}
                </td>
            </tr>
        </table>

        <div class="section-title">(1) Vendor's Information</div>
        <table>
            <tr>
                <th width="10%">Company Name</th>
                <td width="40%">{{ $purchase_order->Supplier->name }}</td>
                <th width="10%">Check Payee</th>
                <td width="40%">{{ $purchase_order->Supplier->check_no }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td colspan="3">{{ $purchase_order->Supplier->address }}</td>
            </tr>
            <tr>
                <th>Contact PIC</th>
                <td></td>
                <th>TIN</th>
                <td>{{ $purchase_order->Supplier->tin_no }}</td>
            </tr>
            <tr>
                <th>TEL</th>
                <td>{{ $purchase_order->Supplier->telephone_no }} / {{ $purchase_order->Supplier->cellphone_no }}</td>
                <th>E-mail</th>
                <td>{{ $purchase_order->Supplier->email }}</td>
            </tr>
            <tr>
                <th>Name of Bank</th>
                <td>{{ $purchase_order->Supplier->bank_name }}</td>
                <th>Account No.</th>
                <td>{{ $purchase_order->Supplier->bank_account_no }}</td>
            </tr>
        </table>

        <div class="section-title">(2) Parts / Material / Service Information</div>
        <table>
            <tr>
                <th width="5%">No.</th>
                <th width="35%">Name Brand/Spec/Size/Color</th>
                <th width="15%">Purpose</th>
                <th width="10%">Quantity</th>
                <th width="10%">Unit</th>
                <th width="10%">Price</th>
                <th width="10%">Sub Total</th>
                <th width="15%">Remarks</th>
            </tr>

            @foreach($purchase_order_details as $purchase_order_detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $purchase_order_detail->item->name }}</td>
                    <td>{{ $purchase_order_detail->purpose }}</td>
                    <td>{{ $purchase_order_detail->quantity }}</td>
                    <td>{{ $purchase_order_detail->item->type->name }}</td>
                    <td class="text-right">{{ number_format($purchase_order_detail->price ?? 0, 2) }}</td>
                    <td class="text-right">{{ number_format(($purchase_order_detail->item->price ?? 0) * ($purchase_order_detail->quantity ?? 1), 2, '.', ',') }}</td>
                    <td>{{ $purchase_order_detail->remarks }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="6" class="text-right"><strong>Total PHP :</strong></td>
                <td class="text-right"></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right">Net:</td>
                <td class="text-right">{{ number_format($purchase_order->net_total, 2, '.', ',') }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right">12% VAT:</td>
                <td class="text-right">
                    @if($purchase_order_details->sum('vat') > 0)
                        {{ number_format(($purchase_order->net_total *.12), 2, '.', ',') }}
                    @else
                        0.00
                    @endif
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><strong>Grand Total PHP:</strong></td>
                <td class="text-right"><strong>{{ number_format(($purchase_order->grand_total), 2, '.', ',') }}</strong></td>
                <td></td>
            </tr>
        </table>

        <div class="section-title">(3) Terms & Conditions</div>
        <ol class="small-text">
            <li>Vendor agrees to sell the goods or services to WSI Inventory, INC</li>
            <li>Transfer method: Delivery by Vendor / Picking up by WSI</li>
            <li>VAT: Inclusive / Exclusive / Non-Vatable</li>
            <li>Payment Method: Bank Cheque / Cash / Bank Transfer</li>
            <li>Payment Timing: Collect (Credit Line) / Prepaid(COD) / Other</li>
            <li>Payment Term: 30 days</li>
            <li>Effectivity: 30 days from issued date</li>
            <li>PO number must be shown in all documents relating to this PO</li>
            <li>Billing invoice and/or Official Receipt under this PO must be issued to WSI Inventory, INC</li>
            <li>
                Billing invoice and/or Official Receipt to be full filled below information:
                <table>
                    <tr>
                        <th>Company Name</th>
                        <td>{{ $setting->company_name }}</td>
                        <th>TIN</th>
                        <td>{{ $setting->tin_no }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td colspan="3">{{ $setting->company_address }}</td>
                    </tr>
                    <tr>
                        <th>Business Style</th>
                        <td colspan="3">{{ $setting->company_name }}</td>
                    </tr>
                    <tr>
                        <th>12% VAT</th>
                        <td colspan="3">Indication of the amount of VAT Separately in the Invoice and/or Official Receipt</td>
                    </tr>
                </table>
            </li>
            <li>For PO related concern: Purchaser 0919-082-0389, Payment Concern: Accounting 0908-815-6483</li>
        </ol>

        <table class="no-border" style="margin-top: 30px;">
            <tr>
                <td>Requested by:</td>
                <td>Verified by:</td>
                <td>Prepared by:</td>
                <td>Checked by:</td>
                <td>Verified by:</td>
                <td>Approved by:</td>
            </tr>
            <tr>
                <td>{{ User::getName($purchase_order->created_by) }}</td>
                <td>{{ $setting->purchase_order_verifier1 }}</td>
                <td>{{ User::getName($purchase_order->created_by) }}</td>
                <td>{{ $setting->purchase_order_checker }}</td>
                <td>{{ $setting->purchase_order_verifier2 }}</td>
                <td>{{ User::getName($purchase_order->posted_by) }}</td>
            </tr>
        </table>

    </body>
    {{-- <script>
        window.onload = function () {
            window.print();

            window.onafterprint = () => {
                history.back();
            };
        };
    </script> --}}

</html>
