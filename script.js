var tabl;
function setHouseInfo() {
    var str="";
    var x="";

    str += "<input type=\"hidden\" name=\"uids\" value=\"" + x + "\">";
    str += "<label for=\"mentorList\">Mentor</label>: <select id=\"mentorList\" name=\"mentorList\">";
    str += "<option value=\"0\">-</option>";
    for(var i=1;i<teachers.length;++i) {
	str += "<option value=\"" + teachers[i].id + "\">" + teachers[i].code + " - " + teachers[i].name + "</option>";
    }
    str += "</select> ";

    str += "<label for=\"houseList\">House</label>: <select id=\"houseList\" name=\"houseList\">";
    str += "<option value=\"0\">-</option>";
    for(var i=1;i<houses.length;++i) {
	str += "<option value=\"" + i + "\">" + houses[i] + "</option>";
    }
    str += "</select> ";

    str += "<label for=\"teamList\">Team</label>: <select id=\"teamList\" name=\"teamList\">";
    str += "<option value=\"0\">-</option>";
    for(var i=1;i<teams.length;++i) {
	str += "<option value=\"" + i + "\">" + teams[i] + "</option>";
    }
    str += "</select> ";
    str += "<input type=\"button\" value=\"Go!\" onclick=\"editStudents();\">";
    document.getElementById("listContainer").innerHTML = str;
}

function editStudents() {
    var x="",url= window.location.protocol + "//" + window.location.hostname + window.location.pathname;
    //console.log(url);return;
    tabl = document.getElementById('studentsTable');
    n = tabl.getElementsByTagName("tr");
    for(var i=0;i<n.length;i++) {
	if(n[i].childNodes[0].childNodes.length != 1 && n[i].childNodes[0].childNodes[1].checked) {
	    x += "," + n[i].childNodes[0].childNodes[1].value;
	}
    }
    x = x.substr(1);
    if(x=="") {
	alert("You must select atleast one student!");
	return;
    }
    url += "?editstudents=1&class=" + classId + "&uids=" + x;
    var mentorV = document.getElementById("mentorList").value, houseV = document.getElementById("houseList").value, teamV = document.getElementById("teamList").value;
    if(mentorV == 0 && houseV==0 && teamV==0) {
	alert("Select either the mentor or house or the team value to edit it! ");
	return;
    }
    if(mentorV!=0) url+="&mentor=" + mentorV;
    if(houseV!=0) url+="&house=" + houseV;
    if(teamV!=0) url+="&team=" + teamV;
    window.location = url;
    console.log("url: " + url);
}

function getStudentsFromClass() {
    setHouseInfo();
    var str = "<tr><th><span class=\"op\">S.No</span></th><th>Exm No</th><th>Adm No</th><th>Name</th><th>Team</th><th>House</th><th>Mentor</th></tr>\n",
    t = document.createElement("table");
    t.setAttribute("id","studentsTable");
    t.setAttribute("border","1");
    t.setAttribute("cellpadding","0");
    t.setAttribute("cellspacing","0");
    for(var i=1; i < studentsList.length; ++i) {
	str += "<tr><td onclick=\"this.childNodes[1].checked = !this.childNodes[1].checked; \" style=\"cursor:pointer; \"><span class=\"op\">" + i + "</span><input class=\"np\" type=\"checkbox\" name=\"uids[]\" value=\"" + studentsList[i].sid + "\"></td>";
	str += "<td>" + studentsList[i].exam_no + "</td>";
	str += "<td>" + studentsList[i].adm_no + "</td>";
	str += "<td><a href=\"./student.php?sid=" + studentsList[i].sid + "\"><nobr>" + studentsList[i].name + "</nobr></td>";
	str += "<td>" + teams[studentsList[i].team] + "</td>";
	str += "<td>" + houses[studentsList[i].house] + "</td>";
	str += "<td>" + teachers[studentsList[i].mentor].name + "</td></tr>\n";
    }
    t.innerHTML = str;
    $("#marks-div").html(t);
}


$(document).ready(function() {
    var f = $("#frameset span");
    $("#f1").click(function() {
	$(".framevals").slideUp();
	$("#frameval1").slideDown();
    });
    $("#f2").click(function() {
	$(".framevals").slideUp();
	$("#frameval2").slideDown();
    });
    $("#f3").click(function() {
	$(".framevals").slideUp();
	$("#frameval3").slideDown();
    });
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