@php use App\Domain\Credit\ExchangeCreditStatus; @endphp
<div>
    @if($status === ExchangeCreditStatus::PENDING->value)
        <h4><span class="badge bg-warning">{{__('website.exchange_credit_status.'.$status)}}</span></h4>
    @elseif($status === ExchangeCreditStatus::APPROVED->value)
        <h4><span class="badge bg-primary">{{__('website.exchange_credit_status.'.$status)}}</span></h4>
    @elseif($status === ExchangeCreditStatus::REJECTED->value)
        <h4><span class="badge bg-danger">{{__('website.exchange_credit_status.'.$status)}}</span></h4>
    @elseif($status === ExchangeCreditStatus::COMPLETED->value)
        <h4><span class="badge bg-success">{{__('website.exchange_credit_status.'.$status)}}</span></h4>
    @endif
</div>
