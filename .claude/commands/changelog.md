# /changelog

Generate a changelog entry and save it to the `changelogs` table.

## Steps

### 1. Scan git commits

Run:
```bash
git log master..develop --oneline --no-merges
```

If that returns nothing (branches are in sync or develop doesn't exist), fall back to:
```bash
git log --merges --oneline -1 master
```
Then get commits since that merge hash. If still nothing, use the last 20 commits:
```bash
git log --oneline --no-merges -20
```

Strip the commit hash prefix from each line and format as a bullet list:
```
- Commit message one
- Commit message two
```

### 2. Present and allow editing

Show the generated bullet list to the user clearly. Ask:
> Would you like to edit the description before saving? (yes/no)

If yes, present the list line-by-line and let the user modify, add, or remove lines in the conversation. Reconstruct the final description from their input.

### 3. Suggest version number

Query the last version from the changelogs table:
```bash
php artisan tinker --execute="echo App\Changelog::orderBy('id','desc')->value('version');"
```

If no version exists, suggest `1.0.0`. Otherwise increment the patch number (e.g. `1.2.3` → `1.2.4`). If the version doesn't follow semver, append `.1`.

Ask the user:
> Version number [suggested]:

### 4. Ask for release date

Ask the user:
> Release date (YYYY-MM-DD) [today's date]:

Default to today.

### 5. Ask for type

Present options and let the user pick one or more (comma-separated):
```
[0] feature
[1] fix
[2] improvement
[3] security
```
Ask:
> Select type(s) — enter numbers separated by commas (e.g. 0,2):

Map the selections back to their labels and join with commas (e.g. `feature,improvement`).

### 6. Confirm and save

Show a summary table of all fields (version, release_date, type, description preview). Ask:
> Save this changelog entry? (yes/no)

If yes, insert the record:
```bash
php artisan tinker --execute="
App\Changelog::create([
    'version'      => 'VERSION',
    'release_date' => 'RELEASE_DATE',
    'type'         => 'TYPE',
    'description'  => 'DESCRIPTION',
]);
echo 'Saved.';
"
```

Escape single quotes in the description before inserting (replace `'` with `\'`).

Confirm success to the user.
