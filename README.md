![Tests](https://github.com/RikudouSage/sdate/workflows/Tests/badge.svg)

# Eternal September date

This small library calculates the date for Eternal September (aka September that never ended).

Excerpt from [Wikipedia](https://en.wikipedia.org/wiki/Eternal_September):

> Eternal September or the September that never ended is Usenet slang for a period beginning in September 1993,
> the month that Internet service provider America Online (AOL) began offering Usenet access to its many users,
> overwhelming the existing culture for online forums. 

Basically it returns the date as if the September 1993 never ended, for example:

```php
<?php

echo sdate('Y-m-d', strtotime('2020-06-01'));
// echoes 1993-09-9771, e.g. the 9,771th day of September 1993
```

All day/month/year formats are supported, every other format is handled by the underlying date function.

## Why?

Why not?
