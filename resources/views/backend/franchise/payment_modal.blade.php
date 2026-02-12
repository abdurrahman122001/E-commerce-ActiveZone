<form action="{{ route('admin.franchises.payment_store') }}" method="POST">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <div class="modal-header">
    	<h5 class="modal-title h6">{{translate('Pay to Franchise')}}</h5>
    	<button type="button" class="close" data-dismiss="modal">
    	</button>
    </div>
    <div class="modal-body">
      <table class="table table-striped table-bordered" >
          <tbody>
              <tr>
                  <td>{{ translate('Current Balance') }}</td>
                  <td>
                      @if($user->franchise)
                        {{ single_price($user->franchise->balance) }}
                      @elseif($user->sub_franchise)
                        {{ single_price($user->sub_franchise->balance) }}
                      @else
                        {{ single_price(0) }}
                      @endif
                  </td>
              </tr>
          </tbody>
      </table>

      <div class="form-group row">
          <label class="col-md-3 col-from-label" for="amount">{{translate('Amount')}}</label>
          <div class="col-md-9">
              <input type="number" lang="en" min="0" step="0.01" name="amount" id="amount" class="form-control" required>
          </div>
      </div>

      <div class="form-group row">
          <label class="col-md-3 col-from-label" for="payment_option">{{translate('Payment Method')}}</label>
          <div class="col-md-9">
              <select name="payment_option" id="payment_option" class="form-control aiz-selectpicker" required>
                  <option value="">{{translate('Select Payment Method')}}</option>
                  <option value="cash">{{translate('Cash')}}</option>
                  <option value="bank_payment">{{translate('Bank Payment')}}</option>
              </select>
          </div>
      </div>
      <div class="form-group row" id="txn_div">
          <label class="col-md-3 col-from-label" for="txn_code">{{translate('Txn Code')}}</label>
          <div class="col-md-9">
              <input type="text" name="txn_code" id="txn_code" class="form-control">
          </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">{{translate('Pay')}}</button>
      <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
    </div>
</form>

<script>
  $(document).ready(function(){
      $('#payment_option').on('change', function() {
        if ( this.value == 'bank_payment')
        {
          $("#txn_div").show();
        }
        else
        {
          $("#txn_div").hide();
        }
      });
      $("#txn_div").hide();
      AIZ.plugins.bootstrapSelect('refresh');
  });
</script>
