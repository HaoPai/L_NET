var main = document.getElementById("main");
var nav = document.getElementById("nav");
var busy = document.getElementById("busy");
var log_area = document.getElementById("log_in");


var main_links = document.getElementsByClassName("main_link");
var main_sections = document.getElementsByClassName("main_section");
var all_patents = document.getElementById("all_patents");
var green_patents = document.getElementById("green_patents");
var show_patent = document.getElementById("show_patent");
var keywords = document.getElementById("keywords");
var remove_buttons = document.getElementsByClassName("remove_word");



for (var i = 0; i < main_links.length; i++) {
    main_links[i].onclick = function() {
        for (var j = 0; j < main_sections.length; j++) main_sections[j].style.display = "none";
        switch (this.id) {
            case "all_link":
                all_patents.style.display = "block";
                break;
            case "green_link":
                green_patents.style.display = "block";
                break;
            case "show_link":
                show_patent.style.display = "block";
                break;
            case "keywords_link":
                keywords.style.display = "block";
                break;
        }
    }
}



var green = document.getElementById("green");
var red = document.getElementById("red");
var select = document.getElementById("select");
select.selected = 1;
green.selected = 0;
red.selected = 0;


var select_buttons = document.getElementsByClassName("select_button");
for (var i = 0; i < select_buttons.length; i++) {
    select_buttons[i].onclick = function() {
        for (var j = 0; j < select_buttons.length; j++) {
            select_buttons[j].selected = "0";
        }

        this.selected = '1';
        show_select_button();
    }
}


var add_keyword = document.getElementById("add_keyword");
var keyword = document.getElementById("keyword");
add_keyword.onclick = function() {
    if (keyword.value == "") alert("请输入非空的关键词！")
    else {
        var word = keyword.value;
        var path = "http://www.l-net.cn/patent/data.php?add_word&word=" + word;
        ajax_send(null, path, this, function(en, response) {
            if (response) alert(response);
            get_patent();
            get_words();
            get_all_patents();
            get_green_patents();
            main.style.display = "block";
            nav.style.display = "block";
            busy.style.display = "none";
        }, true);
        main.style.display = "none";
        nav.style.display = "none";
        busy.style.display = "block";
    }
}


var all_prev_button = all_patents.getElementsByClassName("prev")[0];
all_prev_button.onclick = function() {
    var page_span = all_patents.getElementsByClassName("page")[0];
    var page = parseInt(page_span.innerHTML);
    if (page > 1)
        get_all_patents(page - 1);
}


var all_next_button = all_patents.getElementsByClassName("next")[0];
all_next_button.onclick = function() {
    var page_span = all_patents.getElementsByClassName("page")[0];
    var page = parseInt(page_span.innerHTML);
    if (page < 6638)
        get_all_patents(page + 1);
}


var green_prev_button = green_patents.getElementsByClassName("prev")[0];
green_prev_button.onclick = function() {
    var page_span = green_patents.getElementsByClassName("page")[0];
    var page = parseInt(page_span.innerHTML);
    if (page > 1)
        get_green_patents(page - 1);
}



var green_next_button = green_patents.getElementsByClassName("next")[0];
green_next_button.onclick = function() {
    var page_span = green_patents.getElementsByClassName("page")[0];
    var page = parseInt(page_span.innerHTML);
    if (page < 6638)
        get_green_patents(page + 1);
}








log_in();
get_patent();
get_words();
get_all_patents(1);
get_green_patents();




function show_select_button() {
    if (green.selected == 0) {
        green.style.color = "#aaa";
        green.style.background = "#fff";
    } else {
        green.style.color = "#fff";
        green.style.background = "green";
    }

    if (red.selected == 0) {
        red.style.color = "#aaa";
        red.style.background = "#fff";
    } else {
        red.style.color = "#fff";
        red.style.background = "red";
    }

    if (select.selected == 0) {
        select.style.color = "#aaa";
    } else {
        select.style.color = "#000";
    }
}

var words_table = document.getElementById("words_table");


function get_words() {
    var words_table = document.getElementById("words_table");
    words_table.getElementsByTagName("tbody")[0].innerHTML = "";
    var path = "http://www.l-net.cn/patent/data.php?get_words";
    ajax_send(null, path, this, function(en, response) {
        var words = JSON.parse(response);
        var text = "";
        for (var i = 0; i < words.length; i++) {
            var line = "<tr><td>" + words[i].word_content +
                "</td><td> " + words[i].reference_count + '</td><td ><div  class = "remove_word"  data-id = "' + words[i].word_id + '"  >删除</div></td></tr>';
            text += line;
        }
        words_table.getElementsByTagName("tbody")[0].innerHTML = text;

        var remove_buttons = document.getElementsByClassName("remove_word");
        for (var i = 0; i < remove_buttons.length; i++) {
            remove_buttons[i].onclick = function() {
                var id = this.getAttribute("data-id");
                var path = "http://www.l-net.cn/patent/data.php?remove_word&id=" + id;
                ajax_send(null, path, this, function(en, response) {
                    if (response) alert(response);
                    get_patent();
                    get_words();
                    get_all_patents();
                    get_green_patents();
                    main.style.display = "block";
                    nav.style.display = "block";
                    busy.style.display = "none";
                }, true);
                main.style.display = "none";
                nav.style.display = "none";
                busy.style.display = "block";
            }
        }
    }, true);
}

function get_all_patents(page) {
    if (!page) page = 1;
    var all_patents_table = document.getElementById("all_patents_table");
    all_patents_table.getElementsByTagName("tbody")[0].innerHTML = "";
    var path = "http://www.l-net.cn/patent/data.php?get_all_patents&page=" + page;
    ajax_send(null, path, this, function(en, response) {
        var words = JSON.parse(response);
        var text = "";
        for (var i = 0; i < words.length; i++) {
            var line = '<tr><td > <div class = "patent_title" data-id = "' + words[i].patent_id + '">' + words[i].patent_title +
                '</div></td><td > <div class = "ap_company">' + words[i].ap_company + '</div></td>' +
                '<td><div>' + words[i].sector_code + '</div></td>' +
                '<td class = "key_num">' +
                words[i].key_num + "</td></tr>"
            text += line;
        }
        all_patents_table.getElementsByTagName("tbody")[0].innerHTML = text;
        var patent_titles = all_patents_table.getElementsByClassName("patent_title");
        for (var i = 0; i < patent_titles.length; i++) {
            patent_titles[i].onclick = function() {
                get_patent(this.getAttribute("data-id"));
                all_patents.style.display = "none";
                show_patent.style.display = "block";
                this.parentNode.parentNode.className = "clicked";
            }
        }
        var page_span = all_patents.getElementsByClassName("page")[0];
        page_span.innerHTML = page;
    }, true);
}

function get_green_patents(page) {
    if (!page) page = 1;
    var green_patents_table = document.getElementById("green_patents_table");
    green_patents_table.getElementsByTagName("tbody")[0].innerHTML = "";
    var path = "http://www.l-net.cn/patent/data.php?get_green_patents&page=" + page;
    ajax_send(null, path, this, function(en, response) {
        var words = JSON.parse(response);
        var text = "";
        for (var i = 0; i < words.length; i++) {
            var line = '<tr><td  ><div class =  "patent_title"  data-id = "' + words[i].patent_id + '"> ' + words[i].patent_title +
                '</div></td><td > <div class = "ap_company">' + words[i].ap_company + '</div></td><td class = "key_num">' +
                words[i].key_num + "</td></tr>"
            text += line;
        }
        green_patents_table.getElementsByTagName("tbody")[0].innerHTML = text;
        var patent_titles = green_patents_table.getElementsByClassName("patent_title");
        for (var i = 0; i < patent_titles.length; i++) {
            patent_titles[i].onclick = function() {
                get_patent(this.getAttribute("data-id"));
                green_patents.style.display = "none";
                show_patent.style.display = "block";
                this.parentNode.parentNode.className = "clicked";
            }
        }
        var page_span = green_patents.getElementsByClassName("page")[0];
        page_span.innerHTML = page;
    }, true);
}


function log_in() {
    var user = document.getElementById("user");
    var path = "http://www.l-net.cn/patent/data.php?log_in";
    ajax_send(null, path, this, function(en, response) {
        if (response) {
            var data = JSON.parse(response);
            user.innerHTML = data.user_name;
            user.user_id = data.user_id;
        } else {
            main.style.display = "none";
            nav.style.display = "none";
            log_area.style.display = "block";
        }
    }, true);

}


function get_patent(id) {
    if (id)
        var path = "http://www.l-net.cn/patent/data.php?get_patent&id=" + id;
    else
        var path = "http://www.l-net.cn/patent/data.php?get_patent";
    ajax_send(null, path, this, fill_patent, true);
}

function fill_patent(en, response) {
    var patent = JSON.parse(response);
    var stock_code = document.getElementById("stock_code");
    var ap_company = document.getElementById("ap_company");
    var sector_name = document.getElementById("sector_name");
    var patent_title = document.getElementById("patent_title");
    var patent_abstract = document.getElementById("patent_abstract");
    var words_div = document.getElementById("found_words");
    var lock_user = document.getElementById("lock_user");
    var normal_div = document.getElementById("normal");
    var locked_div = document.getElementById("locked");
    var checked_div = document.getElementById("checked");

    var submit_button = document.getElementById("submit_patent");
    var next_buttons = document.getElementsByClassName("next_button");

    submit_button.patent_id = patent.patent_id;

    for (var i = 0; i < next_buttons.length; i++)
        next_buttons[i].onclick = function() {
            get_patent();
        }

    submit_button.onclick = function() {
        if (green.selected == 0 && red.selected == 0) {
            alert("请选择！")
        } else {
            var green_tag = (green.selected == 1) ? 1 : 0;
            var patent_id = this.patent_id;
            var path = "http://www.l-net.cn/patent/data.php?set_green_tag&patent_id=" + patent_id + "&green_tag=" + green_tag;
            ajax_send(null, path, this, function(en, response) {
                get_patent(patent_id);
            }, true);
        }
    }


    stock_code.innerHTML = patent.stock_code;
    ap_company.innerHTML = patent.ap_company;
    sector_name.innerHTML = patent.sector_code + "  (" + patent.sector_name + ")";
    patent_title.innerHTML = patent.patent_title;
    patent_abstract.innerHTML = patent.patent_abstract;
    var text_words = "";
    for (var i = 0; i < patent.keywords.length; i++) {
        var line = "<span>" + patent.keywords[i] + "</span>";
        text_words += line;
    }
    words_div.innerHTML = text_words;
    select.click();

    normal_div.style.display = "block";
    locked_div.style.display = "none";
    checked_div.style.display = "none";

    if (patent.green_tag == -1 && patent.user_id != patent.your_id) {
        lock_user.innerHTML = patent.user_name;
        normal_div.style.display = "none";
        locked_div.style.display = "block";
    }

    if (patent.green_tag != -1) {
        normal_div.style.display = "none";
        checked_div.style.display = "block";
        check_user = document.getElementById("check_user");
        check_result = document.getElementById("check_result");
        check_time = document.getElementById("check_time");
        result = (patent.green_tag == 1) ? "绿色" : "非绿色";
        check_user.innerHTML = patent.user_name;
        check_result.innerHTML = result;
        check_time.innerHTML = patent.check_time;

    }
}


function ajax_send(sendData, path, en, fun, method) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status >= 200 && xhr.status < 300) {

                fun(en, xhr.responseText);
            } else {
                //alert("ajax 失败！");
            }
        }
    }
    if (method) {

        xhr.open("POST", path, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    } else {
        xhr.open("GET", path, true);
    }
    xhr.send(sendData);
}