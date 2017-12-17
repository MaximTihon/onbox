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
        callback: data.callback || null,
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


        if(param.callback != null) {

            xmlHttp.onreadystatechange = function () {

                if (xmlHttp.readyState == 4) {

                    if (typeof param.callback == 'string') {

                         eval(param.callback + "()");
                    }
                }
            }

        } else {

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

                            }  else {

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
        }

        xmlHttp.send(p);
    }
}


function parsText(text) {

    return text.match(/{.*}/);
}


var cart = function(p) {

     text = parsText(xmlHttp.response);
     var obj = JSON.parse(text[0]);

     document.querySelectorAll('.wrapper-message-cart')[0].style.display = 'flex';

     // document.getElementById('message-cart').style.display = 'block';
     document.getElementById('message-cart').innerHTML = obj.message;

     return false;
}


var showMailBlock = function () {

    var obj = document.getElementById('mail-block');

    if(obj.style.display == 'block') {

        obj.style.display = 'none';

    } else {

        obj.style.display = 'block';
    }

}

var mailBlock = function () {

    var obj = document.querySelector('.mail-block>.form');

    obj.innerHTML = '<div class="close-form" onclick="showMailBlock()">x</div> <div class="mail-block-add">Вы подписались на рассылку onBox.by</div>';
}


var toys = {

     showBlock: function () {

         var obj = document.querySelectorAll('.wrapper-toys');
         var arr = document.querySelectorAll('.arrow-menu');

         if(obj[0].style.display === 'none' || obj[0].style.display === '' ) {

             obj[0].style.display = 'flex';
             arr[0].style.backgroundPosition = '0 -17px';
         }
    },

    hideBlock: function () {

        var obj = document.querySelectorAll('.wrapper-toys');
        var arr = document.querySelectorAll('.arrow-menu');

        if(obj[0].style.display === 'flex') {

            obj[0].style.display = 'none';
            arr[0].style.backgroundPosition = '0 0';
        }
    },

    getBlockToys: function(id) {

         ajaxVS({url:'http://onbox.dev/toys/get_toys/'+id, p:'proc=process', callback:'SetBlockToys'});

    }
}



function SetBlockToys() {

    var answer =  parsText(xmlHttp.response);
    var html = '';
    var ar = [];
    var obj = document.querySelectorAll('#category-toys');

   if(answer) {

       ar = JSON.parse(answer[0]);

       for( var val in ar['answer']) {

         var p = ar['answer'][val];

         var tg =  document.createElement('li');

            tg.setAttribute('onclick', 'location.assign(\'/showcase/brand/'+p.id_brand+'\')');
            tg.innerHTML = '<h1>'+p.name_brand+'</h1><div class="img-block-toys"><img src="/file/brand/'+p.img_brand+'" alt="'+p.name_brand+'"></div>';

               html += tg.outerHTML;
       }

       if(html !== '') {

           obj[0].innerHTML = html;
       }

   } else {

       obj[0].innerHTML = 'Подкатегории еще не добавили';
   }
}


var hide = {

    modal_dialog : function (cls) {

        document.querySelectorAll('.'+cls)[0].style.display = 'none';

    }
}

var carts = {

    sumPrice: function () {

       var obj = document.querySelectorAll('.count_price');

       if(obj) {

           var s = 0;

           for (var k in obj) {

               if(!isNaN(Number(obj[k].innerHTML))) {

                   s += Number(obj[k].innerHTML) ;
               }
           }

           document.querySelector('.sum_order').value = s;
           document.querySelector('.finish-price').innerHTML = 'ИТОГО: '+ s + ' руб'
       }

    },

    onePlus: function (id, price) {

       var count = ++document.querySelector('.count_order_'+id).value;
       var obj = document.querySelector('.price_'+id);

        document.querySelector('.count_'+id).value = count;

        obj.innerHTML = count * price;

        carts.sumPrice();
    },

    oneMinus: function (id,price) {

        if(document.querySelector('.count_order_'+id).value != 1) {

            var count = --document.querySelector('.count_order_'+id).value;
            var obj = document.querySelector('.price_'+id);

            document.querySelector('.count_'+id).value = count;

            obj.innerHTML = count * price;

            carts.sumPrice();
        }
    }
}

var productDisplay = {

    miniPic: function(image) {

        var obj = document.querySelector('.main-pic>img');

        obj.setAttribute('src', image);
    },

    increasePic: function () {

        var obj = document.querySelector('.main-pic>img');

    }
}


function addPageProduct() {

    d(10);
}

















