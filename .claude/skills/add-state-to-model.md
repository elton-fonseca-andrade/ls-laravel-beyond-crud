# Skill: Add a State to a Model

## Steps

1. Create abstract state class:

```php
namespace Domain\{Domain}\States;

abstract class {Model}State
{
    public function __construct(
        protected {Model} $model,
    ) {}

    abstract public function colour(): string;
    // Add other abstract methods as needed
}
```

2. Create concrete states:

```php
class Pending{Model}State extends {Model}State
{
    public function colour(): string
    {
        return 'orange';
    }
}
```

3. Create transitions if state changes are needed:

```php
class PendingToPaidTransition
{
    public function __invoke(Invoice $invoice): Invoice
    {
        // Validate transition is allowed
        // Change state
        // Handle side effects (logging, notifications)
    }
}
```

4. Wire in the model:

```php
public function getStateAttribute(): InvoiceState
{
    return new $this->state_class($this);
}
```

5. Consider using `spatie/laravel-model-states` to reduce boilerplate.
