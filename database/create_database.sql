CREATE DATABASE community_recipe_sharing;

USE community_recipe_sharing;

CREATE TABLE Users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Recipes (
  recipe_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  title VARCHAR(100) NOT NULL,
  instructions TEXT,
  image_url VARCHAR(255),
  creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Ingredients (
  ingredient_id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE Recipe_Ingredients (
  recipe_id INT,
  ingredient_id INT,
  quantity VARCHAR(50),
  PRIMARY KEY (recipe_id, ingredient_id),
  FOREIGN KEY (recipe_id) REFERENCES Recipes(recipe_id),
  FOREIGN KEY (ingredient_id) REFERENCES Ingredients(ingredient_id)
);

CREATE TABLE Categories (
  category_id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE Recipe_Categories (
  recipe_id INT,
  category_id INT,
  PRIMARY KEY (recipe_id, category_id),
  FOREIGN KEY (recipe_id) REFERENCES Recipes(recipe_id),
  FOREIGN KEY (category_id) REFERENCES Categories(category_id)
);

CREATE TABLE Ratings (
  rating_id INT PRIMARY KEY AUTO_INCREMENT,
  recipe_id INT,
  user_id INT,
  rating_value TINYINT,
  rating_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (recipe_id) REFERENCES Recipes(recipe_id),
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);