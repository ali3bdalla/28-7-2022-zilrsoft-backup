<table class="table table-bordered text-center">
    <thead>
    <tr>
        <th class="text-center ">التاريخ</th>
        <th class="text-center "></th>
        <th class="text-center "></th>
        <th class="text-center " colspan="2">المجاميع</th>

        <th class="text-center " colspan="2">الرصيد</th>
    </tr>
    <tr>
        <th class="text-center ">التاريخ والوقت</th>
        <th class="text-center ">رقم القيد</th>
        <th class="text-center ">السند</th>
        <th class="text-center ">مدين</th>
        <th class="text-center ">دائن</th>
        <th class="text-center ">مدين</th>
        <th class="text-center ">دائن</th>
    </tr>
    </thead>
    <tbody> 
        @foreach($transactions as $transaction)
        <tr>
            <th class="text-center ">{{  $transaction->created_at }}</th>
            <th class="text-center ">{{ $transaction->container_id }}</th>
            <th class="text-center ">{{  $transaction->description }}</th>
            @if()
            <th class="text-center ">{{  $transaction->amount }}</th>
            <th class="text-center ">دائن</th>
            <th class="text-center ">مدين</th>
            <th class="text-center ">دائن</th>
        </tr>
        @endforeach
    </tbody>
</table>


{{  $transactions->links() }}