const form = Document.querySelector(".typing-area"),
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button");

form.onsubmit = (e)=>{
    e.preventDefault();    // preventing form from submitting
}

sendBtn.onclick = () =>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/insert-chat.php", true);
    xhr.onload = () =>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if (xhr.status === 200) {
                inputField.value = ""; // once message inserted into database then leave blank the input field
            }
        }
    }

    // we have to send the form data throigh ajax to php
    let formData = new FormData(form);  // creating new formData object
    xhr.send(formData); // sending the form data to php
}