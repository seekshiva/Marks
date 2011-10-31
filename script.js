var tabl;
function setHouseInfo() {
    var str="";
    var x="";

    str += "<input type=\"hidden\" name=\"uids\" value=\"" + x + "\">";
    str += "<label for=\"houseList\">House</label>: <select id=\"houseList\" name=\"houseList\">";
    str += "<option value=\"0\">-</option>";
    for(var i=0;i<houses.length;++i) {
	str += "<option value=\"" + houses[i].houseId + "\">" + houses[i].house + "</option>";
    }
    str += "</select> ";

    str += "<label for=\"teamList\">Team</label>: <select id=\"teamList\" name=\"teamList\">";
    str += "<option value=\"0\">-</option>";
    for(var i=0;i<teams.length;++i) {
	str += "<option value=\"" + teams[i].teamId + "\">" + teams[i].team + "</option>";
    }
    str += "</select>";
    str += "<input type=\"button\" value=\"Go!\" onclick=\"editStudents();\"><br /><br />";
    document.getElementById("listContainer").innerHTML = str;
}

function editStudents() {
    var x="",url= window.location.origin + window.location.pathname;
    tabl = document.getElementById('studentsTable');
    n = tabl.getElementsByTagName("tr");
    for(var i=0;i<n.length;i++) {
	if(n[i].childNodes[0].innerHTML != "" && n[i].childNodes[0].childNodes[1].checked) {
	    x += "," + n[i].childNodes[0].childNodes[1].value;
	}
    }
    x = x.substr(1);
    if(x=="") {
	alert("You must select atleast one student!");
	return;
    }
    url += "?editstudents=1&class=" + classId + "&uids=" + x;
    var houseV = document.getElementById("houseList").value, teamV = document.getElementById("teamList").value;
    if(houseV==0 && teamV==0) {
	alert("Select either the house or the team value to edit it! ");
	return;
    }
    if(houseV!=0) url+="&house=" + houseV;
    if(teamV!=0) url+="&team=" + teamV;
    window.location = url;
    console.log("url: " + url);
}

function filter() {
    fval = document.getElementById("filter").value;
    rows = document.getElementById("studentsTable").getElementsByTagName("tr");
    for(i=0;i<rows.length;++i) {
	ch = rows[i].childNodes;
	if(ch[ch.length-1].nodeName == "TD")
	    if(ch[ch.length-1].innerHTML > fval) {
		rows[i].parentNode.removeChild(rows[i]);
	    }
    }
}


$(document).ready(function() {
    $("#optionHead").click(function(e) {
	$("#optionBody").slideToggle(0);
	if(localStorage["optionDown"] == "1") {
	    localStorage["optionDown"] = 0;
	}
	else {
	    localStorage["optionDown"] = "1";
	}

    });
    if(localStorage) {
	if(localStorage["optionDown"] == "1") {
	    $("#optionBody").slideDown(0);
	}
    }
});