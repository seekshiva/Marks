var menuTimer,timerMenuName;
var tabl,subjects,marks,classId,examId,harr;
var sortString = '<div class="np"><span id="sorter">Sort by: <a href="#" onclick="sortByExamNo(); return false;">Exam No.</a> <a href="#" onclick="sortByRank(); return false;">Rank</a></span></div>';
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
    //window.location = "#!list";
    setHouseInfo();
    selectTab(0);
    var str = "<tr><th><span class=\"op\">S.No</span></th><th>Adm No</th><th>Exm No</th><th>Name</th><th>Team</th><th>House</th><th>Mentor</th></tr>\n",
    t = document.createElement("table");
    t.setAttribute("id","studentsTable");
    t.setAttribute("border","1");
    t.setAttribute("cellpadding","0");
    t.setAttribute("cellspacing","0");
    for(var i=1; i < studentsList.length; ++i) {
	str += "<tr><td onclick=\"this.childNodes[1].checked = !this.childNodes[1].checked; if($(this).parent().attr('class') == 'cb_selected') $(this).parent().attr('class',''); else $(this).parent().attr('class','cb_selected'); \" style=\"cursor:pointer; \"><span class=\"op\">" + i + "</span><input class=\"np\" type=\"checkbox\" name=\"uids[]\" value=\"" + studentsList[i].sid + "\"></td>";
	str += "<td>" + studentsList[i].adm_no + "</td>";
	str += "<td>" + studentsList[i].exam_no + "</td>";
	str += "<td><a href=\"./student.php?sid=" + studentsList[i].sid + "\"><nobr>" + studentsList[i].name + "</nobr></td>";
	str += "<td>" + teams[studentsList[i].team] + "</td>";
	var link = "";
	if(houses[studentsList[i].house] != "-") link = " href=\"?house=" + studentsList[i].house + "\"";
	str += "<td><a" + link + ">" + houses[studentsList[i].house] + "</a></td>";
	str += "<td>" + teachers[studentsList[i].mentor].name + "</td></tr>\n";
    }
    str += "<script>$(\"input[type=checkbox] \").click(function(e) {e.stopPropagation(); $(this).parent().parent().attr('class',($(this).parent().parent().attr('class') == \"cb_selected\")?\"\":\"cb_selected\"); });</script>";
    t.innerHTML = str;
    $("#marks-div").html(t);
    $("#examName").html("");
}


function updateExamInfo(cid,eid) {
    classId = cid;
    examId = eid;
    console.log("updating... " + cid + " - " + eid);
    if(examId == 0) return;
    $.getJSON("./ajax.php?class=" + classId + "&exam=" + examId, function(data) {
	//console.log(data);
	subjects = data.subjects;
	no_avg_subjects = data.no_avg_subjects;
	marks = data;
	if(harr[2][0] == "analyse") "1";
	else if(harr[2][0] == "list") sortByExamNo();
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
    if(!marks) {
	alert("Select an exam from the right corner to view the marks");
	return;
    }
    window.location = "#!class:" + classId + "|exam:" + examId + "|list";
    var t = document.createElement("table");
    var str = "<caption>" + sortString + "</caption>";
    selectTab(1);
    str += "<tr><th>S.No</th><th>Exm No</th><th>Adm No</th><th>Name</th>";
    for(var i=0;i<subjects.length; ++i) {
	str += "<th style=\"width:40px; \"><a href=\"./?class=" + classId + "&exam=" + examId + "&editmarks=" + subjects[i]["code"] + "\">" + subjects[i]["name"] + "</a></th>";
    }
    str += "<th>Total</th><th>Avg</th><th>Rank (" + marks.class_total_strength + ")</th>\n";
    for(var i=0;i<no_avg_subjects.length; ++i) {
	str += "<th style=\"width:40px; \"><a href=\"./?class=" + classId + "&exam=" + examId + "&editmarks=" + no_avg_subjects[i]["code"] + "\">" + no_avg_subjects[i]["name"] + "</a></th>";
    }
    str += "</tr>\n";
    t.setAttribute("id","studentsTable");
    t.setAttribute("border","1");
    t.setAttribute("cellpadding","0");
    t.setAttribute("cellspacing","0");//console.log(studentsList);
    for(var i=1; i < studentsList.length; ++i) {
	str += "<tr><td>" + i + "</td>";
	str += "<td>" + studentsList[i].exam_no + "</td>";
	str += "<td>" + studentsList[i].adm_no + "</td>";
	str += "<td><a href=\"./student.php?sid=" + studentsList[i].sid + "\"><nobr>" + studentsList[i].name + "</nobr></td>";
	
	var sum = 0, count = 0;
	for(var j=0;j<subjects.length; ++j) {
	    var currmark = 0,flag = false;
	    if(typeof(marks[studentsList[i].sid]) != "undefined") {
		if(marks[studentsList[i].sid][subjects[j]["code"]] == "ab") {
		    currmark = "ab";
		}
		else if(marks[studentsList[i].sid][subjects[j]["code"]] != "") {
		    currmark = parseInt(marks[studentsList[i].sid][subjects[j]["code"]]);
		    flag = true;
		}
		//console.log(marks[studentsList[i].sid][subjects[j]["code"]]);
	    }
	    if(typeof(marks[studentsList[i].sid]) != "undefined")
		if(flag) {
		    sum += currmark;
		    ++count;
		}
	    //console.log(studentsList[i].sid + " - " + subjects[j]["code"]);
	    str += "<td";
	    if(currmark < 40 && flag) str += " class=\"red\"";
	    if(currmark == "ab") str += " class=\"red-absent\"";
	    str += " style=\"text-align:center; \">" + currmark + "</td>";
	}
	str += "<td style=\"text-align:center; \">" + sum + "</td><td";
	if(count == 0) avg = 0;
	else avg = sum/count;
	avg = avg * 100 / marks.exam_max_marks;
	if(avg < 50 && avg > 0) str += " class=\"red\"";
	if(avg.toString().indexOf(".") != -1) {
	    avg = avg.toString()
	    avg = parseFloat(avg.substr(0,avg.indexOf(".") + 3));
	}
	str += " style=\"text-align:center; \">" + avg + "</td>";
	if(typeof(marks[studentsList[i].sid]) == "undefined") rank = "-";
	else rank = marks[studentsList[i].sid].rank;
	str += "<td style=\"text-align:center; \">" + rank + "</td>";
	for(var j=0;j<no_avg_subjects.length; ++j) {
	    var currmark = 0,flag = false;
	    if(typeof(marks[studentsList[i].sid]) != "undefined") {
		if(marks[studentsList[i].sid][no_avg_subjects[j]["code"]] == "ab") {
		    currmark = "ab";
		}
		else if(marks[studentsList[i].sid][no_avg_subjects[j]["code"]] != "") {
		    currmark = parseInt(marks[studentsList[i].sid][no_avg_subjects[j]["code"]]);
		    flag = true;
		}
		//console.log(marks[studentsList[i].sid][no_avg_subjects[j]["code"]]);
	    }
	    str += "<td";
	    if(currmark < 40 && flag) str += " class=\"red\"";
	    if(currmark == "ab") str += " class=\"red-absent\"";
	    str += " style=\"text-align:center; \">" + currmark + "</td>";
	}
	str += "</tr>";
    }
    t.innerHTML = str;
    $("#marks-div").html(t);
    $("#examName").html(" - " + marks.exam_name + " (" + marks.exam_max_marks + " marks)");
}

function sortByRank() {
    if(!marks) {
	alert("Select an exam from the right corner to view the marks");
	return;
    }
    var t = document.createElement("table");
    var str = "<caption>" + sortString + "</caption>";
    str += "<tr><th>S.No</th><th>Exm No</th><th>Adm No</th><th>Name</th>";
    for(var i=0;i<subjects.length; ++i) {
	str += "<th style=\"width:40px; \"><a href=\"./?class=" + classId + "&exam=" + examId + "&editmarks=" + subjects[i]["code"] + "\">" + subjects[i]["name"] + "</a></th>";
    }
    str += "<th>Total</th><th>Avg</th><th>Rank (" + marks.class_total_strength + ")</th>\n";
    for(var i=0;i<no_avg_subjects.length; ++i) {
	str += "<th style=\"width:40px; \"><a href=\"./?class=" + classId + "&exam=" + examId + "&editmarks=" + no_avg_subjects[i]["code"] + "\">" + no_avg_subjects[i]["name"] + "</a></th>";
    }
    str += "</tr>\n";
    t.setAttribute("id","studentsTable");
    t.setAttribute("border","1");
    t.setAttribute("cellpadding","0");
    t.setAttribute("cellspacing","0");//console.log(marks);
    var index = 1;
    for(var temp = 0; temp < marks.ranklist.length; ++temp, ++index) {
	var i = studentMapping[marks.ranklist[temp]];
	str += "<tr><td><span>" + index + "</span></td>";
	str += "<td>" + studentsList[i].exam_no + "</td>";
	str += "<td>" + studentsList[i].adm_no + "</td>";
	str += "<td><a href=\"./student.php?sid=" + studentsList[i].sid + "\"><nobr>" + studentsList[i].name + "</nobr></td>";
	
	var sum = 0, count = 0;
	for(var j=0;j<subjects.length; ++j) {
	    var currmark = 0,flag = false;
	    console.log(marks[studentsList[i].sid]);
	    if(typeof(marks[studentsList[i].sid]) != "undefined") {
		if(marks[studentsList[i].sid][subjects[j]["code"]] == "ab") {
		    console.log("ab");
		    currmark = "ab";
		}
		else if(marks[studentsList[i].sid][subjects[j]["code"]] != "") {
		    currmark = parseInt(marks[studentsList[i].sid][subjects[j]["code"]]);
		    flag = true;
		}
		//console.log(marks[studentsList[i].sid][subjects[j]["code"]]);
	    }
	    //currmark = parseInt(marks[studentsList[i].sid][subjects[j]["code"]]);
	    if(flag) {
		sum += currmark;
		++count;
	    }
	    str += "<td";
	    if(currmark < 40 && flag) str += " class=\"red\"";
	    if(marks[studentsList[i].sid][subjects[j]["code"]] == "ab") str += " class=\"red-absent\"";
	    str += " style=\"text-align:center; \">" + currmark + "</td>";
	}
	str += "<td style=\"text-align:center; \">" + sum + "</td><td";
	if(count == 0) avg = 0;
	else avg = sum/count;
	if(avg < 50 && avg > 0) str += " class=\"red\"";
	if(avg.toString().indexOf(".") != -1) {
	    avg = avg.toString()
	    avg = parseFloat(avg.substr(0,avg.indexOf(".") + 3));
	}
	str += " style=\"text-align:center; \">" + avg + "</td>";
	str += "<td style=\"text-align:center; \">" + marks[studentsList[i].sid].rank + "</td>";
	
	for(var j=0;j<no_avg_subjects.length; ++j) {
	    var currmark = 0,flag = false;
	    if(typeof(marks[studentsList[i].sid]) != "undefined") {
		if(marks[studentsList[i].sid][no_avg_subjects[j]["code"]] == "ab") {
		    currmark = "ab";
		}
		else if(marks[studentsList[i].sid][no_avg_subjects[j]["code"]] != "") {
		    currmark = parseInt(marks[studentsList[i].sid][no_avg_subjects[j]["code"]]);
		    flag = true;
		}
		//console.log(marks[studentsList[i].sid][no_avg_subjects[j]["code"]]);
	    }
	    str += "<td";
	    if(currmark < 40 && flag) str += " class=\"red\"";
	    if(currmark == "ab") str += " class=\"red-absent\"";
	    str += " style=\"text-align:center; \">" + currmark + "</td>";
	}
	str += "</tr>";
    }
    t.innerHTML = str;
    $("#marks-div").html(t);
    $("#examName").html(" - " + marks.exam_name + " (" + marks.exam_max_marks + " marks)");
}

function analyse() {
    if(!marks) {
	alert("Select an exam from the right corner to view the marks");
	return;
    }
    window.location = "#!class:" + classId + "|exam:" + examId + "|analyse";
    var range = Array(), str = "<table border=\"1\" cellspacing=\"0\"><tr><th>Subject</th>";
    selectTab(2);
    for(var i=0;i<subjects.length; ++i) {
	str += "<th style=\"width:40px; \"><a href=\"./?class=" + classId + "&exam=" + examId + "&editmarks=" + subjects[i]["code"] + "\">" + subjects[i]["name"] + "</a></th>";
    }
    for(var i=0;i<no_avg_subjects.length; ++i) {
	str += "<th style=\"width:40px; \"><a href=\"./?class=" + classId + "&exam=" + examId + "&editmarks=" + no_avg_subjects[i]["code"] + "\">" + no_avg_subjects[i]["name"] + "</a></th>";
    }
    str += "</tr>\n";
    str += "<tr><th>Teacher</th>";
    for(var i=0;i<subjects.length; ++i) {
	str += "<td style=\"text-align:center; \" class=\"s\">N/A</td>";
    }
    for(var i=0;i<no_avg_subjects.length; ++i) {
	str += "<td style=\"text-align:center; \" class=\"s\">N/A</td>";
    }
    str += "</tr>\n";

    for(var j = 0; j < subjects.length; ++j) {
	range[subjects[j]["code"]] = Array();
	range[subjects[j]["code"]]["ab"] = 0;
	range[subjects[j]["code"]]["90"] = 0;
	range[subjects[j]["code"]]["80"] = 0;
	range[subjects[j]["code"]]["70"] = 0;
	range[subjects[j]["code"]]["60"] = 0;
	range[subjects[j]["code"]]["50"] = 0;
	range[subjects[j]["code"]]["f"] = 0;
    }
    for(var j = 0; j < no_avg_subjects.length; ++j) {
	range[no_avg_subjects[j]["code"]] = Array();
	range[no_avg_subjects[j]["code"]]["ab"] = 0;
	range[no_avg_subjects[j]["code"]]["90"] = 0;
	range[no_avg_subjects[j]["code"]]["80"] = 0;
	range[no_avg_subjects[j]["code"]]["70"] = 0;
	range[no_avg_subjects[j]["code"]]["60"] = 0;
	range[no_avg_subjects[j]["code"]]["50"] = 0;
	range[no_avg_subjects[j]["code"]]["f"] = 0;
    }
    
    for(var i = 1; i < studentsList.length; ++i) {
	for(var j = 0; j < subjects.length; ++j) {
	    //console.log(subjects[j]["code"]);
	    var mark = marks[studentsList[i].sid][subjects[j]["code"]];
	    if(mark == "ab") {
		range[subjects[j]["code"]]["ab"]++;
		continue;
	    }
	    mark = mark * 100 / marks.exam_max_marks;
	    if(mark >= 90) {
		range[subjects[j]["code"]]["90"]++;
		continue;		
	    }
	    else if(mark >= 80) {
		range[subjects[j]["code"]]["80"]++;
		continue;		
	    }
	    else if(mark >= 70) {
		range[subjects[j]["code"]]["70"]++;
		continue;		
	    }
	    else if(mark >= 60) {
		range[subjects[j]["code"]]["60"]++;
		continue;		
	    }
	    else if(mark >= 50) {
		range[subjects[j]["code"]]["50"]++;
		continue;		
	    }
	    else {
		range[subjects[j]["code"]]["f"]++;
		continue;		
	    }
	}
    }
    
    for(var i = 1; i < studentsList.length; ++i) {
	for(var j = 0; j < no_avg_subjects.length; ++j) {
	    //console.log(subjects[j]["code"]);
	    var mark = marks[studentsList[i].sid][no_avg_subjects[j]["code"]];
	    if(mark == "ab") {
		range[no_avg_subjects[j]["code"]]["ab"]++;
		continue;
	    }
	    mark = mark * 100 / marks.exam_max_marks;
	    if(mark >= 90) {
		range[no_avg_subjects[j]["code"]]["90"]++;
		continue;		
	    }
	    else if(mark >= 80) {
		range[no_avg_subjects[j]["code"]]["80"]++;
		continue;		
	    }
	    else if(mark >= 70) {
		range[no_avg_subjects[j]["code"]]["70"]++;
		continue;		
	    }
	    else if(mark >= 60) {
		range[no_avg_subjects[j]["code"]]["60"]++;
		continue;		
	    }
	    else if(mark >= 50) {
		range[no_avg_subjects[j]["code"]]["50"]++;
		continue;		
	    }
	    else {
		range[no_avg_subjects[j]["code"]]["f"]++;
		continue;		
	    }
	}
    }
    var temp = ["90","80","70","60","50","f","ab"],
    temp2 = {"90":"90-100","80":"80-89","70":"70-79","60":"60-69","50":"50-59","f":"< 50","ab":"ab"};

    
    for(var i = 0; i < temp.length; ++i) {
	str += "<tr><th>"+ temp2[temp[i]] + "</th>";
	for(var j = 0; j < subjects.length; ++j) {
	    str += "<td style=\"text-align:center; \">" + range[subjects[j]["code"]][temp[i]] + "</td>";
	}
	for(var j = 0; j < no_avg_subjects.length; ++j) {
	    str += "<td style=\"text-align:center; \">" + range[no_avg_subjects[j]["code"]][temp[i]] + "</td>";
	}
	str += "</tr>\n";
    }
    str += "<tr><th>Class Avg</th>";
    for(var j = 0; j < subjects.length; ++j) {
	str += "<td style=\"text-align:center; \">" + marks.class_avg[subjects[j]["code"]] + "</td>";
    }
    for(var j = 0; j < no_avg_subjects.length; ++j) {
	str += "<td style=\"text-align:center; \">" + marks.class_avg[no_avg_subjects[j]["code"]] + "</td>";
    }
    str += "</tr></table>";
    $("#marks-div").html(str);
}

function  selectTab(tabNo) {
    $("#tabbed div").css({"display":"inline-block"});
    if(tabNo == 0) $("#changer").show(0);
    else  $("#changer").hide(0);
    $("#hl").attr("id","");
    $($("#tabbed div")[tabNo]).attr("id","hl");    
}

function urlParse() {
    var h = window.location.hash;
    h = h.substr(2);
    harr = h.split("|");
    for(var i = 0; i< harr.length; ++i) {
	harr[i] = harr[i].split(":");
    }
    if(harr[0][0] == "class") {
	if(classId != harr[0][1]) {
	    classId = harr[0][1];
	    $("#wrapper").load("./template.php?class=" + classId,function() {
		$("#optionHead").click(function(e) {
		    $("#optionBody").slideToggle();
		    if(localStorage["optionDown"] == "1") {
			localStorage["optionDown"] = 0;
		    }
		    else {
			localStorage["optionDown"] = "1";
		    }
		});
		if(harr.length > 1 && harr[1][0] == "exam") {
		    examId = harr[1][1];
		    updateExamInfo(classId,examId);
		}
	    });
	}
	else {
	    if(harr.length > 1 && harr[1][0] == "exam") {
		examId = harr[1][1];
		updateExamInfo(classId,examId);
	    }
	    else {
		getStudentsFromClass();
	    }
	}
    }
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
    if(localStorage) {
	if(localStorage["optionDown"] == "1") {
	    $("#optionBody").slideDown(0);
	}
    }
    $("#selectExam").val(0);
    $("#sorter").hide();
    urlParse();
});

window.onhashchange = function() {
    urlParse();
    console.log("hi - " + window.location.hash);
}