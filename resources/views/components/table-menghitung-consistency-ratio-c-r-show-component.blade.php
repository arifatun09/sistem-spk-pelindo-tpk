<div class="table-responsive text-nowrap">
    <table class="table table-striped">
        <tbody class="table-border-bottom-0">
            <tr>
                <td>Consistency Ratio</td>
                <td>=</td>
                <td>
                    @php
                    $result = 0;

                    foreach($consistencyRatioResult as $_result) {
                    $result += $_result !== null ? $_result->hasil : 0;
                    }

                    @endphp

                    {{
                        number_format(
                            ((($result/8)-8)/(8-1))/1.41
                        , 3)
                    }} (Konsisten)
                </td>
            </tr>
        </tbody>
    </table>
</div>