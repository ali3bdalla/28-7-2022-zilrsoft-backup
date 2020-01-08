<div class="group">
    <div class="">
        <div class="title"><a href="{{ route('accounting.accounts.show',$account->id) }}">{{ $account->locale_name}}</a>

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
            &nbsp;{{ money_format("%i",$account->current_amount) }}</span>

        </div>
        @foreach($account->children as $account)
            @includeIf('accounting.charts.row',['account' => $account])
        @endforeach
    </div>
</div>
