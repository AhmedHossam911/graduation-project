const Modal = document.querySelector(".modal")
const closeBtn = document.querySelector(".close-btn")
const showBtn = document.querySelectorAll(".show-btn")
const inputFields = document.querySelectorAll(".input-field")

if (closeBtn && Modal) {
    closeBtn.addEventListener("click" , () =>{
        Modal.classList.add("hidden")
    })
}

showBtn.forEach((btn , index) => {
    btn.addEventListener("click" , () => {
        if (inputFields[index]) {
            inputFields[index].type === "password" ? inputFields[index].type = "text" : inputFields[index].type = "password"
        }
    })
})
