document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("recipes-container");
  fetch("../server/get_recipes.php")
    .then(response => response.json())
    .then(recipes => {
      recipes.forEach(recipe => {
        // console.log("We got inside");
        console.log(recipe);
        const recipeDiv = document.createElement("div");
        recipeDiv.className = "recipe";
        recipeDiv.innerHTML = `
          <img src="${recipe.image_url}" alt="${recipe.title}" width="100%" height="200px" object-fit="contain">
          <h3>${recipe.title}</h3>
        `;
        container.appendChild(recipeDiv);
      });
    })
    .catch(error => {
      console.error("Error loading recipes:", error);
    });
});
