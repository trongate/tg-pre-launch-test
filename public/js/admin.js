var body = document.querySelector("body");

function _(elRef) {
    var firstChar = elRef.substring(0, 1);
    
    if (firstChar == '.') {
        elRef = elRef.replace(/\./g, '');
        return document.getElementsByClassName(elRef);
    } else {
        return document.getElementById(elRef);
    }
}

function setPerPage() {
    var lastSegment = window.location.pathname.split('/').pop();
    var selectedIndex = perPageSelector.value;
    var targetUrl = window.location.protocol + '//' + window.location.hostname + window.location.pathname;
    targetUrl = targetUrl.replace('/manage/', '/set_per_page/' + selectedIndex + '/');
    targetUrl = targetUrl.replace('/manage', '/set_per_page/' + selectedIndex + '/');
    window.location.href = targetUrl;
}

function initNoResults() {
    var search = window.location.search;
    var paras = document.getElementsByTagName("p");
    for (var i = 0; i < paras.length; i++) {
        if (paras[i].innerHTML == 'Your search produced the following result(s):') {
            if (search.length>0) {
                paras[i].innerHTML = 'Your search produced no results.';
            } else {
                paras[i].remove();
            }
        }
    }

    if (search.length>0) {
        var createLink = document.querySelector("#create-btn");
        createLink.innerHTML = 'Go Back';
        createLink.removeAttribute("href");
        var targetUrl = window.location.protocol + '//' + window.location.hostname + window.location.pathname;
        createLink.setAttribute("href", targetUrl);
    }

}

function initManagePage() {
    if (resultsTbl == null) {
        initNoResults();
    } else {
        perPageSelector.setAttribute("onchange", "setPerPage()");
    }
}

if (typeof resultsTbl !== "undefined") {
    initManagePage();
}

function openModal(modalId) {
    var modalContainer = document.createElement("div");
    modalContainer.setAttribute("id", "modal-container");
    modalContainer.setAttribute("style", "z-index: 3;");
    body.prepend(modalContainer);

    var overlay = document.createElement("div");
    overlay.setAttribute("id", "overlay");
    overlay.setAttribute("style", "z-index: 2");
    
    body.prepend(overlay);

    var commentModal = _(modalId);
    commentModalContent = commentModal.innerHTML;
    commentModal.remove();

    //create a new comment model
    var newModal = document.createElement("div");
    newModal.setAttribute("class", "modal");
    newModal.setAttribute("id", modalId);

    newModal.style.zIndex = 4;
    newModal.innerHTML = commentModalContent;
    modalContainer.appendChild(newModal);

    setTimeout(() => {
        newModal.style.opacity = 1;
        newModal.style.marginTop = '12vh';
    }, 0);    
}

function closeModal() {
    var modalContainer = _("modal-container");
    var openModal = modalContainer.firstChild;

    openModal.style.zIndex = -4;
    openModal.style.opacity = 0;
    openModal.style.marginTop = '12vh';
    openModal.style.display = 'none';
    body.appendChild(openModal);

    modalContainer.remove();
    var overlay = _("overlay");
    overlay.remove();
}

var pathArray = window.location.pathname.split( '/' );
var segment3 = pathArray[3];

if (segment3 == 'show') {
    ////SHOW FILE JS START

    var viewBtn = document.getElementById("view-all-btn");
    var viewBtnContent = '<i class="fa fa-list-alt"></i> ' + viewBtn.innerHTML;
    viewBtn.innerHTML = viewBtnContent;

    var updateBtn = document.getElementById("update-btn");
    var updateBtnContent = '<i class="fa fa-pencil"></i> ' + updateBtn.innerHTML;
    updateBtn.innerHTML = updateBtnContent;

    var deleteBtn = document.getElementById("delete-btn");
    var deleteBtnContent = '<i class="fa fa-trash"></i> ' + deleteBtn.innerHTML;
    deleteBtn.innerHTML = deleteBtnContent;

    var updateBtn = document.getElementById("comment-btn");
    var updateBtnContent = '<i class="fa fa-commenting-o"></i> ' + updateBtn.innerHTML;
    updateBtn.innerHTML = updateBtnContent;

    var commentsBlock = document.getElementById("comments-block");
    var commentsTbl = document.querySelector("#comments-block > table");

    function submitComment() {
        var textarea = document.querySelector("#comment-modal > div.modal-body > p:nth-child(1) > textarea");
        var comment = textarea.value.trim();
        
        if (comment == "") {
            return;
        } else {
            textarea.value = '';
            closeModal();

            var params = {
                comment,
                target_table: segment1,
                update_id: updateId
            }

            var targetUrl = baseUrl + 'api/create/tg_comments';
            const http = new XMLHttpRequest();
            http.open('post', targetUrl);
            http.setRequestHeader('Content-type', 'application/json');
            http.setRequestHeader('trongateToken', token);
            http.send(JSON.stringify(params));

            http.onload = function() {

                if (http.status == 401) {
                    //invalid token!
                    window.location.href = baseUrl + 'tg_administrators/login';
                } else if(http.status == 200) {
                    fetchComments();
                }

            }

        }

    }

    function fetchComments() {

        var params = {
            target_table: segment1,
            update_id: updateId,
            orderBy: 'date_created'
        }

        var targetUrl = baseUrl + 'api/get/tg_comments';
        const http = new XMLHttpRequest();
        http.open('post', targetUrl);
        http.setRequestHeader('Content-type', 'application/json');
        http.setRequestHeader('trongateToken', token);
        http.send(JSON.stringify(params));

        http.onload = function() {
            if (http.status == 401) {
                //invalid token!
                window.location.href = baseUrl + 'tg_administrators/login';
            } else if(http.status == 200) {

                while (commentsTbl.firstChild) {
                    commentsTbl.removeChild(commentsTbl.lastChild);
                }

                var comments = JSON.parse(http.responseText);
                for (var i = 0; i < comments.length; i++) {
                    var tblRow = document.createElement("tr");
                    var tblCell = document.createElement("td");
                    var pDate = document.createElement("p");
                    var pText = document.createTextNode(comments[i]['date_created']);
                    pDate.appendChild(pText);
                    var pComment = document.createElement("p");
                    var commentText = comments[i]['comment'];
                    pComment.innerHTML = commentText;

                    tblCell.appendChild(pDate);
                    tblCell.appendChild(pComment);
                    tblRow.appendChild(tblCell);
                    commentsTbl.appendChild(tblRow);
                    commentsBlock.appendChild(commentsTbl);
                }
            }
        }
    }

    fetchComments();
    ////SHOW FILE JS END
}

function fetchAssociatedRecords(relationName, updateId) {

    var params = {
        relationName,
        updateId,
        callingModule: segment1
    }

    var targetUrl = baseUrl + 'module_relations/fetch_associated_records';
    const http = new XMLHttpRequest();
    http.open('post', targetUrl);
    http.setRequestHeader('Content-type', 'application/json');
    http.setRequestHeader('trongateToken', token);
    http.send(JSON.stringify(params));

    http.onload = function() {
        drawAssociatedRecords(params.relationName, JSON.parse(http.responseText));
    }
}

function drawAssociatedRecords(relationName, results) {

    var targetTblId = relationName + '-records';
    var targetTbl = document.getElementById(targetTblId);

    while (targetTbl.firstChild) {
        targetTbl.removeChild(targetTbl.lastChild);
    }

    for (var i = 0; i < results.length; i++) {
        var recordId = results[i]["id"];
        var newTr = document.createElement("tr");
        var newTd = document.createElement("td");
        var tdText = document.createTextNode(results[i]["value"]);
        newTd.appendChild(tdText);
        newTr.appendChild(newTd);
        var btnCell = document.createElement("td");

        var disBtn = document.createElement("button");
        disBtn.innerHTML = '<i class="fa fa-ban"></i> disassociate';
        disBtn.setAttribute("onclick", "openDisassociateModal('" + relationName + "', " + recordId + ")");
        disBtn.setAttribute("class", "danger");

        btnCell.appendChild(disBtn);
        newTr.appendChild(btnCell);
        targetTbl.appendChild(newTr);
    }

    populatePotentialAssociations(relationName, results);
}

function populatePotentialAssociations(relationName, results) {

    var params = {
        updateId: updateId,
        relationName,
        results,
        callingModule: segment1
    }

    var fetchAvailableOptionsUrl = baseUrl + 'module_relations/fetch_available_options';

    const http = new XMLHttpRequest();
    http.open('post', fetchAvailableOptionsUrl);
    http.setRequestHeader('Content-type', 'application/json');
    http.setRequestHeader('trongateToken', token);
    http.send(JSON.stringify(params));
    http.onload = function() {
        //repopulate available records
        var results = JSON.parse(http.responseText);
        var associateBtnId = relationName + '-create';
        var associateBtn = document.getElementById(associateBtnId);

        if (results.length>0) {
            associateBtn.style.display = 'block';
            var dropdownId = relationName + '-dropdown';
            var targetDropdown = document.getElementById(dropdownId);

            while (targetDropdown.firstChild) {
                targetDropdown.removeChild(targetDropdown.lastChild);
            }

            for (var i = 0; i < results.length; i++) {
                var newOption = document.createElement("option");
                newOption.setAttribute("value", results[i]["key"]);
                newOption.innerHTML = results[i]["value"];
                targetDropdown.appendChild(newOption);
            }

        } else {
            associateBtn.style.display = 'none';
        }
    }

}

function openDisassociateModal(relationName, recordId) {

    setTimeout(() => {
        var elId = relationName + '-record-to-go';
        document.getElementById(elId).value = recordId;
    }, 100);

    var targetModalId = relationName + '-disassociate-modal';
    openModal(targetModalId);
}


function disassociate(relationName) {
    closeModal();

    //get the id of the record to go
    var elId = relationName + '-record-to-go';
   
    var params = {
        updateId: document.getElementById(elId).value,
        relationName
    }

    var disassociateUrl = baseUrl + 'module_relations/disassociate';

    const http = new XMLHttpRequest();
    http.open('post', disassociateUrl);
    http.setRequestHeader('Content-type', 'application/json');
    http.setRequestHeader('trongateToken', token);
    http.send(JSON.stringify(params));

    http.onload = function() {
        fetchAssociatedRecords(params.relationName, updateId);
    }

}

function submitCreateAssociation(relationName) {

    var dropdownId = relationName + '-dropdown';
    var dropdown = document.getElementById(dropdownId);

    var params = {
        updateId,
        relationName,
        callingModule: segment1,
        value: dropdown.value
    }

    closeModal();
    var createUrl = baseUrl + 'module_relations/submit';

    const http = new XMLHttpRequest();
    http.open('post', createUrl);
    http.setRequestHeader('Content-type', 'application/json');
    http.setRequestHeader('trongateToken', token);
    http.send(JSON.stringify(params));

    http.onload = function() {
        fetchAssociatedRecords(params.relationName, params.updateId);
    }
}



var sideNavOpen = false;
var sideNav = document.getElementById('side-nav');
var main = document.getElementsByTagName('main')[0];

function openSideNav() {
    sideNav.style.opacity = 1;
    sideNav.style.width = '250px';
    sideNav.style.zIndex = 2;
    sideNavOpen = true;
}

function closeSideNav() {
    sideNav.style.opacity = 0;
    sideNav.style.width = '0';
    sideNav.style.zIndex = -1;
    sideNavOpen = false;
}

main.addEventListener('click', (ev) => {
    if ((sideNavOpen == true) && (ev.target.id !== 'open-btn')) {
        closeSideNav();
    }
});