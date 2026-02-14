<form action="{{ route('admin.franchise_employees.payout_store') }}" method="POST">
    @csrf
    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
    <div class="modal-header">
        <h5 class="modal-title h6">{{translate('Send Salary / Bonus to')}} {{ $employee->name }}</h5>
        <button type="button" class="close" data-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-3 col-from-label">{{translate('Amount')}}</label>
            <div class="col-sm-9">
                <input type="number" step="0.01" name="amount" class="form-control" placeholder="{{ translate('Amount') }}" required min="1">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-from-label">{{translate('Type')}}</label>
            <div class="col-sm-9">
                <select name="type" class="form-control aiz-selectpicker" required>
                    <option value="salary">{{translate('Salary')}}</option>
                    <option value="bonus">{{translate('Bonus')}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-from-label">{{translate('Payment Method')}}</label>
            <div class="col-sm-9">
                <select name="payment_method" class="form-control aiz-selectpicker" required>
                    <option value="Cash">{{translate('Cash')}}</option>
                    <option value="Bank Transfer">{{translate('Bank Transfer')}}</option>
                    <option value="Mobile Banking">{{translate('Mobile Banking')}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-from-label">{{translate('Remark')}}</label>
            <div class="col-sm-9">
                <textarea name="remark" rows="3" class="form-control" placeholder="{{ translate('Remark') }}"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
        <button type="submit" class="btn btn-primary">{{translate('Send')}}</button>
    </div>
</form>
<script>
    $('.aiz-selectpicker').selectpicker();
</script>
