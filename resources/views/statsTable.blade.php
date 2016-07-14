
    <table class="table table-striped">
        <tr>
            <th class="row-header">Page
            </th>
            <th class="row-header">Browser
            </th>
            <th class="row-header">Hits
            </th>
            <th class="row-header">Unique IP
            </th>
            <th class="row-header">Unique Cookie
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach(\Route::getRoutes() as $value)
                <tr>
                    <td align="center" style="width: 385px; vertical-align: middle;" rowspan="4">{{$value->getPath()}}</td>
                    <td>123</td>
                    <td>123</td>
                    <td>123</td>
                    <td>123</td>
                </tr>
                <tr>
                    <td>123</td>
                    <td>123</td>
                    <td>123</td>
                    <td>123</td>
                </tr>
                <tr>
                    <td>123</td>
                    <td>123</td>
                    <td>123</td>
                    <td>123</td>
                </tr>
                <tr>
                    <td>123</td>
                    <td>123</td>
                    <td>123</td>
                    <td>123</td>
                </tr>

        @endforeach
        </tbody>
    </table>

