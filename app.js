var THC = {};

THC.total = 1;
THC.open = 1;

THC.init = function(config){
  THC.config = config;
}

THC.start = function(){
  $.ajax({
    method: "GET",
    url: THC.config.urls.total
  }).done(function( msg ) {
    THC.total = parseInt(msg);
    $("#total").html(THC.total);

    $.ajax({
      method: "GET",
      url: THC.config.urls.open
    }).done(function( msg ) {
      THC.open = parseInt(msg);
      $("#done").html(THC.total-THC.open);

      var p = (THC.total-THC.open) / THC.total;
      p = p * 100;
      p = Math.floor(p);
      $("#percentage").html(p+"%");
      $(".progress-bar").css("width", p+"%");
    });

  });


  $.ajax({
    method: "GET",
    url: THC.config.urls.next
  }).done(function( msg ) {
    if(msg == "error"){
      $(".control-wrapper").html("Keine Daten mehr verfügbar");
    }
    THC.currentId = msg;
    console.log(THC.currentId);
    $(".image").attr("src", THC.config.urls.img+THC.currentId+THC.config.urls.filetype);
  });

  $(".control-wrapper").html("");
  $.map(THC.config.control.list, function(val, i){
    var style = "background:"+val.color+";border:none";
    $(".control-wrapper").append('<button id="'+val.id+'" style="'+style+'" class="btn-block btn-primary btn-control btn-lg btn btn-default" type="button">'+val.value+'</button>');
  });

  $(".btn-control").click(function(){
    var id = $(this).attr("id");
    var url = THC.config.urls.set;
    url = url.replace("{{id}}", THC.currentId);
    url = url.replace("{{value}}", id);

    $.ajax({
      method: "GET",
      url: url
    }).done(function( msg ) {
      $.ajax({
        method: "GET",
        url: THC.config.urls.next
      }).done(function( msg ) {
        if(msg == "error"){
          $(".control-wrapper").html("Keine Daten mehr verfügbar");
          return;
        }
        THC.setCurrent(msg);
        $("#counter").html(parseInt($("#counter").html())+1);
        THC.open--;
        $("#done").html(THC.total - THC.open);

        var p = (THC.total-THC.open) / THC.total;
        p = p * 100;
        p = Math.round(p);
        $("#percentage").html(p+"%");
        $(".progress-bar").css("width", p+"%");
      });
    });

  });

  $(".go-back").click(function(){
    if(THC.lastId == undefined)
      return;

    THC.setCurrent(THC.lastId);
  });
}

THC.setCurrent = function(id){
  THC.lastId = THC.currentId;
  THC.currentId = id;
  $(".image").attr("src", THC.config.urls.img+THC.currentId+THC.config.urls.filetype);
}
