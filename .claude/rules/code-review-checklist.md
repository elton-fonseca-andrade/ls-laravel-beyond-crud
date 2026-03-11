# Code Review Checklist

Before finishing any task, verify:

- [ ] Business logic lives in an Action, not in a controller, model, or job.
- [ ] Data entering the domain is wrapped in a DTO with typed properties.
- [ ] Models contain no calculations or side-effect methods.
- [ ] No `app()` service location inside domain code — use constructor injection.
- [ ] Custom QueryBuilder used instead of scopes on the model.
- [ ] Custom Collection used if there are reusable collection operations.
- [ ] State pattern used when 2+ if/else branches check the same status field.
- [ ] Controllers are thin: DTO creation → action call → response.
- [ ] Jobs only dispatch actions — no inline business logic.
- [ ] ViewModels provide view data explicitly — no view composers.
- [ ] Domain code has no dependency on Application layer classes.
- [ ] Test factory is immutable and composable.
- [ ] Tests follow Setup → Execute → Assert pattern.
- [ ] New domain classes are in the correct `Domain/{Name}/` directory.
- [ ] Application code is grouped by module, not by technical type.
