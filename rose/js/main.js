function OnAddUserActionHandler(){
    let name = document.getElementById("userChoice").value;
    let text = document.getElementById("usersToAdd").value;

    if(text.indexOf("Brak użyszkodników") >= 0){
        document.getElementById("usersToAdd").value = null;
    }

    if(!(text.indexOf(name) >= 0) && name !== null){
        document.getElementById("usersToAdd").value += name+"\n";
    }
    let list = document.getElementById("users").innerHTML;
    document.getElementById("users").innerHTML = null;
    let spl = list.split("\n");
    let resStr = "";
    spl.forEach(element =>{
        if(!element.includes(name)){
            resStr += element;
            console.log(element);
        }
    });
    
    document.getElementById("users").innerHTML += resStr;
    document.getElementById("userChoice").value = null;
}

function OnDeleteUserActionHandler(){
    let name = document.getElementById("userChoice").value;
    let text = document.getElementById("usersToAdd").value;

    if(text.indexOf(name) >= 0 && name !== ""){
        document.getElementById("usersToAdd").value = null;
        let repl = text.replace("\n",",");
        let ar = repl.split(",");
        ar.forEach(element => {
            if(!element.trim().includes(name.trim())){
                document.getElementById("usersToAdd").value += element+"\n";
            }
        });
        document.getElementById("users").innerHTML += "<option value='"+name+"'>";
    }

    document.getElementById("userChoice").value = null;
}

function filter(){
    let searchFieldText = document.getElementById("searchinput").value;
    let fileList = document.getElementsByTagName("tbody")[1];

    for(let i = 0; i < fileList.children.length; i++){
        if(!fileList.children[i].textContent.includes(searchFieldText)){
            fileList.children[i].setAttribute("hidden", true);
        }else if(fileList.children[i].getAttribute("hidden")){
            fileList.children[i].removeAttribute("hidden");
        }
    }
}

function resetFilter(){
    let fileList = document.getElementsByTagName("tbody")[1];

    for(let i = 0; i < fileList.children.length; i++){
        fileList.children[i].removeAttribute("hidden", false);  
    }
}