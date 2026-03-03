<form class="form-horizontal" action="{{ route('commissions.pay_to_seller') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
    	<h5 class="modal-title h6">{{translate('Pay to seller')}}</h5>
    	<button type="button" class="close" data-dismiss="modal">
    	</button>
    </div>
    <div class="modal-body">
      @php
          $withdraw_type = $seller_withdraw_request->withdraw_type ?? 'standard';
          $balance = 0;
          $bank_name = '';
          $bank_acc_name = '';
          $bank_acc_no = '';
          $bank_routing_no = '';
          $ifsc_code = '';
          $bank_payment_status = 0;
          $cash_status = 0;
          $shop_id = null;

          if ($withdraw_type == 'referral') {
              $vendor = $user->vendor;
              if ($vendor) {
                  $balance = $vendor->referral_balance;
                  $bank_name = $vendor->bank_name;
                  $bank_acc_name = $vendor->bank_acc_name;
                  $bank_acc_no = $vendor->bank_acc_no;
                  $bank_routing_no = $vendor->bank_routing_no;
                  $ifsc_code = $vendor->ifsc_code;
                  $bank_payment_status = $bank_name ? 1 : 0;
                  $cash_status = 1;
                  $shop_id = $user->shop ? $user->shop->id : null;
              }
          } else {
              if ($user->shop) {
                  $balance = $user->shop->admin_to_pay;
                  $bank_name = $user->shop->bank_name;
                  $bank_acc_name = $user->shop->bank_acc_name;
                  $bank_acc_no = $user->shop->bank_acc_no;
                  $bank_routing_no = $user->shop->bank_routing_no;
                  $ifsc_code = $user->shop->ifsc_code;
                  $bank_payment_status = $user->shop->bank_payment_status;
                  $cash_status = $user->shop->cash_on_delivery_status;
                  $shop_id = $user->shop->id;
              }
          }
      @endphp

      <table class="table table-striped table-bordered" >
          <tbody>
                <tr>
                    <td>{{ translate('Withdrawal Type') }}</td>
                    <td>
                        <span class="badge badge-inline {{ $withdraw_type == 'referral' ? 'badge-success' : 'badge-info' }}">
                            {{ ucfirst($withdraw_type) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    @if($balance >= 0)
                        <td>{{ translate('Available Balance') }}</td>
                        <td>{{ single_price($balance) }}</td>
                    @endif
                </tr>
                <tr>
                    <td>{{ translate('Requested Amount') }}</td>
                    <td>{{ single_price($seller_withdraw_request->amount) }}</td>
                </tr>
                @if ($bank_payment_status == 1)
                    <tr>
                        <td>{{ translate('Bank Name') }}</td>
                        <td>{{ $bank_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Bank Account Name') }}</td>
                        <td>{{ $bank_acc_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Bank Account Number') }}</td>
                        <td>{{ $bank_acc_no }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Bank Routing Number') }}</td>
                        <td>{{ $bank_routing_no }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('IFSC Code') }}</td>
                        <td>{{ $ifsc_code ?? translate('Not Set') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if ($balance > 0)
            <input type="hidden" name="shop_id" value="{{ $shop_id }}">
            <input type="hidden" name="payment_withdraw" value="withdraw_request">
            <input type="hidden" name="withdraw_request_id" value="{{ $seller_withdraw_request->id }}">
            <div class="form-group row">
                <label class="col-sm-3 col-from-label" for="amount">{{translate('Payment Amount')}}</label>
                <div class="col-sm-9">
                    @php
                        $pay_amount = min($seller_withdraw_request->amount, $balance);
                    @endphp
                    <input type="number" lang="en" min="0" step="0.01" name="amount" id="amount" value="{{ $pay_amount }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-from-label" for="payment_option">{{translate('Payment Method')}}</label>
                <div class="col-sm-9">
                    <select name="payment_option" id="payment_option" class="form-control demo-select2-placeholder" required>
                        <option value="">{{translate('Select Payment Method')}}</option>
                        @if($cash_status == 1 || $withdraw_type == 'referral')
                            <option value="cash">{{translate('Cash')}}</option>
                        @endif
                        @if($bank_payment_status == 1)
                            <option value="bank_payment">{{translate('Bank Payment')}}</option>
                        @endif
                    </select>
                </div>
            </div>
            
            <div class="form-group row" id="txn_div">
                <label class="col-md-3 col-from-label" for="txn_code">{{translate('Txn Code')}}</label>
                <div class="col-md-9">
                    <input type="text" name="txn_code" id="txn_code" class="form-control">
                </div>
            </div>
        @endif

    </div>
    <div class="modal-footer">
      @if ($balance > 0)
        <button type="submit" class="btn btn-primary">{{translate('Pay')}}</button>
      @endif
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
