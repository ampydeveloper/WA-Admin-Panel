<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Farms</title>
    
</head>

<body>
    <div class="container">
        

        <table class="table">
            <thead>
                <tr class="" style="border: 1px solid black">
                    <th scope="col">id</th>
                    <th scope="col">customer_id</th>
                    <th scope="col">farm_address</th>
                    <th scope="col">farm_city</th>
                    <th scope="col">farm_image</th>
                    <th scope="col">farm_province</th>
                    <th scope="col">farm_unit</th>
                    <th scope="col">farm_zipcode</th>
                    <th scope="col">farm_active</th>
                    <th scope="col">latitude</th>
                    <th scope="col">longitude</th>
                    <th scope="col">distance_warehouse</th>
                    <th scope="col">distance_dumping_area</th>
                    <th scope="col">created_by</th>
                    <th scope="col">assigned_job_date_time</th>
                    <th scope="col">created_at</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customerFarms as $data)
                <tr>
                    <th scope="row">{{ $data->id }}</th>
                    <td>{{ $data->customer_id??"" }}</td>
                    <td>{{ $data->farm_address??"" }}</td>
                    <td>{{ $data->farm_city??"" }}</td>
                    <td>{{ $data->farm_image??"" }}</td>
                    <td>{{ $data->farm_province??"" }}</td>
                    <td>{{ $data->farm_unit??"" }}</td>
                    <td>{{ $data->farm_zipcode??"" }}</td>
                    <td>{{ $data->farm_active??"" }}</td>
                    <td>{{ $data->distance_warehouse??"" }}</td>
                    <td>{{ $data->distance_dumping_area??"" }}</td>
                    <td>{{ $data->created_by??"" }}</td>
                    <td>{{ $data->assigned_job_date_time??"" }}</td>
                    <td>{{ $data->created_at??"" }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>

</html>