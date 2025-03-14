<div>
    <div class="d-flex justify-content-between mb-3">
        <ul class="list-inline mb-0">
            <li class="list-inline-item">
                <button type="button" class="btn btn-sm btn-outline-primary">
                    <span>Опубликовано: Да</span>
                    <i class="bi bi-x"></i>
                </button>
            </li>
            <li class="list-inline-item">
                <button type="button" class="btn btn-sm btn-outline-primary">
                    <span>Дата публикации: 01.01.2025 - 01.02.2025</span>
                    <i class="bi bi-x"></i>
                </button>
            </li>
            <li class="list-inline-item">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x"></i>
                </button>
            </li>
        </ul>
        <button type="button" class="btn btn-sm btn-primary">
            <i class="bi bi-funnel"></i>
        </button>
    </div>

    @empty($entityList)
        <div class="alert alert-secondary text-center">Записи не найдены, уточните условия поиска.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                <tr>
                    @foreach($table as $column)
                        <th scope="col">
                            <span>{{ $column->label }}</span>
                            @if($column->sortable)
                                <a href="#"><i class="bi bi-chevron-down"></i></a>
                            @endif
                        </th>
                    @endforeach
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($entityList as $entity)
                    @livewire(\Admin\Infrastructure\Component\List\RowComponent::class, ['entity' => $entity, 'table' => $table], key($entity['id']))
                @endforeach
                </tbody>
                <caption>
                    @include('admin.components.pagination.summary')
                </caption>
            </table>
        </div>
    @endempty

    @includeWhen($this->hasPagination, 'admin.components.pagination.pagination')
</div>