<div class="table-responsive text-nowrap">
    <table class="table table-striped">
        <tbody class="table-border-bottom-0">
            <tr>
                <td>λmaks</td>
                <td>=</td>
                <td>
                    @php
                    $result = 0;

                    foreach($consistencyRatioResult as $_result) {
                    $result += $_result ? $_result->hasil : 0;
                    }

                    @endphp

                    {{ $result / 8 }}
                </td>
            </tr>
        </tbody>
    </table>
</div>