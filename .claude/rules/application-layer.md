# Application Layer Rules

## Controllers

- Thin. Receive request, build DTO, call action, return response.
- No business logic.
- Use single-action controllers (invokable) when a resource action is non-CRUD.

## ViewModels

- Responsible for preparing data for views.
- Inject dependencies explicitly — no hidden state via view composers.
- Can implement `Arrayable` to pass directly to `view()`.
- Can implement `Responsable` to return as JSON.
- ViewModels are NOT models — they can combine data from multiple sources.

## Jobs

- Jobs manage queue infrastructure: retries, delays, chaining, middleware.
- The `handle()` method calls a domain Action — nothing more.
- Jobs belong to the application layer, not the domain.

## HTTP Query Builders

- Extend `Spatie\QueryBuilder\QueryBuilder` in dedicated classes (e.g. `InvoiceIndexQuery`).
- Configure allowed filters, sorts, includes, and base query in the constructor.
- Inject into controllers via type-hint (auto-resolved via container).
- Controllers can still chain extra scopes on top for specific use cases.

## Requests

- Live in the application layer, inside the module.
- Map validated data to DTOs before passing to actions.

## Resources

- Live in the application layer.
- Map one-to-one with a model or DTO for API responses.
