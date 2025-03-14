<div>
    <div class="d-flex justify-content-end mb-3">
        <ul class="list-inline mb-0">
            <li class="list-inline-item">
                <button type="button" class="btn btn-primary btn-floating">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </li>
            @if($this->canCreateEntity)
                <li class="list-inline-item">
                    @livewire(\Admin\Infrastructure\Component\Create\CreateButton::class, ['createEntityComponent' => static::$createEntityComponent])
                </li>
            @endif
        </ul>
    </div>

    <div class="mb-3">
        @include('admin.components.entity.index.activeFilters')
    </div>

    @empty($entityList)
        <div class="alert alert-secondary text-center">Записи не найдены, уточните условия поиска.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    @foreach($tableColumns as $column)
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
                    @livewire(\Admin\Infrastructure\Component\Index\RowComponent::class, ['entity' => $entity, 'tableColumns' => $tableColumns, 'indexEntityComponent' => static::class, 'updateEntityComponent' => static::$updateEntityComponent], key($entity['id']))
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