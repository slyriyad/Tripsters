
const panels = document.querySelectorAll(".panel");

panels.forEach((panel) => {
  panel.addEventListener("click", () => {
    removeActiveClasses();
    panel.classList.add("active");
    console.log("Active panel:", panel);
  });
});

const removeActiveClasses = () => {
  const activePanels = document.querySelectorAll(".panel.active");
  console.log("Active panels:", activePanels);
  activePanels.forEach((activePanel) => {
    console.log("Removing active class from:", activePanel);
    activePanel.classList.remove("active");
  });
};