
<h3 style="text-align: center;padding-left: 100px" >Table Data</h3>
<br>
<table>
    <thead>
    <tr>
        @foreach($heading_array as $heading_value)
            <th>{{$heading_value}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($excel_data as $user)
        <tr>
            @foreach($heading_array as $heading_value)
                <td>{{ $user->$heading_value }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
