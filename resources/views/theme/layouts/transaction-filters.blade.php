
<div class="col-md-6">
    <div class="btn-group">
        <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside">
            Filters
        </button>
        <div class="dropdown-menu p-3" style="width:250px;">
            <form id="filterForm" class="pd-20">
                <div>
                    <strong>Order by</strong>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="orderBy" id="orderBy1" value="updated_at" @if($filter->orderBy == 'updated_at') checked @endif>
                        <label class="form-check-label" for="orderBy1">
                            Date modified
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="orderBy" id="orderBy2" value="name" @if($filter->orderBy == 'name') checked @endif>
                        <label class="form-check-label" for="orderBy2">
                            Name
                        </label>
                    </div>
                </div>
                
                <div class="dropdown-divider"></div>

                <div>
                    <strong>Sort order</strong>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sortBy" id="sortByAsc" value="asc" @if($filter->sortBy == 'asc') checked @endif>
                        <label class="form-check-label" for="sortByAsc">
                            Ascending
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sortBy" id="sortByDesc" value="desc" @if ($filter->sortBy == 'desc') checked @endif>
                        <label class="form-check-label" for="sortByDesc">
                            Descending
                        </label>
                    </div>
                </div>
                
                <div class="dropdown-divider"></div>

                <div>
                    <strong>Status filter</strong>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="statusFilter" id="showDefault" value="default" @if($filter->statusFilter == 'default') checked @endif>
                        <label class="form-check-label" for="showDefault">
                            Default
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="statusFilter" id="showSavedOnly" value="saved" @if($filter->statusFilter == 'saved') checked @endif>
                        <label class="form-check-label" for="showSavedOnly">
                            Saved items only
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="statusFilter" id="showPostedOnly" value="posted" @if ($filter->sortBy == 'posted') checked @endif>
                        <label class="form-check-label" for="showPostedOnly">
                            Posted items only
                        </label>
                    </div>
                </div>
                
                <div class="dropdown-divider"></div>

                <div class="form-check d-flex align-items-center">
                    <input class="form-check-input me-2" type="checkbox" id="showDeleted" name="showDeleted" @if($filter->showDeleted) checked @endif>
                    <label class="form-check-label" for="showDeleted">
                        Show cancelled items
                    </label>
                </div>
                
                <div class="align-items-center">
                    <div class="white-section">
                        <input class="range_01" name="perPage">
                        <label class="form-check-label">Items per page</label>
                    </div>
                </div>
                            
                <button id="filter" type="button" class="btn btn-sm btn-primary mt-4">Apply</button>
            </form>
        </div>
    </div>

    <div class="btn-group">
        <button id="action_dropdown_btn" type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
            Actions
        </button>
        <div id="action_dropdown" class="dropdown-menu">
            <a class="dropdown-item" href="javascript:void(0)" data-action="delete" onclick="multiple_cancel()">Cancel Transaction</a>
            <a class="dropdown-item disabled" href="javascript:void(0)" data-action="restore">No Action Available</a>
            {{-- <a class="dropdown-item" href="javascript:void(0)" data-action="restore" onclick="multiple_restore()">Restore</a> --}}
            <a class="dropdown-item text-danger disabled" href="javascript:void(0)" data-action="invalid" style="display: none;">Invalid selection</a>
        </div>
    </div>
</div>