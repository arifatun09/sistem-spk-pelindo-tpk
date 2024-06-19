<div class="table-responsive text-nowrap">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Kriteria</th>
                @foreach($criteria_gb_name as $index => $criteria)
                <th>{{ $index }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <!-- idnex sould be [$index, $_index] -->
            @php
            $indexing = 0
            @endphp

            @foreach($criteria_gb_name as $index => $criteria)
            @foreach($criteria_gb_name as $index => $criteria)
            <tr>
                <td>{{ $index }}</td>

                @foreach($criteria as $_index => $_criteria)
                @php
                $anhiproItem = $anhipro ? $anhipro->firstWhere('kriteria_id', $_criteria->id) : null;
                @endphp
                <td>{{ $anhiproItem ? $anhiproItem->hasil : '' }}</td>
                @endforeach

                @php
                $indexing += 1;
                @endphp

            </tr>
            @endforeach
            <tr>
                <td></td>
                @foreach($criteria_gb_jenis as $index => $criteria)
                @php
                $pairComparisonMatrixItem = $pairComparisonMatrix ? $pairComparisonMatrix->firstWhere('name', $index) : null;
                @endphp
                <td>{{ $pairComparisonMatrixItem ? $pairComparisonMatrixItem->hasil : '' }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>