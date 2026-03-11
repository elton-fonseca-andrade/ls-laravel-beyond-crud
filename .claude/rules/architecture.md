# Architecture Rules

## Layers

- **Domain**: pure business logic. No dependency on HTTP, requests, or controllers.
- **Application**: consumes the domain. Controllers, ViewModels, Jobs, Requests, Resources, Queries.
- **Support**: generic utilities that could be a standalone package.

## Allowed dependencies

- Domain MUST NOT import Application code.
- Application imports Domain freely.
- Support can be used by both.

## Namespaces

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/App/",
            "Domain\\": "src/Domain/",
            "Support\\": "src/Support/"
        }
    }
}
```

## Application modules

- Group application layer code by feature module (e.g. `App/Admin/Invoices/`), not by technical type.
- A module can consume multiple domains.
- Domains and application modules do NOT need 1:1 mapping.
