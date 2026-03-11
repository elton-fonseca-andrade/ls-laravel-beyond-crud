# Testing Rules

## Test type

Domain tests are **integration tests** — they test business rules end-to-end with database.

## Pattern

Every test follows three steps: **Setup → Execute → Assert**.

## Test Factories (custom, NOT Laravel's default)

- Each factory is a plain class with a static `new()` constructor and a `create()` method.
- Factories are **immutable** — state methods return a cloned instance.
- Factories can accept other factories for nested relations.
- Factories auto-generate unique values (e.g. incrementing invoice numbers).

```php
class InvoiceFactory
{
    private static int $number = 0;
    private ?string $status = null;
    private ?PaymentFactory $paymentFactory = null;

    public static function new(): self
    {
        return new self();
    }

    public function paid(PaymentFactory $paymentFactory = null): self
    {
        $clone = clone $this;
        $clone->status = PaidInvoiceState::class;
        $clone->paymentFactory = $paymentFactory ?? PaymentFactory::new();
        return $clone;
    }

    public function create(array $extra = []): Invoice
    {
        self::$number += 1;

        $invoice = Invoice::create(array_merge([
            'number' => 'I-' . self::$number,
            'status' => $this->status ?? PendingInvoiceState::class,
        ], $extra));

        if ($this->paymentFactory) {
            $this->paymentFactory->forInvoice($invoice)->create();
        }

        return $invoice;
    }
}
```

## Testing DTOs

- Only test the mapping (e.g. `fromRequest()`). Type safety covers the rest.
- Assert the DTO is the correct class. Type hints handle field validation.

## Testing Actions

- Test the action's result, not the internals of composed sub-actions.
- Mock expensive side-effect actions (e.g. PDF generation) by binding a mock in the container.

```php
class MockGeneratePdfAction extends GeneratePdfAction
{
    public static function setUp(): void
    {
        app()->singleton(GeneratePdfAction::class, fn () => new self());
    }

    public function execute(ToPdf $toPdf): void
    {
        return;
    }
}
```

## Testing Models

- Test custom QueryBuilders, Collections, and Subscribers in isolation.
- For subscribers: call the listener method directly with a manually created event object.
