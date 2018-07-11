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

function SearchInUserDataList(){
    let searchName = document.getElementById("searchinput").value;
    let userList = document.getElementById("userTable").innerHTML.split("<tr>");
    let out = "";
    let i = 0;
    userList.forEach(element =>{
        if(element.includes(searchName)){
            out += element;
        }
        i++;
    });
    document.getElementById("userTable").innerHTML = out;
}