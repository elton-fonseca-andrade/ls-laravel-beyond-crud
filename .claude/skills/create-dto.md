# Skill: Create a DTO

## Template

```php
namespace Domain\{DomainName}\DataTransferObjects;

class {Name}Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?Carbon $birth_date = null,
    ) {}
}
```

## Usage from a Request

```php
$data = new CustomerData(
    name: $request->validated('name'),
    email: $request->validated('email'),
    birth_date: Carbon::make($request->validated('birth_date')),
);
```

## Rules

- Use `readonly` properties.
- Use constructor promotion.
- Use named arguments when instantiating.
- Nullable/optional fields use `?Type` with `= null` default.
- No methods beyond static factory methods (e.g. `fromRequest()`).
