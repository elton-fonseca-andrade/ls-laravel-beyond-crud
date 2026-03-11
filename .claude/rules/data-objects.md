# DTO Rules (Data Transfer Objects)

## Definition

DTO = class that structures data in a typed, predictable way. Entry point for data into the domain.

## Conventions

- Use PHP 8+ **constructor promotion** and **readonly** properties:

```php
class CustomerData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly Carbon $birth_date,
    ) {}
}
```

- Instantiate with **named arguments**:

```php
$data = new CustomerData(
    name: $request->validated('name'),
    email: $request->validated('email'),
    birth_date: Carbon::make($request->validated('birth_date')),
);
```

## Factory methods

- Prefer a dedicated factory class in the application layer when mapping is complex.
- Simple mappings can use a static `fromRequest()` on the DTO itself — acceptable tradeoff.

## What NOT to do

- NO logic inside DTOs. They represent data, nothing else.
- NO untyped arrays as substitutes for DTOs when data has known structure.
- NO optional fields without explicit nullable types or default values.
