# Skill: Create an Action

## Template

```php
namespace Domain\{DomainName}\Actions;

class {Name}Action
{
    public function __construct(
        // Inject dependencies here
    ) {}

    public function execute({InputDTO} $data): {ReturnType}
    {
        // Business logic here
    }
}
```

## Rules

- One public method: `execute()`.
- Dependencies in constructor. Context data in `execute()` params.
- Suffix with `Action`.
- Return a meaningful type (Model, DTO, bool, void).
- If this action needs async execution, create a Job wrapper in the application layer.
