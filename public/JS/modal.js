const Modal = document.querySelector(".modal")
const closeBtn = document.querySelector(".close-btn")
const showBtn = document.querySelectorAll(".show-btn")
const input = document.querySelectorAll(".input-field")

closeBtn.addEventListener("click" , () =>{
    Modal.classList.add("hidden")
})

showBtn.forEach((btn , index) => {
    btn.addEventListener("click" , () => {
        input[index].type === "password" ? input[index].type = "text" : input[index].type = "password"
    })
})


