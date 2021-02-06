<?php $amount = moneyFormatter($account->current_amount) ?>
<ul class="group list-group">
    <li class="list-group-item">
        <div class="title @if($amount<0) rgRed @endif"><a href="{{ route('accounting.accounts.show',$account->id)
        }}">{{
        $account->locale_name}}</a>

            @if($account->type=='credit')
                <span class="butn">Cr</span>
            @else
                <span class="butn">Dr</span>
            @endif
            <a href="{{route('accounting.accounts.create')}}?parent_id={{$account->id}}" class="butn"><i class="fa
            fa-plus-circle"></i></a>

            @can("edit chart")
                <a href="{{route('accounting.accounts.edit',$account->id)}}" class="butn"><i class="fa fa-edit"></i></a>
            @endcan

            @can("delete chart")
                <accounting-delete-button-layout-component
                        :url='@json(route('accounting.accounts.destroy',$account->id))'
                >
                    <i class="fa fa-trash"></i>
                </accounting-delete-button-layout-component>
            @endcan


            <span class="amount">  &nbsp;
            &nbsp;{{ $amount  }}</span>

        </div>
        @foreach($account->children()->orderBy('sorting_number','desc')->orderBy('id', 'ASC')->get() as $account)
            @includeIf('accounting.charts.row',['account' => $account])
        @endforeach
    </li>
</ul>
