var tabl,subjects,marks,classId,examId;
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


function updateExamInfo(cid,eid) {
    classId = cid;
    examId = eid;
    if(examId == 0) return;
    $.getJSON("./ajax.php?class=" + classId + "&exam=" + examId, function(data) {
	//console.log(data);
	subjects = data.subjects; marks = data;
	sortByExamNo();
	$("#sorter").show();
    });
}

function generateStudentMapping() {
    for(var i = 1; i < studentsList.length; ++i) {
	studentMapping[studentsList[i].sid] = i;
    }
    //console.log(studentMapping);
}

function sortByExamNo() {
	var t = document.createElement("table");
	var str = "<tr><th>S.No</th><th>Exm No</th><th>Adm No</th><th>Name</th>";
	for(var i=0;i<subjects.length; ++i) {
	    str += "<th style=\"width:40px; \"><a href=\"./?class=" + classId + "&exam=" + examId + "&editmarks=" + subjects[i]["code"] + "\">" + subjects[i]["name"] + "</a></th>";
	}
	str += "<th>Total</th><th>Avg</th><th>Rank (" + marks.class_total_strength + ")</th></tr>\n";
	t.setAttribute("id","studentsTable");
	t.setAttribute("border","1");
	t.setAttribute("cellpadding","0");
	t.setAttribute("cellspacing","0");console.log(studentsList);
	for(var i=1; i < studentsList.length; ++i) {
	    str += "<tr><td>" + i + "</td>";
	    str += "<td>" + studentsList[i].exam_no + "</td>";
	    str += "<td>" + studentsList[i].adm_no + "</td>";
	    str += "<td><a href=\"./student.php?sid=" + studentsList[i].sid + "\"><nobr>" + studentsList[i].name + "</nobr></td>";
	    
	    var sum = 0, count = 0;
	    for(var j=0;j<subjects.length; ++j) {
		currmark = parseInt(marks[studentsList[i].sid][subjects[j]["code"]]);
		if(marks[studentsList[i].sid][subjects[j]["code"]] != "ab") {
		    sum += currmark;
		    ++count;
		}
		//console.log(studentsList[i].sid + " - " + subjects[j]["code"]);
		str += "<td";
		if(currmark < 40) str += " class=\"red\"";
		if(marks[studentsList[i].sid][subjects[j]["code"]] == "ab") str += " class=\"red-absent\"";
		str += ">" + marks[studentsList[i].sid][subjects[j]["code"]] + "</td>";
	    }
	    str += "<td>" + sum + "</td><td";
	    if(sum/count < 50) str += " class=\"red\"";
	    str += ">" + sum/count + "</td>";
	    str += "<td>" + marks[studentsList[i].sid].rank + "</td>";

	}
	t.innerHTML = str;
	$("#marks-div").html(t);
	$("#examName").html(" - " + marks.exam_name);    
}

function sortByRank() {
    var t = document.createElement("table");
    var str = "<tr><th>S.No</th><th>Exm No</th><th>Adm No</th><th>Name</th>";
    for(var i=0;i<subjects.length; ++i) {
	str += "<th style=\"width:40px; \"><a href=\"./?class=" + classId + "&exam=" + examId + "&editmarks=" + subjects[i]["code"] + "\">" + subjects[i]["name"] + "</a></th>";
    }
    str += "<th>Total</th><th>Avg</th><th>Rank (" + marks.class_total_strength + ")</th></tr>\n";
    t.setAttribute("id","studentsTable");
    t.setAttribute("border","1");
    t.setAttribute("cellpadding","0");
    t.setAttribute("cellspacing","0");console.log(marks);
    var index = 1;
    for(var temp = 0; temp < marks.ranklist.length; ++temp, ++index) {
	var i = studentMapping[marks.ranklist[temp]];
	str += "<tr><td><span>" + index + "</span></td>";
	str += "<td>" + studentsList[i].exam_no + "</td>";
	str += "<td>" + studentsList[i].adm_no + "</td>";
	str += "<td><a href=\"./student.php?sid=" + studentsList[i].sid + "\"><nobr>" + studentsList[i].name + "</nobr></td>";
	
	var sum = 0, count = 0;
	for(var j=0;j<subjects.length; ++j) {
	    currmark = parseInt(marks[studentsList[i].sid][subjects[j]["code"]]);
	    if(marks[studentsList[i].sid][subjects[j]["code"]] != "ab") {
		sum += currmark;
		++count;
	    }
	    //console.log(studentsList[i].sid + " - " + subjects[j]["code"]);
	    str += "<td";
	    if(currmark < 40) str += " class=\"red\"";
	    if(marks[studentsList[i].sid][subjects[j]["code"]] == "ab") str += " class=\"red-absent\"";
	    str += ">" + marks[studentsList[i].sid][subjects[j]["code"]] + "</td>";
	}
	str += "<td>" + sum + "</td><td";
	if(sum/count < 50) str += " class=\"red\"";
	str += ">" + sum/count + "</td>";
	str += "<td>" + marks[studentsList[i].sid].rank + "</td>";
	
    }
    t.innerHTML = str;
    $("#marks-div").html(t);
    $("#examName").html(" - " + marks.exam_name);
console.log("sorting by rank");    
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
    $("#selectExam").val(0);
    $("#sorter").hide();
});