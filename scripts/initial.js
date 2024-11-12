document.addEventListener("DOMContentLoaded", () => {
    // Placeholder data for recipes
    const recipes = [
      { title: "Spaghetti Carbonara", imageUrl: "../images/carbonara.jpg" },
      { title: "Vegetable Stir Fry", imageUrl: "../images/stirfry.jpg" },
      { title: "Chicken Curry", imageUrl: "../images/curry.jpg" },
    ];
  
    const container = document.getElementById("recipes-container");
    
    recipes.forEach(recipe => {
      const recipeDiv = document.createElement("div");
      recipeDiv.className = "recipe";
      recipeDiv.innerHTML = `
        <img src="${recipe.imageUrl}" alt="${recipe.title}" width="100%">
        <h3>${recipe.title}</h3>
      `;
      container.appendChild(recipeDiv);
    });
  });
  