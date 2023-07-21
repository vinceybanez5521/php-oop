// console.log("Hello World!");

const deletePositionBtns = document.querySelectorAll(".delete-position");
// console.log(deletePositionBtns);

deletePositionBtns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();

    if (confirm("Are you sure you want to delete this position?")) {
      e.target.parentElement.submit();
    }
  });
});

const deleteEmployeeBtns = document.querySelectorAll(".delete-employee");
// console.log(deleteEmployeeBtns);

deleteEmployeeBtns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();

    if (confirm("Are you sure you want to delete this employee?")) {
      e.target.parentElement.submit();
    }
  });
});
