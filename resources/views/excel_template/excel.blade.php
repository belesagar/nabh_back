@if(isset($other_data))
    <h3 style="text-align: center;padding-left: 100px" >{{$other_data['hospital_name']}}</h3>
    <br>
@endif
<table>
    <thead>
    <tr>
        @foreach($heading_array as $heading_value)
            <th>{{$heading_value}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($excel_data as $excel_value)
        <tr>
            @foreach($heading_array as $heading_value)
                <td>{{ $excel_value[$heading_value] }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
