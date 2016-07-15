{{--TODO replase style to assets--}}
<style>
    .select-statistic-page {
        margin-bottom: 20px;
    }
</style>

<button type="button" style="opacity:0.5;" id="onePageStats" class="select-statistic-page btn btn-primary btn-flat"
        disabled>Detail for Page</button>
<button type="button" id="allSiteStats" class="select-statistic-page btn btn-primary btn-flat">All Site</button>
<div id="page-stats-table">
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
                        <td>{{ isset($arr["hits"]) ? $arr["hits"] : 0 }}</td>
                        <td>{{ isset($arr["ip"]) ? count($arr["ip"]) : 0 }}</td>
                        <td>{{ isset($arr["cookie"]) ? $arr["cookie"] : 0 }}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
        </tbody>
    </table>
</div>
<div id="all-stats-table" style="display:none;">
    <table class="table table-striped">
        <tr>
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
        @foreach($allstats as $key => $value)
            @if(!empty($value))
                    <tr>
                        <td>{{ $key }}</td>
                        <td>{{ isset($value["hits"]) ? $value["hits"] : 0 }}</td>
                        <td>{{ isset($value["ip"]) ? count($value["ip"]) : 0 }}</td>
                        <td>{{ isset($value["cookie"]) ? $value["cookie"] : 0 }}</td>
                    </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
{{--TODO replase scrypt to assets--}}
<script>
    onePageStats.onclick = function() {
        if (document.getElementById("page-stats-table").style.display == "none") {
            document.getElementById("page-stats-table").style.display = "block";
            document.getElementById("all-stats-table").style.display = "none";
            onePageStats.style.opacity = "0.5";
            allSiteStats.style.opacity = "1";
            onePageStats.setAttribute("disabled", "");
            allSiteStats.removeAttribute("disabled");
        }
    };

    allSiteStats.onclick = function() {
        if (document.getElementById("all-stats-table").style.display == "none") {
            document.getElementById("all-stats-table").style.display = "block";
            document.getElementById("page-stats-table").style.display = "none";
            allSiteStats.style.opacity = "0.5";
            onePageStats.style.opacity = "1";
            allSiteStats.setAttribute("disabled", "");
            onePageStats.removeAttribute("disabled");
        }
    };
</script>