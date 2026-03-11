# State vs Enum Rules

## When to use the State Pattern

- Model behavior changes depending on its state (colors, permissions, business rules).
- Multiple if/else or match blocks check the same status field across the codebase.
- State transitions have side effects (logging, notifications, validation).

## When to use Enums

- A set of related values with minimal behavior attached.
- Few places in the codebase branch on the value.
- No transitions needed.

## Rule of thumb

Start with an enum. When you find yourself attaching value-specific logic or growing if/else chains, refactor to the state pattern.

## State Pattern structure

```
Domain/Invoices/States/
├── InvoiceState.php          # Abstract base
├── PendingInvoiceState.php   # Concrete state
├── PaidInvoiceState.php      # Concrete state
└── Transitions/
    └── PendingToPaidTransition.php
```

- Abstract state class defines the contract (e.g. `colour()`, `mustBePaid()`).
- Concrete states implement behavior. Each is independently testable.
- States receive the model via constructor to access model data.
- Transitions are separate classes — they change state and handle side effects.
- States are for **reading/providing data**. Transitions are for **writing/changing state**.

## States without transitions

A state that never changes is valid (e.g. `InvoiceType`: Credit vs Debit). Apply the same pattern to eliminate if/else on type fields.
