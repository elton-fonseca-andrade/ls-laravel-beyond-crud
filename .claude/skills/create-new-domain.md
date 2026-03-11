# Skill: Create a New Domain

## Steps

1. Create the directory under `src/Domain/{DomainName}/`.
2. Add subdirectories as needed:

```
Domain/{DomainName}/
├── Actions/
├── DataTransferObjects/
├── Models/
├── QueryBuilders/
├── Collections/
├── Events/
├── Exceptions/
├── Listeners/
├── Rules/
└── States/
```

3. Only create directories you need immediately. Add others as the domain grows.
4. Register any service providers or event subscribers in the appropriate Laravel config.

## Notes

- Domain names reflect business concepts (Invoices, Customers, Bookings), not technical ones.
- Domains can be split later if they grow too large. Don't over-plan upfront.
