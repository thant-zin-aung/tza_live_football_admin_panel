let fetchDataButton = document.getElementsByClassName("fetch-data-button")[0];
let leagueWrappersHeaders = document.getElementsByClassName("header");
let matchInfoWrappers = document.getElementsByClassName("match-info-wrapper");
let saveButtons = document.getElementsByClassName("save-button");
let editButtons = document.getElementsByClassName("edit-button");
let activeLeagueBody = "active-league-body";
let activeMatchBody = "active-match-body";

// fetchDataButton.addEventListener("click",e=>{
//     console.log("clicked fetch");
// })

for ( let count=0 ; count<leagueWrappersHeaders.length ; count++ ) {
    leagueWrappersHeaders.item(count).addEventListener("click",event=>{
        let parent = leagueWrappersHeaders.item(count).parentElement;
        let body = parent.getElementsByClassName("body")[0];
        if ( !body.classList.contains(activeLeagueBody) ) {
            body.classList.add(activeLeagueBody);
            for ( let lCount = 0 ; lCount < leagueWrappersHeaders.length ; lCount++ ) {
                if ( lCount == count ) continue;
                let oParent = leagueWrappersHeaders.item(lCount).parentElement;
                let oBody = oParent.getElementsByClassName("body")[0];
                oBody.classList.remove(activeLeagueBody);
            }
        } else {
            body.classList.remove(activeLeagueBody);
        }
    }); 
}

for ( let count=0 ; count<matchInfoWrappers.length ; count++ ) {
    matchInfoWrappers.item(count).addEventListener("click",event=>{
        let parent = matchInfoWrappers.item(count).parentElement;
        let body = parent.getElementsByClassName("add-links-wrapper")[0];
        if ( !body.classList.contains(activeMatchBody) ) {
            body.classList.add(activeMatchBody);
            for ( let lCount = 0 ; lCount < matchInfoWrappers.length ; lCount++ ) {
                if ( lCount == count ) continue;
                let oParent = matchInfoWrappers.item(lCount).parentElement;
                let oBody = oParent.getElementsByClassName("add-links-wrapper")[0];
                oBody.classList.remove(activeMatchBody);
            }
        } else {
            body.classList.remove(activeMatchBody);
        }
    }); 
}

// Save button manipulation
for ( let count=0 ; count<saveButtons.length ; count++ ) {
    saveButtons.item(count).addEventListener("click",event=>{
        let parent = saveButtons.item(count).parentElement;
        let linkBox = parent.getElementsByClassName("link-box")[0];
        setTimeout(() => {
            saveButtons.item(count).disabled=true;
            linkBox.disabled=true; 
            let individualSaveButtons = parent.getElementsByClassName("save-button");
            let isAllLinkSave=true;
            for ( let iCount = 0 ; iCount < individualSaveButtons.length ; iCount++ ) {
                if ( !individualSaveButtons.item(iCount).disabled ) {
                    isAllLinkSave=false;
                    break;
                }
            }
            let matchInfoWrapper = parent.parentElement.parentElement.parentElement;
            let doneLogo = matchInfoWrapper.querySelector(".match-info-wrapper .info-wrapper .done-logo");
            if ( isAllLinkSave ) doneLogo.style.display="inline-block";
            else doneLogo.style.display="none";
        }, 1000);
    });
}

// Edit button manipulation
for ( let count=0; count<editButtons.length ; count++ ) {
    editButtons.item(count).addEventListener("click",event=>{
        let parent = editButtons.item(count).parentElement;
        let linkBox = parent.getElementsByClassName("link-box")[0];
        let saveButton = parent.getElementsByClassName("save-button")[0];
        saveButton.disabled=false;
        linkBox.disabled=false; 
    });
}