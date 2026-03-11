# Action Rules

## Definition

Action = simple class that takes input, runs business logic, returns output. Lives in the domain.

## Conventions

- Required suffix: `Action` (e.g. `CreateInvoiceAction`).
- Single public method: `execute()`.
- Dependencies via **constructor** (container DI).
- Context data via **`execute()` parameters**.
- DO NOT use `handle()` — conflicts with Laravel's method injection.
- DO NOT use `__invoke()` — broken syntax when composing actions as injected properties.

## Composition

- Actions can inject other actions via constructor.
- Avoid deep dependency chains.
- Extract shared utilities to `Support\` (e.g. `VatCalculator`).

## What NOT to do

- NO business logic in controllers, models, or jobs.
- NO direct `Request` access inside an action — receive a DTO instead.
- NO infrastructure concerns (queues, HTTP) inside an action.

## Queueable actions

- For async dispatch, use a simple Job wrapper or `spatie/laravel-queueable-action`.
- The Job belongs to the application layer; the action belongs to the domain.
