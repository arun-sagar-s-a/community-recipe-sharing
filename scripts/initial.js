document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("recipes-container");
  fetch("../server/get_recipes.php")
    .then(response => response.json())
    .then(recipes => {
      recipes.forEach(recipe => {
        console.log("We got inside");
        console.log(recipe);
        const recipeDiv = document.createElement("div");
        recipeDiv.className = "recipe";
        recipeDiv.innerHTML = `
          <img src="../images/pancake.jpg" alt="Classic Pancakes" width="100%">
          <h3>${recipe.title}</h3>
        `;
        container.appendChild(recipeDiv);
      });
    })
    .catch(error => {
      console.error("Error loading recipes:", error);
    });
});
