# Skill: Create a New Feature

## Steps

1. **Identify the domain(s)** this feature belongs to.
2. **Define the DTO** — what data does this feature need?
3. **Create the Action** — what does this feature do?
4. **Create/update the Model** if new data is persisted.
5. **Create the application layer entry point**:
   - Controller + Request (HTTP)
   - Command (Console)
   - Job (Async)
6. **Wire the DTO**: map request/input → DTO → action.
7. **Write tests**: factory → action test → controller integration test.

## Checklist

- DTO created with readonly typed properties?
- Action created with `execute()` method?
- Controller only maps input and returns output?
- Test factory updated or created?
