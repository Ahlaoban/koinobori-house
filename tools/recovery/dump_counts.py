"""Count rows in the INSERT statements emitted by this mysqldump snapshot.

No SQL execution and no row values in output. Unsupported syntax fails closed.
"""
import re


def count_values(data, stop_at_semicolon=False):
    depth = rows = 0
    quoted = escaped = False
    for char in data:
        if quoted:
            if escaped:
                escaped = False
            elif char == 92:
                escaped = True
            elif char == 39:
                quoted = False
            continue
        if char == 39:
            quoted = True
        elif char == 59 and depth == 0 and stop_at_semicolon:
            if not rows:
                raise ValueError('Empty INSERT')
            return rows
        elif char == 40:
            if depth == 0:
                rows += 1
            depth += 1
        elif char == 41:
            depth -= 1
            if depth < 0:
                raise ValueError('Unbalanced SQL tuple')
        elif depth == 0 and char not in b', \t\r\n':
            raise ValueError('Unsupported text outside SQL tuple')
    if depth or quoted or escaped or not rows or stop_at_semicolon:
        raise ValueError('Incomplete SQL values')
    return rows


def dump_counts(sql):
    tables = re.findall(rb'^CREATE TABLE `([A-Za-z0-9_]+)` \(', sql, re.M)
    if not tables or len(tables) != len(set(tables)):
        raise ValueError('Missing or duplicate table definitions')
    counts = {table.decode('ascii'): 0 for table in tables}
    inserts = list(re.finditer(rb'^INSERT INTO `([A-Za-z0-9_]+)` VALUES\s+', sql, re.M))
    if len(inserts) != len(re.findall(rb'^INSERT INTO ', sql, re.M)):
        raise ValueError('Unsupported INSERT syntax')
    for match in inserts:
        if match[1].decode('ascii') not in counts:
            raise ValueError('Unsupported INSERT syntax or unknown table')
        counts[match[1].decode('ascii')] += count_values(sql[match.end():], True)
    return counts
