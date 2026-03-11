# Skill: Create an Application Module

## Steps

1. Create the module directory:

```
App/{Application}/{ModuleName}/
├── Controllers/
├── Requests/
├── Resources/
├── ViewModels/
├── Queries/
├── Filters/
└── Middleware/
```

2. Only create subdirectories you need.
3. Register routes in a dedicated route file or route group.
4. Controllers receive Requests, build DTOs, call domain Actions, return responses.

## ViewModel template

```php
class {Name}ViewModel
{
    public function __construct(
        private User $user,
        private ?Post $post = null,
    ) {}

    public function post(): Post
    {
        return $this->post ?? new Post();
    }

    public function categories(): Collection
    {
        return Category::allowedForUser($this->user)->get();
    }
}
```

## HTTP Query Builder template

```php
class {Model}IndexQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        $query = {Model}::query()->with([/* eager loads */]);

        parent::__construct($query, $request);

        $this->allowedFilters(/* ... */)->allowedSorts(/* ... */);
    }
}
```

## Key rules

- Module groups code by feature, not by technical type.
- A module can consume multiple domains.
- Shared application code (base classes, global middleware) goes in `Support\`.
