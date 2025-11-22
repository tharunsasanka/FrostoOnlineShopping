
document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("themeToggle");
  const body = document.body;

  // Check localStorage for saved mode
  if (localStorage.getItem("theme") === "dark") {
    body.classList.add("dark-mode");
    toggle.textContent = "⚪";
  }

  toggle.addEventListener("click", () => {
    body.classList.toggle("dark-mode");
    const isDark = body.classList.contains("dark-mode");

    toggle.textContent = isDark ? "⚪" : "⚫";
    localStorage.setItem("theme", isDark ? "dark" : "light");
  });
});
