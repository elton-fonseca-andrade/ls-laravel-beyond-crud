# Skill: Refactor a Fat Model

## Identify what to extract

1. **Scopes** → Custom QueryBuilder class
2. **Collection logic** → Custom Collection class
3. **Calculations** (totals, prices, aggregations) → Actions
4. **Side-effect methods** (`send()`, `toPdf()`, `archive()`) → Actions
5. **Event handling** → Dedicated Event classes + Subscriber classes
6. **Complex accessors** that compute values → Pre-calculate in Actions, store in DB

## Process

1. Pick one responsibility to extract.
2. Create the target class (QueryBuilder, Collection, Action, Subscriber).
3. Move the logic.
4. Wire the model to the new class (override `newEloquentBuilder`, `newCollection`, or `$dispatchesEvents`).
5. Update tests.
6. Repeat until the model only contains: relations, casts, simple accessors, config.
