# Skill: Create a Test Factory

## Template

```php
namespace Tests\Factories;

class {Model}Factory
{
    private static int $counter = 0;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = []): {Model}
    {
        self::$counter++;

        return {Model}::create(array_merge([
            // Default attributes
        ], $extra));
    }
}
```

## Adding state methods

```php
public function paid(PaymentFactory $paymentFactory = null): self
{
    $clone = clone $this;
    $clone->status = PaidInvoiceState::class;
    $clone->paymentFactory = $paymentFactory ?? PaymentFactory::new();
    return $clone;
}
```

## Key rules

- Always return `clone $this` in state methods (immutability).
- Accept optional sub-factories for related models.
- Auto-increment unique fields via static counter.
- `new()` = static constructor. `create()` = produces the model.
