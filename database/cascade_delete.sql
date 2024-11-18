-- Drop the existing foreign key constraints
ALTER TABLE `Recipe_Categories` 
DROP FOREIGN KEY `recipe_categories_ibfk_1`;

ALTER TABLE `Recipe_Categories` 
DROP FOREIGN KEY `recipe_categories_ibfk_2`;

-- Add new foreign key constraints with CASCADE DELETE
ALTER TABLE `Recipe_Categories`
ADD CONSTRAINT `recipe_categories_ibfk_1`
FOREIGN KEY (`recipe_id`) REFERENCES `Recipes`(`recipe_id`) ON DELETE CASCADE;

ALTER TABLE `Recipe_Categories`
ADD CONSTRAINT `recipe_categories_ibfk_2`
FOREIGN KEY (`category_id`) REFERENCES `Categories`(`category_id`) ON DELETE CASCADE;
