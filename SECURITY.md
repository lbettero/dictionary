# Security policy

## Supported version

Security fixes are applied to the current `main` branch. Older copies should be upgraded or replaced.

## Reporting a vulnerability

Do not publish exploit details in a public issue. Use the repository's private GitHub Security Advisory reporting flow so the maintainer can investigate before disclosure.

## Secure deployment

The public tooltip module does not require the PHP editors. Keep the editor disabled unless it is actively needed:

```text
DICTIONARY_EDITOR_ENABLED=0
```

If editing is required, the deployment must:

- use HTTPS;
- define a unique `DICTIONARY_ADMIN_USER` and strong `DICTIONARY_ADMIN_PASSWORD` outside the repository;
- restrict the editor routes at the reverse proxy or network layer when possible;
- give the PHP process write access only to `assets/data/dictionary.json` and `assets/css/dictionary-custom.css`;
- retain server logs without recording credentials or CSRF tokens;
- back up the two writable files and test restoration;
- monitor failed authentication and rejected write attempts;
- test updates in a separate environment before production.

The application rejects anonymous writes, non-POST saves, missing or invalid CSRF tokens, cross-origin requests, oversized dictionaries, invalid colors, and values outside supported ranges. Files are replaced through a temporary file in the destination directory.

## ENS and organizational evidence

This repository alone cannot establish ENS conformity. The system owner must separately determine legal or contractual applicability, approve the system boundary and category, maintain the risk analysis and statement of applicability, and retain operational evidence for access control, monitoring, backup, recovery, incidents, suppliers, and changes.
