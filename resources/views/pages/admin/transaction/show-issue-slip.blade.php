@if ($transaction->issue_slip)
    <div class="card card-outline card-orange shadow-none mt-3 mb-0">
        <div class="card-header py-2">
            <h6 class="card-title font-weight-bold">Issue Slip</h6>
            <small class="text-gray float-right">The access key opens this slip only</small>
        </div>
        <div class="card-body p-2 d-flex align-items-center flex-wrap">
            <a class="btn btn-app p-2 m-0 mr-3" href="/attachments/issue_slip/{{ $transaction->issue_slip }}" target="_blank">
                <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                <p class="text-dark">Slip</p>
            </a>
            <div>
                <span class="text-gray small">Access Key</span><br>
                <span class="font-weight-bold mr-2">{{ $transaction->issueSlipKey ? $transaction->issueSlipKey->key : 'No key yet' }}</span>
                @if ($transaction->issueSlipKey)
                    <button type="button" class="btn btn-xs btn-secondary rounded-circle mr-1" title="Copy Key" data-key="{{ $transaction->issueSlipKey->key }}"
                        onclick="var b = this; navigator.clipboard.writeText(b.dataset.key).then(function () { b.firstElementChild.textContent = 'check'; setTimeout(function () { b.firstElementChild.textContent = 'content_copy'; }, 1500); });">
                        <i class="align-middle material-icons" style="font-size: 1rem;">content_copy</i>
                    </button>
                @endif
                @if ($perms['can_manage_issue_slip'])
                    <form method="POST" action="{{ route('transaction-form.reset-issue-slip-key', ['transaction' => $transaction->id]) }}" class="d-inline" onsubmit="return confirm('Generate a new vendor access key for this attachment? The old key will stop working.');">
                        @csrf
                        <button type="submit" class="btn btn-xs btn-danger rounded-circle mr-1" title="Refresh Key">
                            <i class="align-middle material-icons" style="font-size: 1rem;">refresh</i>
                        </button>
                    </form>
                    @if ($transaction->vendor_id && !$transaction->is_reimbursement)
                        <form method="POST" action="{{ route('transaction-form.resend-vendor-mail', ['transaction' => $transaction->id]) }}" class="d-inline" onsubmit="return confirm('Resend the attachment to the vendor with a new access key?');">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-warning rounded-circle" title="Resend to Vendor">
                                <i class="align-middle material-icons" style="font-size: 1rem;">mail</i>
                            </button>
                        </form>
                    @endif
                @endif
                @if ($transaction->issueSlipKey)
                    <br>
                    <span class="text-gray small">
                        {{ $transaction->issueSlipKey->isExpired() ? 'expired ' : 'expires ' }}{{ $transaction->issueSlipKey->expires_at->diffForHumans() }}
                    </span>
                @endif
            </div>
        </div>
    </div>
@endif
