function OnAddUserActionHandler(){
    let name = document.getElementById("userChoice");
    let deleteContainer = document.getElementById("deleteListContainer");
    let firstP = document.createElement('p');
    let secondP = document.createElement('p');
    firstP.textContent = name.value;
    secondP.textContent = "Usuń";
    firstP.setAttribute("id", name.value);
    secondP.setAttribute("class","clickableParagraph");
    secondP.setAttribute("onclick","deleteUserFromList('"+name.value+"',this);");

    deleteContainer.appendChild(firstP);
    deleteContainer.appendChild(secondP);

    let dataArea = document.getElementById("usersToAdd");
    dataArea.textContent += name.value+"\n";

    let options = document.getElementById("users").childNodes;
    
    options.forEach(option => {
        if(option.value != null){
            if(name.value.includes(option.value)){
                document.getElementById("users").removeChild(option);
            } 
        }
    });
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

function deleteUserFromList(name, element){
    let userList = document.getElementById("usersToAdd");
    let listContent = userList.value.split("\n");
    let result = "";

    listContent.forEach(line => {
        if(!line.includes(name)){
            result += line+"\n";
        }
    });
    document.getElementById("deleteListContainer").removeChild(element);
    document.getElementById("deleteListContainer").removeChild(document.getElementById(name));
    document.getElementById("usersToAdd").textContent = result;
    let option = document.createElement("option");
    option.setAttribute("value", name);
    document.getElementById("users").appendChild(option);
}