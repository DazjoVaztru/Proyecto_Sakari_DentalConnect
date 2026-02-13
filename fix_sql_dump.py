import re

input_file = 'c:/laragon/www/DentalConnect/respaldo_dental.sql'
output_file = 'c:/laragon/www/DentalConnect/respaldo_dental_fixed.sql'

def fix_sql_dump(input_path, output_path):
    with open(input_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # REMOVE DEFINER clauses
    # Pattern to match: DEFINER=`user`@`host`
    # Common formats: DEFINER=`root`@`localhost` or DEFINER=root@localhost
    content = re.sub(r'DEFINER\s*=\s*`?[^`]+`?@`?[^`\s]+`?', '', content)
    
    # OPTIONAL: Fix collation if moving between MySQL versions (e.g. 8.0 to 5.7 or MariaDB)
    # Replacing utf8mb4_0900_ai_ci with utf8mb4_unicode_ci is a common fix
    content = content.replace('utf8mb4_0900_ai_ci', 'utf8mb4_unicode_ci')

    # Ensure FOREIGN_KEY_CHECKS is set to 0 at the start
    # It usually is, but let's double check or leave it if present. 
    # The dump usually has /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
    
    with open(output_path, 'w', encoding='utf-8') as f:
        f.write(content)

    print(f"Successfully created fixed SQL dump at: {output_path}")

if __name__ == "__main__":
    try:
        fix_sql_dump(input_file, output_file)
    except Exception as e:
        print(f"Error processing file: {e}")
