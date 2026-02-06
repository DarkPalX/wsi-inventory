@extends('theme.main')

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
                    <div class="card-header">Item Details</div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="25%">Item ID</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->id }}</td>
                                    </tr>
                                    <tr>
                                        <td width="25%">SKU</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->sku }}</td>
                                    </tr>
                                    <tr>
                                        <td width="25%">Title</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="25%">Subtitle</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->subtitle }}</td>
                                    </tr>
                                    <tr>
                                        <td width="25%">Category</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->category->name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Authors</td>
                                        <td width="1%">:</td>
                                        <td>
                                            @foreach($item->authors as $author)
                                                {{ $author->name }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="25%">Inventory</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->Inventory }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="25%">Publisher</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->publisher->name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="25%">Publication Date</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->publication_date }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Edition</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->edition }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">ISBN</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->isbn }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Copyright</td>
                                        <td width="1%">:</td>
                                        <td>{{ $item->copyright }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive mt-5">
                                    <strong><small>Additional Infos</small></strong>
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Attribute</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Format</td>
                                                <td>{{ $item->format }}</td>
                                            </tr>
                                            <tr>
                                                <td>Paper Height ({{ env('UOM') }})</td>
                                                <td>{{ $item->paper_height }}</td>
                                            </tr>
                                            <tr>
                                                <td>Paper Width ({{ env('UOM') }})</td>
                                                <td>{{ $item->paper_width }}</td>
                                            </tr>
                                            <tr>
                                                <td>Cover Height ({{ env('UOM') }})</td>
                                                <td>{{ $item->cover_height }}</td>
                                            </tr>
                                            <tr>
                                                <td>Cover Width ({{ env('UOM') }})</td>
                                                <td>{{ $item->cover_width }}</td>
                                            </tr>
                                            <tr>
                                                <td>No. of Pages</td>
                                                <td>{{ $item->pages }}</td>
                                            </tr>
                                            <tr>
                                                <td>External Color</td>
                                                <td>{{ $item->color }}</td>
                                            </tr>
                                            <tr>
                                                <td>Internal Color</td>
                                                <td>{{ $item->color2 }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="table-responsive mt-5">
                                    <strong><small>Cost Breakdown</small></strong>
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item</th>
                                                <th>Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Editor</td>
                                                <td>{{ $item->editor }}</td>
                                            </tr>
                                            <tr>
                                                <td>Researcher</td>
                                                <td>{{ $item->researcher }}</td>
                                            </tr>
                                            <tr>
                                                <td>Writer</td>
                                                <td>{{ $item->writer }}</td>
                                            </tr>
                                            <tr>
                                                <td>Graphic Designer</td>
                                                <td>{{ $item->graphic_designer }}</td>
                                            </tr>
                                            <tr>
                                                <td>Layout Designer</td>
                                                <td>{{ $item->layout_designer }}</td>
                                            </tr>
                                            <tr>
                                                <td>Photographer</td>
                                                <td>{{ $item->photographer }}</td>
                                            </tr>
                                            <tr>
                                                <td>Markup Fee</td>
                                                <td>{{ $item->markup_fee }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Total Cost</strong></td>
                                                <td><strong>{{ $item->total_cost }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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