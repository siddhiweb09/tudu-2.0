@if (!empty($ai_suggestions))
    <div class="row">
        <div class="col-md-12">
            <div class="card ai-suggestions">
                <div class="card-body">
                    <h5 class="card-title"><i class="ti-light-bulb"></i>Suggestions</h5>
                    <ul>
                        @foreach ($ai_suggestions as $suggestion)
                            <li>{{ $suggestion }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif