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
                $indexing = 0;
                $rowspan = count($criteria_gb_name);
            @endphp

            @foreach($criteria_gb_name as $index => $criteria)
                        <tr>
                            <td>{{ $index }}</td>

                            @foreach($criteria as $_index => $_criteria)
                                        @php
                                            $anhiproItem = $anhipro->firstWhere('kriteria_id', $_criteria->id);
                                        @endphp
                                        <td>{{ $anhiproItem ? $anhiproItem->hasil : '' }}</td>
                            @endforeach

                            @if($rowspan >= count($criteria))
                                <td rowspan="{{ $rowspan }}">X</td>
                            @endif

                            @php
                                $calculatePriorityWeightsItem = $calculatePriorityWeights->firstWhere('name', "{$index}-pw");
                            @endphp
                            <td>{{ $calculatePriorityWeightsItem ? $calculatePriorityWeightsItem->hasil : '' }}</td>

                            @if($rowspan >= count($criteria))
                                        <td rowspan="{{ $rowspan }}">=</td>

                                        @php
                                            $rowspan -= 1;
                                        @endphp
                            @endif

                            @php
                                $consistencyRatioItem = $consistencyRatio->firstWhere('name', $index);
                            @endphp
                            <td>{{ $consistencyRatioItem ? $consistencyRatioItem->hasil : '' }}</td>

                            @php
                                $indexing += 1;
                            @endphp

                        </tr>
            @endforeach
        </tbody>
    </table>
</div>