import unittest
from dump_counts import count_values, dump_counts


class DumpCountTests(unittest.TestCase):
    def test_punctuation_inside_strings_does_not_create_rows(self):
        self.assertEqual(count_values(b"(1,'a),(b'),(2,'it\\'s (quoted)'),(3,NULL)"), 3)

    def test_doubled_quotes_and_binary_values(self):
        self.assertEqual(count_values(b"(1,'it''s safe'),(2,0xFE12),(3,'\\\\')"), 3)

    def test_split_insert_statements_and_empty_table(self):
        sql = (b'CREATE TABLE `wprs_a` (\n);\nCREATE TABLE `wprs_empty` (\n);\n'
               b"INSERT INTO `wprs_a` VALUES (1,'x'),(2,'y');\n"
               b"INSERT INTO `wprs_a` VALUES (3,'z');\n")
        self.assertEqual(dump_counts(sql), {'wprs_a': 3, 'wprs_empty': 0})

    def test_truncated_values_fail(self):
        for value in (b"(1,'unfinished", b'(1,2', b'(1)),(2)', b''):
            with self.subTest(value=value), self.assertRaises(ValueError):
                count_values(value)

    def test_unknown_table_or_insert_shape_fails(self):
        for insert in (b'INSERT INTO `unknown` VALUES (1);',
                       b'INSERT INTO `wprs_a` (`id`) VALUES (1);'):
            with self.subTest(insert=insert), self.assertRaises(ValueError):
                dump_counts(b'CREATE TABLE `wprs_a` (\n);\n' + insert)

    def test_multiline_values_and_quoted_semicolon(self):
        sql = (b'CREATE TABLE `wprs_a` (\n);\n'
               b"INSERT INTO `wprs_a` VALUES\n(1,'a; b'),\n(2,'x');\n"
               b"INSERT INTO `wprs_a` VALUES (3,'y');\n")
        self.assertEqual(dump_counts(sql), {'wprs_a': 3})

    def test_missing_statement_terminator_fails(self):
        with self.assertRaises(ValueError):
            dump_counts(b'CREATE TABLE `wprs_a` (\n);\nINSERT INTO `wprs_a` VALUES\n(1)')


if __name__ == '__main__':
    unittest.main()
