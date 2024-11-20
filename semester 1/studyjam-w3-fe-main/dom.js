const inputTitle = document.getElementById("input-title")
const descriptionInput = document.getElementById("description-input")
const sendButton = document.getElementById("submit-button")
const booklistContainer = document.getElementById("booklist-container")

sendButton.addEventListener("click", function (event) {
  const dataID = Date.now()
  event.preventDefault();

  const div = document.createElement("div")
  div.setAttribute("data-id", dataID)

  const h3 = document.createElement("h3")
  h3.innerText = inputTitle.value
  div.append(h3)

  const p = document.createElement("p")
  p.innerText = descriptionInput.value
  div.append(p)

  const doneButton = document.createElement("button")
  doneButton.innerText = "wes mariii!"
  div.append(doneButton)
  doneButton.classList.add("done")

  doneButton.addEventListener("click", function () {
    doneButton.style.display = "none";
    cancelButton.style.display = "none";
    p.style.color = "blue";
  })

  doneButton.addEventListener("click", function () {
    div.style.opacity = "0.55";
    div.style.backgroundColor = "rgba(0, 128, 0, 0.25)";
  })
  
  doneButton.style.marginRight = "15px";

  const cancelButton = document.createElement("button")
  cancelButton.innerText = "Ga sido.."
  div.append(cancelButton)
  cancelButton.classList.add("cancel")
  cancelButton.setAttribute("data-id", dataID)

  cancelButton.addEventListener("click", function () {
    div.remove();
  })

  div.classList.add("book")
  booklistContainer.append(div)
})
  
  
  const cancelButton = document.createElement("button")
  cancelButton.innerText = "Ga sido.."
  div.append(cancelButton)
  cancelButton.classList.add("cancel")
  cancelButton.setAttribute("data-id", dataID)

  cancelButton.addEventListener("click", function () {
    div.remove()

  })

  div.classList.add("book")
  booklistContainer.append(div)

