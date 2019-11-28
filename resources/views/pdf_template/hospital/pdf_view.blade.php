<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ $title }}</title>
</head>
<body>
<h1>{{ $heading}}</h1>

<h3>{{$indicator_title}}</h3>

<div>
    <table border="1">
        <thead>
        <tr>
            @foreach($pdf_data['heading_array'] as $heading_value)
                <th>{{$heading_value}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($pdf_data['data_array'] as $value)
            <tr>
                @foreach($pdf_data['heading_array'] as $heading_value)
                    <td>{{ $value[$heading_value] }}</td>
                @endforeach
            </tr>
        @endforeach

        <!-- For showing total -->
        @If(isset($pdf_data['total_array']))
            <tr>
                @foreach($pdf_data['heading_array'] as $heading_value)
                    <td><b>{{ $pdf_data['total_array'][$heading_value] }}</b></td>
                @endforeach
            </tr>
        @endif
        </tbody>
    </table>
</div>
</body>
</html>
