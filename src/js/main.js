var xmlHttp = createXmlHttpRequestObject();

function d(param) {

    console.log(param);
}

function createXmlHttpRequestObject() {

    var xmlHttp;

    if(window.ActiveXObject) {

        try {

            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP")

        } catch (e) {

            xmlHttp = false;
        }
    } else {

        try {

            xmlHttp = new XMLHttpRequest();

        } catch (e) {

            xmlHttp = false;
        }
    }

    if(!xmlHttp) {

        alert("Error creating the XMLHttpRequest object");
    } else {

        return xmlHttp;
    }
}


function ajaxVS(data) {

    var param = {

        url: data.url || 'http://onbox.dev/',
        type: data.type || 'post',
        data: data.p || false,
        res: data.res || false,
        idForm: data.idForm || false
    };

    ajax(param);
}

function ajax(param) {

    if(xmlHttp.readyState == 4 || xmlHttp.readyState == 0) {


        xmlHttp.open(param.type, param.url, true);

        if(param.data == false) {

            var form = document.getElementById(param.idForm);

            p = new FormData(form);

        } else {

            p = param.data;
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        }


        xmlHttp.onreadystatechange = function () {

            if(param.res == true) {

                if (xmlHttp.readyState == 4) {

                    if (xmlHttp.status == 200) {

                        var arr = [];


                        var text = parsText(xmlHttp.response);

                        var obj = JSON.parse(text[0]);

                        if (!obj.html) {

                            var i = 0;
                            for (key in obj) {

                                arr[i] = key + '=' + obj[key];

                                i++;
                            }

                            q = arr.join('&');

                            // window.location.search = q;

                        } else {

                            document.getElementsByClassName('show_modal')[0].style.display = "block";
                            document.getElementById('show_modal').innerHTML = obj.html;
                        }

                    } else {

                        alert('Ошибка сервира');
                    }
                }
            } else {

                 location.reload();
            }

        };

        xmlHttp.send(p);
    }
}


function parsText(text) {

    return text.match(/{.*}/);
}




















