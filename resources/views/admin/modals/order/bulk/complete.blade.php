<div class="modal fade" id="bulk-complete-modal" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('orders.bulk_update',['type' => 'complete']) }}" method="POST" id="bulk-complete-form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mark all as complete</h5>
                </div>
                <div class="modal-body">
                    <h6>This will mark all the selected orders into <strong>COMPLETED ORDER</strong></h6>
                    <span class="text-danger">NOTE: This cannot be undone</span>
                        @csrf
                        @method("PATCH")

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">
                        Cancel
                    </button>
                    <button class="btn btn-success" type="submit" id="button-complete-submit">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>