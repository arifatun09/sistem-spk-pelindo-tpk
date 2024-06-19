<div class="table-responsive text-nowrap">
    <table class="table table-striped">
        <tbody class="table-border-bottom-0">
            <tr>
                <td>Consistency Index</td>
                <td>=</td>
                <td>
                    @php
                    $result = 0;

                    foreach ($consistencyRatioResult as $_result) {
                    $result += $_result ? $_result->hasil : 0;
                    }

                    @endphp

                    {{ number_format((($result/8)-8)/(8-1), 3) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>