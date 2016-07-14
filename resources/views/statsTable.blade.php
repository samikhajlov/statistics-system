    <table class="table table-striped">
        <tr>
            <th class="row-header">Page
            </th>
            <th class="row-header">{{ $type }}
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
        @foreach($stats as $key => $value)
            <?php $j = 0;?>
            @if(!empty($value))
                @foreach($value as $subgroup => $arr)
                    <tr>
                        @if($j == 0)
                            <td align="center" style="width: 385px; vertical-align: middle;"
                            rowspan="{{ count($value) }}">{{ $key }}</td>
                            <?php $j++;?>
                        @endif
                        <td>{{ $subgroup }}</td>
                        <td>{{ $arr["hits"] }}</td>
                        <td>{{ isset($arr["ip"]) ? count($arr["ip"]) : 0 }}</td>
                        <td>{{ $arr["cookie"] }}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
        </tbody>
    </table>

