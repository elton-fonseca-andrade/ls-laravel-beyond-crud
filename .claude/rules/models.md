# Model Rules

## Purpose

Models provide data from the database. They are NOT the place for business logic.

## What belongs in a Model

- Eloquent relations
- Attribute casts
- Simple accessors (reading pre-calculated data, NOT computing it)
- `$dispatchesEvents` mapping to specific event classes
- `newEloquentBuilder()` override pointing to custom QueryBuilder
- `newCollection()` override pointing to custom Collection

## What does NOT belong in a Model

- Price calculations, totals, aggregations → use Actions
- Complex scopes → extract to a dedicated QueryBuilder class
- Long collection chains → extract to a custom Collection class
- `$model->send()`, `$model->toPdf()` → use Actions
- Service location via `app()` → inject dependencies in Actions

## Custom QueryBuilders

```php
class InvoiceQueryBuilder extends Builder
{
    public function wherePaid(): self
    {
        return $this->whereState('status', Paid::class);
    }
}
```

Override in model:

```php
public function newEloquentBuilder($query): InvoiceQueryBuilder
{
    return new InvoiceQueryBuilder($query);
}
```

## Custom Collections

```php
class InvoiceLineCollection extends Collection
{
    public function creditLines(): self
    {
        return $this->filter(fn (InvoiceLine $line) => $line->isCreditLine());
    }
}
```

Override in model:

```php
public function newCollection(array $models = []): InvoiceLineCollection
{
    return new InvoiceLineCollection($models);
}
```

## Event mapping

Map generic model events to specific event classes:

```php
protected $dispatchesEvents = [
    'saving' => InvoiceSavingEvent::class,
    'deleting' => InvoiceDeletingEvent::class,
];
```

Use dedicated subscriber classes, not inline observers.
