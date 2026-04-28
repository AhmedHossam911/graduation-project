const Modal = document.querySelector(".modal")
const backdrop = document.querySelector("#modal-backdrop")
const closeBtn = document.querySelector(".close-btn")
const showBtn = document.querySelectorAll(".show-btn")
const input = document.querySelectorAll(".input-field")
const forgetPass = document.querySelector(".forget-pass")
const profileContainer = document.querySelector(".profile")

closeBtn.addEventListener("click" , () =>{
    if(Modal) Modal.classList.add("hidden")
    if(backdrop) backdrop.classList.add("hidden")
    if(profileContainer) profileContainer.classList.remove("blur-sm", "pointer-events-none", "select-none")
})

if(backdrop) {
    backdrop.addEventListener("click" , () =>{
        if(Modal) Modal.classList.add("hidden")
        if(backdrop) backdrop.classList.add("hidden")
        if(profileContainer) profileContainer.classList.remove("blur-sm", "pointer-events-none", "select-none")
    })
}

showBtn.forEach((btn , index) => {
    btn.addEventListener("click" , () => {
        input[index].type === "password" ? input[index].type = "text" : input[index].type = "password"
    })
})


forgetPass.addEventListener("click" , () => {
    if(Modal) Modal.classList.remove("hidden")
    if(backdrop) backdrop.classList.remove("hidden")
    if(profileContainer) profileContainer.classList.add("blur-sm", "pointer-events-none", "select-none")
})